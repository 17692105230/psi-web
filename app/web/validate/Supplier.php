<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/6
 * Time: 11:20
 */

namespace app\web\validate;


use think\Validate;

class Supplier extends Validate
{
    protected $rule = [
        'lock_version' => 'number|gt:-1',
        'supplier_director' => 'require',
        'supplier_discount' => 'require|number',
        'supplier_email' => 'email',
        'supplier_mphone' => 'number|max:11',
        'supplier_name' => 'require',
        'supplier_phone' => 'number|max:11',
        'supplier_sort' => 'number',
        'supplier_status' => 'number',
        'supplier_id' => 'require|number'
    ];

    protected $message = [

        'lock_version.number' => '系统参数错误~~',
        'lock_version.gt' => '系统参数错误~~'
    ];

    protected $scene = [
        'add' => ['supplier_director', 'supplier_discount'
            , 'supplier_mphone', 'supplier_name', 'supplier_phone','supplier_status'],
        'del' =>['supplier_id', 'lock_version']

    ];

}