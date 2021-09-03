<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/7
 * Time: 15:38
 */

namespace app\web\validate;


use think\Validate;

class PurchaseRejectOrder extends Validate
{
    protected $rule = [
        'orders_code' => 'require',
        'supplier_id' => 'require',
        'settlement_id' => 'require',
        'data_update' => 'require',
        'data_insert' => 'require',
        'data_delete' => 'require',
        'return_orders_date' => 'require',
        'lock_version' => 'require',
    ];

    protected $message = [
        'orders_code.require' => '采购单号不能为空',
        'supplier_id.require' => '供应商不能为空',
        'settlement_id.require' => '结算账户不能为空',
        'data_update.require' => '修改订单修改内容不能为空',
        'data_insert.require' => '修改订单添加内容不能为空',
        'data_delete.require' => '修改订单删除内容不能为空',
        'return_orders_date.require' => '订单日期不能为空',
        'lock_version.require' => '版本信息不能为空',
    ];

    protected $scene = [
        'detail' => ['orders_code'],
        'delete' => ['orders_code'],
        'update' => ['data_update','data_insert', 'data_delete', 'orders_date','lock_version'],
    ];
}