<?php


namespace app\web\validate;


class PurchaseOrders extends BaseValidate
{
    protected $rule = [
        'orders_code' => 'require',
        'supplier_id' => 'require',
        'settlement_id' => 'require',
        'data_update' => 'require',
        'data_insert' => 'require',
        'data_delete' => 'require',
        'orders_date' => 'require',
        'lock_version' => 'require',
        'goods_code|商品货号' => 'require',
        'warehouse_id|仓库编号' => 'require',
        'orders_type|订单类型' => 'require',
    ];

    protected $message = [
        'orders_code.require' => '采购单号不能为空',
        'supplier_id.require' => '供应商不能为空',
        'settlement_id.require' => '结算账户不能为空',
        'data_update.require' => '修改订单修改内容不能为空',
        'data_insert.require' => '修改订单添加内容不能为空',
        'data_delete.require' => '修改订单删除内容不能为空',
        'orders_date.require' => '订单日期不能为空',
        'lock_version.require' => '版本信息不能为空',

    ];

    protected $scene = [
        'detail' => ['orders_code'],
        'delete' => ['orders_code'],
        'update' => ['data_update','data_insert', 'data_delete', 'orders_date','lock_version'],
        'build' => ['goods_code', 'warehouse_id', 'orders_type'],
    ];


}