<?php


namespace app\web\validate;


class TransferOrdersValidate extends BaseValidate
{
    protected $rule = [
        'out_warehouse_id' => 'require|number',
        'in_warehouse_id' => 'require|number',
        'data_insert' => 'require',
        'data_update' => 'require',
        'data_delete' => 'require',
    ];

    protected $scene = [
        'save_orders' => ['out_warehouse_id', 'in_warehouse_id', 'data_insert', 'data_update', 'data_delete'],
    ];
}