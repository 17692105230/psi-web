<?php


namespace app\web\model;

use app\common\model\web\TransferOrders as TransferOrdersModel;

class TransferOrders extends TransferOrdersModel
{

    public function getDetail($where)
    {
        return $this->with('details')->where($where)->find();
    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'out_warehouse_id' => $data['out_warehouse_id'],
            'in_warehouse_id' => $data['in_warehouse_id'],
            'goods_number' => $data['goods_number'],
            'orders_date' => $data['orders_date'],
            'user_id' => $data['user_id'],
            'orders_status' => isset($data['orders_status']) ? $data['orders_status'] : 0,
            'orders_remark' => $data['orders_remark'],
            'com_id' => $data['com_id'],
            'shop_id' => isset($data['shop_id']) ? $data['shop_id'] : 0,
        ]);
    }

    public function loadList($data)
    {
        $dbQuery = $this->alias('o')
            ->join('hr_organization out', 'o.out_warehouse_id=out.org_id', 'left')
            ->join('hr_organization in', 'o.in_warehouse_id=in.org_id', 'left')
            ->where('o.com_id', $data['com_id'])
            ->when(isset($data['status']) && $data['status'] != -1, function ($query) use ($data) {
                $query->where('o.orders_status', $data['status']);
            })->when(isset($data['orders_code']) && $data['orders_code'], function ($query) use ($data) {
                $query->where('o.orders_code', 'like', '%' . $data['orders_code'] . '%');
            })->when(isset($data['out_warehouse_id']), function ($query) use ($data) {
                $query->where('o.out_warehouse_id', $data['out_warehouse_id']);
            })->when(isset($data['in_warehouse_id']), function ($query) use ($data) {
                $query->where('o.in_warehouse_id', $data['in_warehouse_id']);
            })->when(isset($data['search_date_begin']), function ($query) use ($data) {
                $query->where('o.create_time', '>', strtotime($data['search_date_begin']));
            })->when(isset($data['search_date_end']), function ($query) use ($data) {
                $query->where('o.create_time', '<=', strtotime($data['search_date_end'] . ' +1 day'));
            });
        $total = $dbQuery->count();
        list($offset, $limit) = pageOffset($data);
        $rows = $dbQuery->limit($offset, $limit)->field('o.*,out.org_name as out_warehouse_name,in.org_name as in_warehouse_name')->select();
        return compact('total', 'rows');
    }

    public function delOrders($com_id, $orders_code)
    {
        $info = $this->getOne(['com_id' => $com_id, 'orders_code' => $orders_code], 'orders_code');
        if (!$info) {
            $this->setError('订单不存在');
            return false;
        }
        $this->where('orders_code', $orders_code)->delete();
        (new TransferOrdersDetails())->where('orders_code', $orders_code)->delete();
        return true;
    }
}