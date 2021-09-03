<?php


namespace app\web\model;

use think\facade\Db;
use Exception;
use app\common\model\web\DiaryFinance as DiaryFinanceModel;
use app\web\model\Settlement as SettlementModel;

class DiaryFinance extends DiaryFinanceModel
{

    public function saveAccountIn($data)
    {
        Db::startTrans();
        try {
            $settleModel = new SettlementModel();
            $settleInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $data['settlement_id']]);
            if (empty($settleInfo)) {
                throw new Exception('账户不存在');
            }
            // 更新账户金额
            $settleInfo->save(['settlement_money' => $settleInfo['settlement_money'] + $data['income']]);
            $data['settlement_balance'] = $settleInfo['settlement_money'];
            // 写入流水
            $this->saveData($data);
            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return false;
        }
    }

    public function saveAccountOut($data)
    {
        Db::startTrans();
        try {
            $settleModel = new SettlementModel();
            $settleInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $data['settlement_id']]);
            if (empty($settleInfo)) {
                throw new Exception('账户不存在');
            }
            // 更新账户金额
            $settleInfo->save(['settlement_money' => $settleInfo['settlement_money'] - $data['expend']]);
            $data['settlement_balance'] = $settleInfo['settlement_money'];
            // 写入流水
            $this->saveData($data);
            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return false;
        }
    }


    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'account_id' => $data['account_id'],
            'settlement_id' => $data['settlement_id'],
            'counterparty' => $data['counterparty'],
            'user_id' => $data['user_id'],
            'income' => $data['income'],
            'item_type' => $data['item_type'],
            'order_date' => $data['order_date'],
            'com_id' => $data['com_id'],
            'settlement_balance' => $data['settlement_balance'],
            'surplus' => isset($data['surplus']) ? $data['surplus'] : 0,
            'remark' => isset($data['remark']) ? $data['remark'] : '',
            'shop_id' => isset($data['shop_id']) ? $data['shop_id'] : 0,
            'expend' => isset($data['expend']) ? $data['expend'] : 0,
        ]);
    }

    public function queryAccountList($param)
    {
        $baseQuery = $this->alias('f')
            ->join('hr_dict d', 'd.dict_id = f.account_id', 'left')
            ->join('hr_user user', 'user.user_id = f.user_id', 'left')
            ->join('hr_settlement settlement', 'settlement.settlement_id = f.settlement_id', 'left')
            ->when(array_get($param, 'account_id'), function ($query) use ($param) {
                $query->where('f.account_id', $param['account_id']);
            })->when(array_get($param, 'settlement_id'), function ($query) use ($param) {
                $query->where('f.settlement_id', $param['settlement_id']);
            })
            ->when(array_get($param, 'orders_code'), function ($query) use ($param) {
                $query->where('f.orders_code', 'like', '%' . $param['orders_code'] . '%');
            })
            ->when(array_get($param, 'begin_date'), function ($query) use ($param) {
                $query->where('f.order_date', '>', $param['begin_date']);
            })
            ->when(array_get($param, 'end_date'), function ($query) use ($param) {
                $query->where('f.order_date', '<=', $param['end_date']);
            });

        list($offset, $limit) = pageOffset($param);

        $rows = $baseQuery->field(['f.*', 'd.dict_name as account_name', 'user.user_name', 'settlement.settlement_name'])
            ->limit($offset, $limit)
            ->order('f.order_date desc')
            ->order('f.create_time desc')
            ->select();

        $total = $baseQuery->count();
        $income = $baseQuery->sum('f.income');

        $sum = (new self)->alias('f')
            ->join('hr_dict d', 'd.dict_id = f.account_id', 'left')
            ->when(array_get($param, 'account_id'), function ($query) use ($param) {
                $query->where('f.account_id', $param['account_id']);
            })->when(array_get($param, 'settlement_id'), function ($query) use ($param) {
                $query->where('f.settlement_id', $param['settlement_id']);
            })
            ->when(array_get($param, 'orders_code'), function ($query) use ($param) {
                $query->where('f.orders_code', 'like', '%' . $param['orders_code'] . '%');
            })
            ->when(array_get($param, 'begin_date'), function ($query) use ($param) {
                $query->where('f.order_date', '>', $param['begin_date']);
            })
            ->when(array_get($param, 'end_date'), function ($query) use ($param) {
                $query->where('f.order_date', '<=', $param['end_date']);
            })->field('ifnull(SUM(f.income),0) as income,ifnull(SUM(f.expend),0) as expend,ifnull(SUM(f.surplus),0) as surplus')->limit(1)->select();

        return compact('total', 'rows', 'sum', 'income');
    }

    // 账户互转
    public function saveAccountLoop($data)
    {
        Db::startTrans();
        try {
            $settlementIdOut = $data['settlement_id_out'];
            $settlementIdIn = $data['settlement_id_in'];
            $taMoney = $data['ta_money'];
            $settleModel = new SettlementModel();
            $settleOutInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $settlementIdOut]);
            if (empty($settleOutInfo)) {
                throw new Exception('转出账户不存在');
            }

            $settleInInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $settlementIdIn]);
            if (empty($settleInInfo)) {
                throw new Exception('转出账户不存在');
            }

            // 更新账户金额

            $settleOutInfo->save(['settlement_money' => $settleOutInfo['settlement_money'] - $taMoney]);
            $settleInInfo->save(['settlement_money' => $settleInInfo['settlement_money'] + $taMoney]);

            $tim_now = time();
            $insert_out = [
                'settlement_id' => $settlementIdOut,
                'orders_code' => $data['orders_code'],
                'account_id' => $data['account_id'],
                'counterparty' => $data['counterparty'],
                'item_type' => $data['item_type'],
                'order_date' => $data['order_date'],
                'user_id' => $data['user_id'],
                'income' => 0,
                'expend' => $taMoney,
                'surplus' => $taMoney * -1,
                'settlement_balance' => $settleOutInfo['settlement_money'],
                'remark' => isset($data['remark']) ? $data['remark'] : '',
                'com_id' => $data['com_id'],
                'create_time' => $tim_now,
                'update_time' => $tim_now,
            ];

            $out_id = $this->insertGetId($insert_out);
            if (!$out_id) {
                throw  new Exception('写入流水失败');
            }

            $insert_in = [
                'settlement_id' => $settlementIdIn,
                'orders_code' => $data['orders_code'],
                'account_id' => $data['account_id'],
                'counterparty' => $data['counterparty'],
                'item_type' => $data['item_type'],
                'order_date' => $data['order_date'],
                'user_id' => $data['user_id'],
                'income' => $taMoney,
                'expend' => 0,
                'surplus' => $taMoney,
                'settlement_balance' => $settleInInfo['settlement_money'],
                'remark' => isset($data['remark']) ? $data['remark'] : '',
                'com_id' => $data['com_id'],
                'create_time' => $tim_now,
                'update_time' => $tim_now,
            ];
            $in_id = $this->insertGetId($insert_in);
            if (!$in_id) {
                throw  new Exception('写入流水失败');
            }

            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage() . '/' . $e->getLine());
            Db::rollback();
            return false;
        }
    }

    public function saveAccountBegin($data)
    {
        Db::startTrans();
        try {
            $settlement_id = $data['settlement_id'];
            $income = $data['income'];
            $settleModel = new SettlementModel();
            $settleOutInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $settlement_id]);
            if (empty($settleOutInfo)) {
                throw new Exception('账户不存在');
            }
            // 更新账户金额
            $settleOutInfo->save(['settlement_money' => $settleOutInfo['settlement_money'] + $income]);
            $tim_now = time();
            $insert_out = [
                'settlement_id' => $settlement_id,
                'orders_code' => $data['orders_code'],
                'account_id' => $data['account_id'],
                'counterparty' => $data['counterparty'],
                'item_type' => $data['item_type'],
                'order_date' => $data['order_date'],
                'user_id' => $data['user_id'],
                'income' => $income,
                'expend' => 0,
                'surplus' => $income,
                'settlement_balance' => $settleOutInfo['settlement_money'],
                'remark' => isset($data['remark']) ? $data['remark'] : '',
                'com_id' => $data['com_id'],
                'create_time' => $tim_now,
                'update_time' => $tim_now,
            ];

            $res = $this->insertGetId($insert_out);
            if (!$res) {
                throw  new Exception('写入流水失败');
            }

            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage() . '/' . $e->getLine());
            Db::rollback();
            return false;
        }
    }

    public function updateAccountIn($data)
    {
        Db::startTrans();
        try {
            $info = $this->getOne(['details_id' => $data['details_id'], 'com_id' => $data['com_id']]);
            if (empty($info)) {
                throw new Exception('未周到要修改的数据');
            }
            // 收入回滚
            $settleModel = new SettlementModel();
            $res1 = $settleModel->where('settlement_id', $info['settlement_id'])->dec('settlement_money', $info['income'])->update();
            if (!$res1) {
                throw new Exception('账户资金回滚失败');
            }
            // 调整新的账户资金
            $settlementInfo = $settleModel->where('settlement_id', $data['settlement_id'])->find();
            if (empty($settlementInfo)) {
                throw new Exception('结算账户不存在');
            }
            $res2 = $settlementInfo->save([
                'settlement_money' => $settlementInfo['settlement_money'] + $data['income']
            ]);

            if (!$res2) {
                throw new Exception('账户资金修改失败');
            }
            // 锁版本确认
            if ($data['lock_version'] != $info['lock_version']) {
                throw new Exception('系统数据错误，数据已被其他用户更改，请重新获取');
            }
            // 修改详细信息
            $info->save([
                'settlement_id' => $data['settlement_id'],
                'account_id' => $data['account_id'],
                'income' => $data['income'],
                'surplus' => $data['surplus'],
                'user_id' => $data['user_id'],
                'remark' => $data['remark'],
                'order_date' => $data['order_date'],
                'settlement_balance' => $settlementInfo['settlement_money'],
                'lock_version' => $data['lock_version'] + 1,
            ]);
            Db::commit();
            return true;
        } catch (Exception $exception) {
            Db::rollback();
            $this->setError($exception->getMessage() . '/' . $exception->getLine());
            return false;
        }
    }


    // 更新账户支出
    public function updateAccountOut($data)
    {
        Db::startTrans();
        try {
            // $this->getOne();
            $info = $this->getOne(['details_id' => $data['details_id'], 'com_id' => $data['com_id']]);
            if (empty($info)) {
                throw new Exception('未找到要修改的数据');
            }
            // 收入回滚
            $settleModel = new SettlementModel();
            $res1 = $settleModel->where('settlement_id', $info['settlement_id'])->inc('settlement_money', $info['expend'])->update();
            if (!$res1) {
                throw new Exception('账户资金回滚失败');
            }
            // 调整新的账户资金
            $settlementInfo = $settleModel->where('settlement_id', $data['settlement_id'])->find();
            if (empty($settlementInfo)) {
                throw new Exception('结算账户不存在');
            }
            $res2 = $settlementInfo->save([
                'settlement_money' => $settlementInfo['settlement_money'] - $data['expend']
            ]);

            if (!$res2) {
                throw new Exception('账户资金修改失败');
            }
            // 锁版本确认
            if ($data['lock_version'] != $info['lock_version']) {
                throw new Exception('系统数据错误，数据已被其他用户更改，请重新获取');
            }
            // 修改详细信息
            $info->save([
                'settlement_id' => $data['settlement_id'],
                'account_id' => $data['account_id'],
                'expend' => $data['expend'],
                'surplus' => $data['surplus'],
                'user_id' => $data['user_id'],
                'remark' => $data['remark'],
                'order_date' => $data['order_date'],
                'settlement_balance' => $settlementInfo['settlement_money'],
                'lock_version' => $data['lock_version'] + 1,
            ]);
            Db::commit();
            return true;
        } catch (Exception $exception) {
            Db::rollback();
            $this->setError($exception->getMessage() . '/' . $exception->getLine());
            return false;
        }
    }

    // 修改账户互转
    public function updateAccountLoop($data)
    {
        Db::startTrans();
        try {
            $info = $this->getList(['com_id' => $data['com_id'], 'orders_code' => $data['orders_code']]);
            if (count($info) != 2) {
                throw new Exception('未找到该单据');
            }
            if ($data['lock_version'] != $info[0]['lock_version']) {
                throw new Exception('系统数据错误，数据已被其他用户更改，请重新获取');
            }
            // 确定 原来的收入和支出
            if ($info[0]['income'] > 0) {
                $origin_money = $info[0]['income'];
                $origin_settlement_id_in = $info[0]['settlement_id'];
                $origin_settlement_id_out = $info[1]['settlement_id'];
                $originDiaryOutInfo = $info[1];
                $originDiaryInInfo = $info[0];
            } else {
                $origin_money = $info[1]['income'];
                $origin_settlement_id_in = $info[1]['settlement_id'];
                $origin_settlement_id_out = $info[0]['settlement_id'];
                $originDiaryOutInfo = $info[1];
                $originDiaryInInfo = $info[0];
            }
            // 原始账户还原
            $settleModel = new SettlementModel();
            $res1 = $settleModel->where('settlement_id', $origin_settlement_id_in)
                ->dec('settlement_money', $origin_money)
                ->update();
            if (!$res1) {
                throw new Exception('账户资金回滚失败');
            }
            $res2 = $settleModel->where('settlement_id', $origin_settlement_id_out)
                ->inc('settlement_money', $origin_money)
                ->update();
            if (!$res2) {
                throw new Exception('账户资金回滚失败');
            }

            // 更新修改
            $settlementIdOut = $data['settlement_id_out'];
            $settlementIdIn = $data['settlement_id_in'];
            $taMoney = $data['ta_money'];
            $settleOutInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $settlementIdOut]);
            if (empty($settleOutInfo)) {
                throw new Exception('转出账户不存在');
            }
            $settleInInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $settlementIdIn]);
            if (empty($settleInInfo)) {
                throw new Exception('转入账户不存在');
            }
            // 更新账户金额
            $settleOutInfo->save(['settlement_money' => $settleOutInfo['settlement_money'] - $taMoney]);
            $settleInInfo->save(['settlement_money' => $settleInInfo['settlement_money'] + $taMoney]);

            // 修改原始出账的
            $res3 = $originDiaryOutInfo->save([
                'settlement_id' => $settlementIdOut,
                'user_id' => $data['user_id'],
                'income' => 0,
                'expend' => $taMoney,
                'surplus' => $taMoney * -1,
                'settlement_balance' => $settleOutInfo['settlement_money'],
                'remark' => isset($data['remark']) ? $data['remark'] : '',
                'com_id' => $data['com_id'],
                'lock_version' => $info[0]['lock_version'] + 1,
            ]);
            if (!$res3) {
                throw  new Exception('修改出账流水失败');
            }
            // 修改原始入账的
            $res4 = $originDiaryInInfo->save([
                'settlement_id' => $settlementIdIn,
                'user_id' => $data['user_id'],
                'income' => $taMoney,
                'expend' => 0,
                'surplus' => $taMoney,
                'settlement_balance' => $settleInInfo['settlement_money'],
                'remark' => isset($data['remark']) ? $data['remark'] : '',
                'com_id' => $data['com_id'],
                'lock_version' => $info[0]['lock_version'] + 1,
            ]);
            if (!$res4) {
                throw  new Exception('修改入账流水失败');
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage() . '/' . $e->getLine());
            Db::rollback();
            return false;
        }
    }

    // 修改期初调整
    public function updateAccountBegin($data)
    {
        Db::startTrans();
        try {
            $info = $this->getOne(['details_id' => $data['details_id'], 'com_id' => $data['com_id']]);
            if (empty($info)) {
                throw new Exception('未找到单据信息');
            }
            // 版本验证
            if ($info['lock_version'] != $data['lock_version']) {
                throw new Exception('系统数据错误，数据已被其他用户更改，请重新获取');
            }
            // 原始账户还原
            $settleModel = new SettlementModel();
            $res1 = $settleModel->where('settlement_id', $info['settlement_id'])
                ->dec('settlement_money', $info['income'])
                ->update();
            if (!$res1) {
                throw new Exception('账户资金回滚失败');
            }
            $settleOutInfo = $settleModel->getOne(['com_id' => $data['com_id'], 'settlement_id' => $data['settlement_id']]);
            if (empty($settleOutInfo)) {
                throw new Exception('账户不存在');
            }
            // 更新账户金额
            $settleOutInfo->save(['settlement_money' => $settleOutInfo['settlement_money'] + $data['income']]);
            // 更新单据信息
            $info->save([
                'user_id' => $data['user_id'],
                'settlement_id' => $data['settlement_id'],
                'income' => $data['income'],
                'surplus' => $data['income'],
                'settlement_balance' => $settleOutInfo['settlement_money'],
                'remark' => $data['remark'],
                'order_date' => $data['order_date'],
                'lock_version' => $data['lock_version'] + 1,
            ]);
            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return false;
        }
    }

    public function delAccountRecordItem($data)
    {
        $info = $this->getOne(['com_id' => $data['com_id'], 'details_id' => $data['details_id']]);
        Db::startTrans();
        try {
            if (!$info) {
                throw new Exception('单据不存在');
            }
            $settlementModel = new SettlementModel();
            switch ($info['item_type']) {
                case '9198':
                    // 恢复资金
                    $settlementModel->where('settlement_id', $info['settlement_id'])
                        ->inc('settlement_money', $info['income'])
                        ->update();
                    $res = $info->delete();
                    if (!$res) {
                        throw new Exception('删除失败');
                    }
                    break;
                case '9199':
                    // 恢复资金
                    $settlementModel->where('settlement_id', $info['settlement_id'])
                        ->dec('settlement_money', $info['expend'])
                        ->update();
                    $res = $info->delete();
                    if (!$res) {
                        throw new Exception('删除失败');
                    }
                    break;
                case '9107':
                    $list = $this->getList(['orders_code'=> $info['orders_code']]);
                    if (count($list) != 2) {
                        throw new Exception('不是正确的互转记录');
                    }

                    // 确定 原来的收入和支出
                    if ($list[0]['income'] > 0) {
                        $origin_money = $list[0]['income'];
                        $origin_settlement_id_in = $list[0]['settlement_id'];
                        $origin_settlement_id_out = $list[1]['settlement_id'];
                    } else {
                        $origin_money = $list[1]['income'];
                        $origin_settlement_id_in = $list[1]['settlement_id'];
                        $origin_settlement_id_out = $list[0]['settlement_id'];
                    }
                    $res1 = $settlementModel->where('settlement_id', $origin_settlement_id_in)
                        ->dec('settlement_money', $origin_money)
                        ->update();
                    if (!$res1) {
                        throw new Exception('账户1资金回滚失败');
                    }
                    $res2 = $settlementModel->where('settlement_id', $origin_settlement_id_out)
                        ->inc('settlement_money', $origin_money)
                        ->update();
                    if (!$res2) {
                        throw new Exception('账户2资金回滚失败');
                    }
                    $res = $this->where('orders_code', $info['orders_code'])->delete();
                    if (!$res) {
                        throw new Exception('删除失败');
                    }
                    break;
                case '9105':
                    // 恢复资金
                    $settlementModel->where('settlement_id', $info['settlement_id'])
                        ->dec('settlement_money', $info['income'])
                        ->update();
                    $res = $info->delete();
                    if (!$res) {
                        throw new Exception('删除失败');
                    }
                    break;
                default :
                    throw new Exception('该单据不允许删除');
                    break;
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            Db::rollback();
            return false;
        }

    }

}