<?php


namespace app\web\validate;
use think\Validate;

class Category extends Validate
{

    protected $rule = [
        'lock_version' => 'require',
        'category_id' => 'require',
        'category_pid' => 'require',
        'category_name' => 'require',
        'sort' => 'require',

    ];

    protected $message = [
        'lock_version.require' => '版本信息不能为空',
        'category_id.require' => 'id不能为空',
        'category_name.require' => '分类名称不能为空',
    ];

    protected $scene = [
        'add' => ['category_pid','category_name', 'sort'],
        'edit' => ['category_id','lock_version'],
        'delete' => ['category_id','lock_version'],
    ];
}