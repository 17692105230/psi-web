<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/22
 * Time: 15:47
 */

namespace app\web\validate;


class SaleOrders extends BaseValidate
{
    protected $rule = [
        'client_id' => 'require|number',
        'salesman_id' => 'require|number',
        'orders_rmoney' => 'require',
        'warehouse_id' => 'require|number',
        'settlement_id' => 'require|number',
        'delivery_id' => 'require|number',
        'other_money' => 'require',
        'erase_money' => 'require',
        'goods_number' => 'require|number',
        'orders_pmoney' => 'require',
        'lock_version' => 'require|number',
        'orders_code' => 'require'
    ];
    protected $message = [

    ];
    protected $scene = [
        'update' => ['client_id','salesman_id','orders_rmoney','warehouse_id','settlement_id','delivery_id',
            'orders_remark','other_money','erase_money','goods_number','orders_pmoney','lock_version'],
        'delete' => ['orders_code'],
        'repeal' => ['lock_version','orders_code'],
    ];
}