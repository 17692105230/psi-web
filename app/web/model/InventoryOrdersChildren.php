<?php


namespace app\web\model;

use app\common\model\web\InventoryOrdersChildren as InventoryOrdersChildrenModel;

class InventoryOrdersChildren extends InventoryOrdersChildrenModel
{
    //查询列表
    public function getOrdersChildrenList($where, $field = "*")
    {
        return $this->field($field)->where($where)->select();
    }
    //查询在用值--children_code
    public function getFieldValue($where, $value_field="children_code")
    {
        return $this->where($where)->value($value_field);
    }

}