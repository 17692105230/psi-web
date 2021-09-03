<?php


namespace app\web\model;

use app\common\model\web\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
use think\facade\Db;
use Exception;
use app\common\utils\BuildCode;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\model\Supplier as SupplierModel;
use app\web\model\DiarySupplier as DiarySupplierModel;
class PurchaseOrders extends PurchaseOrdersModel
{

    /**
     * @desc  采购单页面展示
     * @param $where
     * @return array
     * Date: 2021/1/6
     * Time: 11:07
     * @author myy
     */
    public function getPageList($where)
    {
        $query = $this->alias('o')
            ->join('hr_supplier s', 'o.supplier_id=s.supplier_id', 'left')
            ->where('o.com_id', $where['com_id'])
            // 供应商名称搜索
            ->when(isset($where['supplier_name']) && $where['supplier_name'], function ($query) use ($where) {
                $query->where('s.supplier_name', 'like', '%' . $where['supplier_name'] . '%');
            })
            // 单据编号
            ->when(isset($where['orders_code']) && $where['orders_code'], function ($query) use ($where) {
                $query->where('o.orders_code', 'like', '%' . $where['orders_code'] . '%');
            })
            // 单据状态
            ->when(isset($where['orders_status']) && $where['orders_status'] != -1, function ($query) use ($where) {
                $query->where('o.orders_status', $where['orders_status']);
            })
            // 单据开始时间
            ->when(isset($where['search_date_begin']) && $where['search_date_begin'], function ($query) use ($where) {
                $query->where('o.orders_date', '>', strtotime($where['search_date_begin']));
            })
            // 单据结束时间
            ->when(isset($where['search_date_end']) && $where['search_date_end'], function ($query) use ($where) {
                $query->where('o.orders_date', '<=', strtotime($where['search_date_end'] . ' +1 day'));
            });

        $total = $query->count();
        list($offset, $limit) = pageOffset($where);
        $rows = $query->field('o.*,s.supplier_name')->order('o.orders_date desc')->limit($offset, $limit)->select();

        return compact('total', 'rows');
    }

    /**
     * @desc  查询订单详情
     * @param $where
     * @return mixed
     * Date: 2021/1/6
     * Time: 16:42
     * @author myy
     */
    public function getOrderDetail($where)
    {
        return $this->alias('o')
            ->with('details')
            ->join('hr_supplier s', 'o.supplier_id=s.supplier_id', 'left')
            ->join('hr_organization z','o.warehouse_id = z.org_id','left')
            ->join('hr_settlement t','t.settlement_id = o.settlement_id','left')
            ->where('o.com_id', $where['com_id'])
            ->where('o.orders_code', $where['orders_code'])
            ->field('o.*,s.supplier_name,z.org_name,t.settlement_name')
            ->find();
    }

    /**
     * @desc 删除订单
     * @param $where
     * @return bool
     * Date: 2021/1/6
     * Time: 16:43
     * @author myy
     */
    public function delOrder($where)
    {
        $data = $this->getOrderDetail($where);
        if (empty($data)) {
            $this->setError('订单不存在');
            return false;
        }
        if ($data['orders_status'] != 0) {
            $this->setError('订单状态不是草稿状态不能删除');
            return false;
        }
        return $data->together(['details'])->delete();
    }

