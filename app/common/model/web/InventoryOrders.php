<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class InventoryOrders extends BaseModel
{
    protected $table = 'hr_inventory_orders';
    protected $pk = 'orders_id';
}