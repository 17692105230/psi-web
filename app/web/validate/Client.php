<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/18
 * Time: 10:35
 */

namespace app\web\validate;


class Client extends BaseValidate
{
    protected $rule = [
        "client_id"=>'number',
        'client_name' => 'require|chsDash|length:1,20',
        'client_category_id'=>'require|number',
        'client_discount'=>'number',
        'client_status'=>'number',
        'client_phone'=>'length:6,11',
        'client_email'=>'email',
        'lock_version'  => 'number|gt:-1',
    ];
    protected $message = [
        'client_id.number'=>'系统参数错误~~',
        'client_name.require'=>'客户名称必须填写',
        'client_name.chsDash'=>'客户名称只能是汉字、字母、数字和下划线_及破折号-',
        'client_name.length'=>'名称长度1-20字符',
        'client_category_id.require'=>'客户类别必须选择',
        'client_category_id.number'=>'客户类别必须选择',
        'client_discount.number'=>'默认折扣必须为数字',
        'client_status.number'=>'客户状态错误',
        'client_phone.length'=>'客户电话长度6-11位字符',
        'client_email.email'=>'邮箱错误',
        'lock_version.number'=>'系统参数错误~~',
        'lock_version.gt'=>'系统参数错误~~',
    ];
    protected $scene = [
        "saveclient"=>["client_id", "client_name", "client_category_id", "client_discount", "client_status", "client_phone", "client_email", "lock_version"],
        "loaddata"=>["client_id"],
    ];
}