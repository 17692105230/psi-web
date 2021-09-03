<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class InventoryOrdersChildren extends BaseModel
{
    protected $table="hr_inventory_orders_children";
    protected $pk = "details_id";
}