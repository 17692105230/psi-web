<?php


namespace app\web\model;

use app\common\model\web\InventoryOrders as InventoryOrdersModel;

class InventoryOrders extends InventoryOrdersModel
{

    public function loadList($data)
    {
        $dbQuery = $this->alias('o')
            ->leftJoin('hr_organization w', 'w.org_id=o.warehouse_id')
            ->leftJoin("hr_user u", "u.user_id=o.user_id")
            ->where('o.com_id', $data["com_id"])
            ->when(isset($data['search_orders_code']) && $data['search_orders_code'], function ($query) use ($data) {
                $query->where('o.orders_code', 'like', '%' . $data['search_orders_code'] . '%');
            })->when(isset($data['search_warehouse_name']) && $data['search_warehouse_name'], function ($query) use ($data) {
                $query->where('o.warehouse_id', $data['search_warehouse_name']);
            })->when(isset($data['search_date_begin']) && $data['search_date_begin'], function ($query) use ($data) {
                $query->where('o.create_time', '>', strtotime($data['search_date_begin']));
            })->when(isset($data['search_date_end']) && $data['search_date_end'], function ($query) use ($data) {
                $query->where('o.create_time', '<=', strtotime($data['search_date_end'] . ' +1 day'));
            })->when(isset($data['status']) && $data['status'] != -1, function ($query) use ($data) {
                $query->where('o.orders_status', $data['status']);
            });
        $total = $dbQuery->count();
        list($offset, $limit) = pageOffset($data);
        $rows = $dbQuery->limit($offset, $limit)->field('o.*,w.org_name warehouse_name, u.user_name')->select();
        return compact('total', 'rows');
    }

    //查询信息
    public function getInventoryOrder($where)
    {
        return $this->alias("io")
            ->where("io.com_id", $where["com_id"])
            ->where("io.orders_code", $where["orders_code"])
            ->leftJoin('hr_organization org', "io.warehouse_id=org.org_id")
            ->leftJoin('hr_user u', "io.user_id=u.user_id")
            ->leftJoin("hr_inventory_orders_details_c dc", "io.orders_id=dc.orders_id")
            ->field("io.*, org.org_name warehouse_name, u.user_name, IFNULL(dc.goods_lmoney, 0.00) goods_plmoney")
            ->find();
    }
    //获取指定字段值
    public function getFielValue($where, $field)
    {
        return $this->where($where)->value($field);
    }
}