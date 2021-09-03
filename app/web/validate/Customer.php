<?php


namespace app\web\validate;


use think\Validate;

class Customer extends Validate
{
    protected $rule = [
        "classify_name"=>"require",
        "classify_price"=>"require",
        "sort"=>"require|number",
        "classify_id"=>"require|min:1",
        "lock_version"=>"require|min:0",
        "dict_type"=>"require",
    ];
    protected $message = [
        "classify_name.require"=>"缺少客户分类名称",
        "classify_price.require"=>"缺少客户分类价格",
        "classify_id.require"=>"参数错误",
        "classify_id.min"=>"参数错误",
        "lock_version.require"=>"参数错误",
        "lock_version.min"=>"参数错误",
        "dict_type.require"=>"参数错误",
    ];
    protected $scene = [
        "add"=>["classify_name","classify_price","sort"],
        "del"=>["classify_id", "lock_version"],
        "edit"=>["custom_id"],
        "loadlist"=>["dict_type"],
    ];
}