<?php


namespace app\web\validate;


use think\Validate;

class Color extends Validate
{
    protected $rule = [
        'lock_version' => 'require',
        'color_group' => 'require',
        'color_name' => 'require',
        'sort' => 'require',
        'color_id' => 'require',
    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'color_group.require' => '色组不能为空',
        'color_name.require' => '颜色名称不能为空',
        'sort.require' => '排序不能为空',
        'color_id' => 'id不能为空'
    ];

    protected $scene = [
        'add' => ['color_group','color_name','sort'],
        'edit' => ['color_id','lock_version','color_group'],
        'del' => ['color_id','lock_version'],
    ];
}