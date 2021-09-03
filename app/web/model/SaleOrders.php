<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/15
 * Time: 11:54
 */

namespace app\web\model;

use app\common\model\web\SaleOrders as SaleOrdersModel;
use app\common\utils\BuildCode;
use app\web\model\SaleOrdersDetails as SaleOrdersDetailsMOdel;
use app\web\model\GoodsStock as GoodsStockModel;
use think\facade\Db;
use app\web\model\Settlement as SettlementModel;
use think\Exception;
use app\web\model\ClientAccount as ClientAccountModel;
use app\web\model\DiaryClient as DiaryClientModel;

class SaleOrders extends SaleOrdersModel
{
    public function loadData($where, $page, $rows)
    {
        $query = $this->alias('so')
            ->join('hr_client_base cb', 'so.client_id = cb.client_id', 'left')
            ->where(['so.com_id' => $where['com_id']])
            // 不为临时单据
            ->where('so.orders_status', '<>', 7)
            //单据状态
            ->when(isset($where['status']) && $where['status'] != -1, function ($query) use ($where) {
                $query->where(['orders_status' => $where['status']]);
            })


            //客户名字
            ->when(isset($where['search_client_name']) && $where['search_client_name'], function ($query) use ($where) {
                $query->where('cb.client_name', 'like', '%' . $where['search_client_name'] . '%');
            })
            //销售订单号
            ->when(isset($where['search_orders_code']) && $where['search_orders_code'], function ($query) use ($where) {
                $query->where('so.orders_code', 'like', '%' . $where['search_orders_code'] . '%');
            })
            // 单据开始时间
            ->when(isset($where['search_date_begin']) && $where['search_date_begin'], function ($query) use ($where) {
                $query->where('so.orders_date', '>', strtotime($where['search_date_begin']));
            })
            // 单据结束时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('so.orders_date', '<=', strtotime($where['search_date_end'] . ' +1 day'));
            });
        $total = $query->count();
        $offset = ($page - 1) * $rows;
        $rows = $query->limit($offset, $rows)->field('so.*,cb.client_name')->select();
        return compact('total', 'rows');
    }

    public function saveTransferData($data, $plan_data_details)
    {
        $res = $this->save([
            'orders_code' => $data['orders_code'],
            'client_id' => $data['client_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'user_id' => $data['user_id'],
            'orders_status' => $data['orders_status'],
            'orders_remark' => $data['orders_remark'],
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id']
        ]);
        if (!$res) {
            return false;
        }
        $orders_id = $this->orders_id;
        $sale_order_details = new SaleOrdersDetailsMOdel();
        foreach ($plan_data_details as $key => $item) {
            $item['orders_id'] = $orders_id;
            $item['orders_code'] = $data['orders_code'];
            $res = $sale_order_details->addTransfer($item);
            if (!$res) {
                return false;
            }
        }
        return true;
    }

    public function getOrdersDetails($where)
    {
        return $this->alias('so')
            ->with('details')
            ->join('hr_client_base cb', 'so.client_id=cb.client_id', 'left')
            ->join('hr_user user', 'so.salesman_id=user.user_id', 'left')
            ->join('hr_dict d', 'so.delivery_id=d.dict_id', 'left')
            ->join('hr_settlement s', 'so.settlement_id=s.settlement_id', 'left')
            ->join('hr_organization o', 'so.warehouse_id=o.org_id', 'left')
            ->where('so.com_id', $where['com_id'])
            ->where('so.orders_code', $where['orders_code'])
            ->field('so.*,cb.client_name,user.user_name,d.dict_name,s.settlement_name,o.org_name')
            ->find();
    }

    public function updatePlane($data)
    {
        // 单据编号为空则为添加
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('SO');
            $info = $this;
        } else {
            $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
            if (empty($info)) {
                $this->setError('订单不存在');
                return false;
            }
            if ($info['orders_status'] == 9) {
                $this->setError('已完成单据不允许操作');
                return false;
            }
            if ($info['lock_version'] != $data['lock_version']) {
                $this->setError('更新内容不是最新的版本');
            }
            $data['lock_version'] = $data['lock_version'] + 1;
        }
        $time_now = time();
        Db::startTrans();
        try {
            //更新salePlan表
            $info->saveData($data);
            $orders_code = $data['orders_code'];
            $detailModel = new SaleOrdersDetailsMOdel();
            //删除detail
            $data_delete = json_decode($data['data_delete'], true);
            if ($data_delete && is_array($data_delete)) {
                $delete_details_ids = array_column($data_delete, 'details_id');
                $res = $detailModel->where('details_id', 'in', $delete_details_ids)
                    ->where('orders_code', $orders_code)
                    ->delete();
                if (!$res) {
                    throw  new Exception('删除订单详情失败');
                }
            }
            //添加detail
            $data_insert = json_decode($data['data_insert'], true);
            if ($data_insert && is_array($data_insert)) {
                foreach ($data_insert as $item) {
                    $item = array_merge($item, [
                        'com_id' => $data['com_id'],
                        'time_now' => $time_now,
                        'orders_id' => $info['orders_id'],
                        'orders_code' => $info['orders_code'],
                    ]);
                    $res1 = $detailModel->add($item);
                    if (!$res1) {
                        throw new Exception('添加订单详情失败');
                    }
                }
            }
            //修改details
            $data_update = json_decode($data['data_update'], true);
            if ($data_update && is_array($data_update)) {
                foreach ($data_update as $val) {
                    $detailInfo = $detailModel->getOne(['details_id' => $val['details_id'], 'orders_code' => $orders_code]);
                    if (empty($detailInfo)) {
                        throw new Exception('更新订单不存在');
                    }
                    if ($detailInfo['lock_version'] != $val['lock_version']) {
                        throw new Exception('不是最新版本');
                    }
                    // 指定的字段
                    $val['lock_version'] = $val['lock_version'] + 1;
                    $val['com_id'] = $info['com_id'];
                    $val['orders_id'] = $info['orders_id'];
                    $val['orders_code'] = $info['orders_code'];
                    $detailInfo->saveData($val);
                }
            }
            //如果提交正式则执行扣款,和减少商品数量操作
            if ($data['orders_status'] == 9) {
                //更新库存和账户
                $this->updateStockMoney($data['com_id'], $data['orders_code']);
                //写入客户流水
                $this->RecordDiaryClient($data,38);
            }
            Db::commit();
            return $info['orders_code'];
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage() . $e->getLine());
            return false;
        }
    }

    public function saveData($data)
    {
        return $this->save(
            [
                'orders_code' => $data['orders_code'],
                'client_id' => $data['client_id'],
                'orders_pmoney' => $data['orders_pmoney'],
                'goods_number' => $data['goods_number'],
                'user_id' => $data['user_id'],
                'orders_status' => $data['orders_status'],
                'orders_remark' => $data['orders_remark'],
                'orders_date' => strtotime($data['orders_date']),
                'com_id' => $data['com_id'],
                'salesman_id' => $data['salesman_id'],
                'orders_rmoney' => $data['orders_rmoney'],
                'warehouse_id' => $data['warehouse_id'],
                'settlement_id' => $data['settlement_id'],
                'delivery_id' => $data['delivery_id'],
                'other_type' => $data['other_type'],
                'other_money' => $data['other_money'],
                'erase_money' => $data['erase_money'],
                'lock_version' => $data['lock_version'],

            ]
        );
    }

    /**
     * Notes:删除
     * User:ccl
     * DateTime:2021/1/26 15:25
     * @param $Where
     * @return bool
     */
    public function delSaleOrders($Where)
    {
        $data = $this->getOrdersDetails($Where);
        if (empty($data)) {
            $this->setError('销售单不存在');
            return false;
        }
        if ($data['orders_status'] != 0) {
            $this->setError('销售单状态不是草稿不能删除');
            return false;
        }
        return $data->together(['details'])->delete();
    }

    /**
     * Notes:提交正式后更新客户账户余额，库存
     * User:ccl
     * DateTime:2021/1/26 15:34
     * @param $data
     */
    public function updateStockMoney($com_id, $orders_code)
    {
        $details_info = $this->getOrdersDetails(['com_id' => $com_id, 'orders_code' => $orders_code]);
        if (!$details_info) {
            throw new Exception('(更新库存账户金额)获取销售单详情失败');
        }
        //更新库存
        $this->baseSaveOrdersToStock($details_info);
        //更新收款账户金额
        $this->UpdatePayeeAmount($details_info['settlement_id'], $details_info['orders_rmoney'], $details_info['com_id']);
        //更新支付账户金额
        $this->UpdatePayAmount($details_info['client_id'], $details_info['orders_rmoney'], $details_info['com_id']);
    }

    /**
     * Notes:更新商品库存
     * User:ccl
     * DateTime:2021/1/26 16:55
     * @param $detail_info
     * @throws \think\Exception
     */
    public function baseSaveOrdersToStock($detail_info)
    {
        foreach ($detail_info['details'] as $item) {
            $data = $item;
            $data['warehouse_id'] = $detail_info['warehouse_id'];
            $data['orders_code'] = $detail_info['orders_code'];
            $data['stock_number'] = $data['goods_number'] * -1;
            $goods_stock = new GoodsStockModel();
            $goods_stock->updateOrInsert($data, '销售单');
        }
    }

    /**
     * Notes:更新收款账户金额(增加)
     * User:ccl
     * DateTime:2021/1/26 17:01
     */
    public function UpdatePayeeAmount($settlement_id, $money, $com_id)
    {
        $settlement = new SettlementModel();
        $data = $settlement->where(['settlement_id' => $settlement_id, 'com_id' => $com_id])->field('settlement_money,lock_version')->find();
        if (!$data) {
            throw new Exception('收款账户余额查询失败');
        }
        $settlement_money = bcadd($data['settlement_money'], $money, 2);
        $lock_version = $data['lock_version'] + 1;
        $res = $settlement->where(['settlement_id' => $settlement_id, 'com_id' => $com_id])->update(['settlement_money' => $settlement_money, 'lock_version' => $lock_version]);
        if (!$res) {
            throw new Exception('收款账户进账失败');
        }
    }

    /**
     * Notes:更新支付账户金额(减少)
     * User:ccl
     * DateTime:2021/1/26 17:28
     */
    public function UpdatePayAmount($client_id, $money, $com_id)
    {
        $client_account = new ClientAccountModel();
        $data = $client_account->where(['client_id' => $client_id, 'com_id' => $com_id])->field('account_money,lock_version')->find();
        if (!$data) {
            throw new Exception('支付账户余额查询失败');
        }
        $account_money = bcsub($data['account_money'], $money, 2);
        $lock_version = $data['lock_version'] + 1;
        $res = $client_account->where(['client_id' => $client_id, 'com_id' => $com_id])->update(['account_money' => $account_money, 'lock_version' => $lock_version]);
        if (!$res) {
            throw new Exception('支付账户扣款失败');
        }
    }

    public function RecordDiaryClient($data,$type)
    {
        $diary_client = new DiaryClientModel();
        $client_account = new ClientAccountModel();
        $sttlement = new SettlementModel();
        //获取单据信息
        $order_info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
        if (!$order_info) {
            throw new Exception('(写入客户流水)获取单据信息失败');
        }
        //获取客户账户余额
        $account_info = $client_account->getOne(['com_id' => $order_info['com_id'], 'client_id' => $order_info['client_id']]);
        if (!$account_info) {
            throw new Exception('(写入客户流水)获取客户余额失败');
        }
        //获取结算账户余额
        $sttlement_money = $sttlement->getOne(['com_id' => $order_info['com_id'], 'settlement_id' => $order_info['settlement_id']], 'settlement_money');
        if (!$sttlement_money) {
            throw new Exception('(写入客户流水)获取结算账户余额失败');
        }
        //账户余额加订单应收金额
        $pbalance = bcadd($account_info['account_money'], $order_info['orders_pmoney'], 2);
        $data = [
            'client_id' => $order_info['client_id'],
            'orders_code' => $order_info['orders_code'],
            'user_id' => $order_info['user_id'],
            'settlement_id' => $order_info['settlement_id'],
            'account_id' => $type,
            'pmoney' => $order_info['orders_pmoney'],
            'rmoney' => $order_info['orders_rmoney'],
            'pbalance' => $pbalance,
            //结算账户余额
            'settlement_balance' => $sttlement_money['settlement_money'],
            'client_balance' => $account_info['account_money'],
            'create_time' => strtotime($order_info['create_time']),
            'remark' => $order_info['orders_remark'],
            'com_id' => $order_info['com_id']
        ];
        $res = $diary_client->addRecord($data);
        if (!$res) {
            throw new Exception('写入客户流水失败');
        }
    }

    /**
     * Notes:销售单撤销
     * User:ccl
     * DateTime:2021/1/28 16:22
     * @param $detail_info
     */
    public function revokeSaleOrders($detail_info,$version)
    {
        try {
            Db::startTrans();
            //商品退回增加库存
            foreach ($detail_info['details'] as $item) {
                $data = $item;
                $data['warehouse_id'] = $detail_info['warehouse_id'];
                $data['orders_code'] = $detail_info['orders_code'];
                $data['stock_number'] = $data['goods_number'];
                $goods_stock = new GoodsStockModel();
                $res = $goods_stock->updateOrInsert($data, '销售单撤销');
                if (!$res) {
                    throw new Exception('(撤销销售单)修改库存失败');
                }
            }
            //客户账户金额
            $client_account = new ClientAccountModel();
            $account_money = $client_account->getOne(['com_id' => $detail_info['com_id'], 'client_id' => $detail_info['client_id']], 'account_money,lock_version');
            if (!$account_money) {
                throw new Exception('(撤销销售单)获取客户账户余额失败');
            }
            //返回金额增加
            $accpunt_version = $account_money['lock_version'] + 1;
            $client_money = bcadd($account_money['account_money'], $detail_info['orders_rmoney'],2);
            $res2 = $account_money->where(['com_id' => $detail_info['com_id'], 'client_id' => $detail_info['client_id']])->update(['account_money' => $client_money, 'lock_version' => $accpunt_version]);
            if (!$res2) {
                throw new Exception('(撤销销售单)更新客户金额失败');
            }

            //收款账户金额减少
            $settlement = new SettlementModel();
            $settlement_money = $settlement->where(['settlement_id' => $detail_info['settlement_id'], 'com_id' => $detail_info['com_id']])->field('settlement_money,lock_version')->find();
            if (!$data) {
                throw new Exception('(撤销销售单)收款账户余额查询失败');
            }
            $settlement_money['settlement_money'] = bcsub($settlement_money['settlement_money'], $detail_info['orders_rmoney'], 2);
            $settlement_money['lock_version'] =  $settlement_money['lock_version'] + 1;
            $res = $settlement->where(['settlement_id' => $detail_info['settlement_id'], 'com_id' => $detail_info['com_id']])->update(['settlement_money' => $settlement_money['settlement_money'], 'lock_version' =>  $settlement_money['lock_version']]);
            if (!$res) {
                throw new Exception('(撤销销售单)收款账户退款失败');
            }
            //写入客户流水
            $this->RecordDiaryClient($detail_info,45);
            //修改销售单状态
            if($version != $detail_info['lock_version']){
                throw new Exception('(撤销销售单)销售单版本号不一致');
            }else{
             $version = $version + 1;
             $res3 = $this->where(['com_id'=> $detail_info['com_id'],'orders_code' => $detail_info['orders_code']])->update(['lock_version' => $version,'orders_status' => 1]);
             if(!$res3){
                 throw new Exception('(撤销销售单)修改销售单状态失败');
             }
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage() . $e->getLine());
            return false;
        }
    }

}