    public function updateOrder($data)
    {

        // 空订单号为添加
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('PO');
            $info = $this;
        } else {
            $info = $this->getOne(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
            if (empty($info)) {
                $this->setError('订单不存在');
                return false;
            }
            /* 查询单据状态 */
            if ($info['orders_status'] == 9) {
                $this->setError('已完成单据不允许操作~~');
                return false;
            }
            /* 查看锁版本是否正确 */
            if ($info['lock_version'] != $data['lock_version']) {
                $this->setError('更新内容不是最新版本');
                return false;
            }
            $data['lock_version'] = $data['lock_version'] + 1;
        }

        $time_now = time();
        // 开始修改
        Db::startTrans();
        try {
            //更新order表
            $info->saveData($data);
            $orders_code = $info['orders_code'];
            $detailModel = new PurchaseOrdersDetailsModel();
            // 要删除的detail
            $data_delete = json_decode($data['data_delete'], true);
            if ($data_delete && is_array($data_delete)) {
                $delete_detail_ids = array_column($data_delete, 'details_id');
                $res = $detailModel->where('details_id', 'in', $delete_detail_ids)
                    ->where('orders_code', $orders_code)
                    ->delete();
                if (!$res) {
                    throw new Exception('删除订单详情失败');
                }
            }
            // 要添加的detail
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
            // 要修改的 detail
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
            $detail_info = $this->getOrderDetail(['com_id' => $info['com_id'], 'orders_code' => $info['orders_code']]);
            //查出仓库名称
            $orgModel = new Organization();
            $warehouse_name = $orgModel->where(['com_id' => $info['com_id'],
                'org_id' => $data['warehouse_id'],
                'org_type' => 2])->field('org_name')->find();
            //查询结算账户名称
            $settlement = new Settlement();
            $settlement_name = $settlement->where(['com_id' => $info['com_id'],
                'settlement_id' => $data['settlement_id']
                ])->field('settlement_name')->find();

            //拼接到 返回的 数组中
            $detail_info['settlement_name'] = $settlement_name['settlement_name'];
            $detail_info['warehouse_name'] = $warehouse_name['org_name'];
            // 提交采购 正式单据则需要进行的操作
            if ($data['orders_status'] == 9) {
                //当前单据类型
                $data['item_type'] = 9103;
                // 更新库存
                $this->baseSaveOrdersToStock($detail_info);
                // 更新供应商金额
                $this->baseSaveOrdersToSupplier($data);
                //更新供应商对账
                $this->baseSaveOrdersToDiarySupplier($data);
            }
            Db::commit();
            return $detail_info;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage() . $e->getLine());
            return false;
        }

    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'lock_version' => $data['lock_version'],
            'orders_remark' => $data['orders_remark'],
            'supplier_id' => $data['supplier_id'],
            'settlement_id' => $data['settlement_id'],
            'warehouse_id' => $data['warehouse_id'],
            'orders_pmoney' => $data['orders_pmoney'],
            'orders_rmoney' => $data['orders_rmoney'],
            'goods_number' => $data['goods_number'],
            'other_type' => $data['other_type'],
            'other_money' => $data['other_money'],
            'erase_money' => $data['erase_money'],
            'orders_status' => isset($data['orders_status']) ? $data['orders_status'] : 0,
            'orders_date' => strtotime($data['orders_date']),
            'com_id' => $data['com_id'],
            'user_id' => $data['user_id']
        ]);
    }

    public function baseSaveOrdersToStock($detail_info)
    {

        foreach ($detail_info['details'] as $item) {
            $data = $item->toArray();
            $data['warehouse_id'] = $detail_info['warehouse_id'];
            $data['orders_code'] = $detail_info['orders_code'];
            $data['stock_number'] = $data['goods_number'];
            (new GoodsStockModel())->updateOrInsert($data, '采购单');
        }
    }

    /**
     * 函数描述:更新供应商金额
     * Date: 2021/1/20
     * Time: 11:00
     * @author mll
     */
    public function baseSaveOrdersToSupplier($data)
    {
        $supplier = new SupplierModel();
        $supplier_money = $supplier->getOne(['supplier_id'=>$data['supplier_id']],'supplier_money');
        if (!$supplier_money){
            throw new Exception('为查找到相应供应商信息');
        }
        $new_supplier_money = bcsub($supplier_money['supplier_money'],$data['orders_rmoney']);
        $new = [
            'supplier_money'=>$new_supplier_money
        ];
        if (!$supplier->where(['supplier_id'=>$data['supplier_id']])->save($new)){
            throw new Exception('更新供应商金额出错');
        }
    }

    /**
     * 函数描述: 更新供应商对账
     * @param $data
     * Date: 2021/1/21
     * Time: 17:00
     * @author mll
     */
    public function baseSaveOrdersToDiarySupplier($data)
    {
        //查出供应商对应的账户余额
        $supplier = new SupplierModel();
        $supplier_money = $supplier->getOne(['supplier_id'=>$data['supplier_id']],'supplier_money');
        if (!$supplier_money){
            throw new Exception('供应商信息未找到');
        }
        $data['supplier_money'] = $supplier_money['supplier_money'];
        $diary_data = [
            'supplier_id' => $data['supplier_id'],
            'orders_code' => $data['orders_code'],
            'settlement_id' => $data['settlement_id'],
            'pmoney' => $data['orders_pmoney'],
            'rmoney' => $data['orders_rmoney'],
            'pbalance' => bcsub($data['orders_pmoney'],$data['orders_rmoney']),
            'supplier_balance' => $data['supplier_money'],
            'remark' => $data['orders_remark'],
            'com_id' => $data['com_id'],
            'item_type' => $data['item_type'],
            'order_date' => strtotime($data['orders_date'])
        ];
        //供应商对账model
        $diaryModel = new DiarySupplierModel();
        if(!$diaryModel->save($diary_data)){
            throw new Exception('供应商对账信息添加失败');
        }
    }

    /**
     * 函数描述:采购订单流转采购单
     * @param $data
     * @param $detail
     * Date: 2021/1/22
     * Time: 11:59
     * @author mll
     */
    public function saveTransferData($data,$detail)
    {
        $res = $this->save([
            'orders_code' => $data['orders_code'],
            'supplier_id' => $data['supplier_id'],
            //合计金额
            'orders_pmoney' => $data['orders_pmoney'],
            'goods_number' => $data['goods_number'],
            'orders_status' => $data['orders_status'],
            'orders_remark' => $data['orders_remark'],
            'orders_date' => strtotime($data['orders_date']),
            'lock_version' => $data['lock_version'],
            'com_id' => $data['com_id'],
            'warehouse_id' => $data['warehouse_id']
        ]);
        if (!$res){
            return false;
        }
        $orders_id = $this->orders_id;
        $purchaseDetailModel = new PurchaseOrdersDetailsModel();
        foreach ($detail as $key=>$item){
            $item['orders_id'] = $orders_id;
            $item['orders_code'] = $data['orders_code'];
            $res = $purchaseDetailModel->add($item);
            if (!$res){
                return false;
            }
        }
        return true;
    }

    /**
     * 函数描述: 采购单撤销
     * @param $detail
     * Date: 2021/1/27
     * Time: 15:45
     * @author mll
     */
    public function revokePuchaseOrders($detail_info)
    {
        $data_info = $this->getOrderDetail(['com_id'=>$detail_info['com_id'],'orders_code'=>$detail_info['orders_code']]);

        /**
         * 1.修改状态之前先去减库存
         * 2.根据传过来的实付金额，对供应商进行添加金额
         * 3.供应商对账添加一条记录
         */
        try {
            Db::startTrans();
            //减少库存
            foreach ($detail_info['details'] as $item) {
                $data = $item->toArray();
                $data['warehouse_id'] = $detail_info['warehouse_id'];
                $data['orders_code'] = $detail_info['orders_code'];
                $data['stock_number'] = $data['goods_number'];
                $goods_stock = (new GoodsStockModel())->updateOrInsert($data, '采购单撤销');
                if (!$goods_stock){
                    throw new Exception('修改库存出错');
                }
            }
            //供应商添加金额
            //先查询出来当前金额
            $supplier_model = new SupplierModel();
            $where_supplier = [
                'supplier_id' => $detail_info['supplier_id'],
                'com_id' => $detail_info['com_id']
            ];
            $supplier_rmoney = $supplier_model->where($where_supplier)->field('supplier_money')->find();
            if (!$supplier_rmoney){
                throw new Exception('为找到相关供应商');
            }
            //将撤销单的金额+上供应商本来拥有的金额
            $new_money['supplier_money'] = bcadd($supplier_rmoney['supplier_money'] , $detail_info['orders_rmoney']);
            //将新金额更新到供应商表中
            $supplier_res = $supplier_model->where($where_supplier)->save($new_money);
            if (!$supplier_res){
                throw new Exception('更新供应商金额出错');
            }
            Db::commit();
            return $data_info;
        }catch (Exception $e) {
            Db::rollback();
            $this->setError('修改失败:' . $e->getMessage() . $e->getLine());
            return false;
        }

    }

    public function getPurchaseOrders($where)
    {
        $condtion = $this->alias('o')
            ->where('o.com_id',$where['com_id'])
            ->where('o.orders_status','<>',7)
            //供应商
            ->when(isset($where['supplier_id']) && $where['supplier_id'],function ($query) use ($where){
                $query->where('o.supplier_id',$where['supplier_id']);
            })
            ->when(isset($where['warehouse_id']) && $where['warehouse_id'],function ($query) use ($where){
                $query->where('oi.org_id',$where['warehouse_id']);
            })
            ->when(isset($where['orders_code']) && $where['orders_code'],function ($query) use ($where){
                $query->where('o.orders_code',$where['orders_code']);
            })
            ->when(isset($where['goods_status']) && $where['goods_status'],function ($query) use ($where){
                $query->where('o.orders_status',$where['goods_status']);
            })
            //如果只有开始时间
            ->when(isset($where['begin_date']) && $where['begin_date'],function ($query) use ($where){
                $query->where('o.update_time','>=',$where['begin_date']);
            })
            //如果只有结束时间
            ->when(isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->where('o.update_time','<=',$where['end_date']);
            })
            //开始时间结束时间都有
            ->when(isset($where['begin_date']) && $where['begin_date'] && isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->whereBetween('o.update_time',[$where['begin_date'],$where['end_date']]);
            })
            ->join('hr_user u','o.user_id = u.user_id','left')
            ->join('hr_supplier s','o.supplier_id = s.supplier_id','left')
            ->join('hr_organization oz','oz.org_id = o.warehouse_id','left')
            ->join('hr_settlement sl','sl.settlement_id = o.settlement_id','left');
        $total = $condtion->count();
        $offset = ($where['page'] - 1) * $where['rows'];
        $rows = $condtion
            ->limit($offset,$where['rows'])
            ->field('u.user_name,o.orders_code,o.goods_number,o.orders_pmoney,o.orders_rmoney,
                    o.orders_date,s.supplier_name,oz.org_name,sl.settlement_name')
            ->select();
        $goods_count = $condtion->sum('o.goods_number');
        $goods_money = $condtion->sum('o.orders_pmoney');
        return compact('total','rows','goods_count','goods_money');
    }

}