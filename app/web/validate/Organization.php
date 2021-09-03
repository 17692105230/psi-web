<?php


namespace app\web\validate;


use think\Validate;

class Organization extends Validate
{
    protected $rule = [
        'org_id'  => 'number',
        'org_pid' => 'number',
        'org_type'=> 'require|number',
        'org_head'=> 'require|chsDash|length:1,10',
        'org_phone'=> 'number',
        'org_name' => 'require|chsDash|length:1,30',
        'lock_version'  => 'require|number|gt:-1',
    ];
    protected $message = [
        'org_id.number'=>'系统参数错误~~',
        'org_pid.require'=>'上级机构数据错误~~',
        'org_pid.number'=>'上级机构数据错误~~',
        'org_type.require'=>'机构类型数据错误~~',
        'org_type.number'=>'机构类型数据错误~~',
        'org_head.require'=>'机构负责人必须填写~~',
        'org_head.chsDash'=>'机构负责人名称只能是汉字、字母、数字和下划线_及破折号-',
        'org_head.length'=>'名称长度 1-10 字符',
        'org_name.require'=>'名称必须填写',
        'org_name.chsDash'=>'名称只能是汉字、字母、数字和下划线_及破折号-',
        'org_name.length'=>'名称长度1-30字符',
        'lock_version.require'=>'系统参数错误~~',
        'lock_version.number'=>'系统参数错误~~',
        'lock_version.gt'=>'系统参数错误~~',
    ];
    protected $scene = [
        "saveorg"=>["org_id","org_pid", "org_type", "org_head", "org_name", "lock_version"],
    ];

}