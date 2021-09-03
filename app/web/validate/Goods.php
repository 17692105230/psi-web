<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/4
 * Time: 18:01
 */

namespace app\web\validate;


use think\Validate;

class Goods extends Validate
{
    protected $rule = [
        'goods_id'  => 'number',
        'goods_name' => 'require|length:1,50',
        'goods_code' => 'require',
        'goods_pprice' => 'require|gt:0',
        'goods_wprice' => 'require|gt:0',
        'goods_srprice' => 'require',
        'goods_rprice' => 'require|gt:0',
        'goods_bnumber' => 'number',
        'lock_version'  => 'number|gt:-1',
        'page'  => 'number',
        'rows'  => 'number',
    ];

    protected $message = [
        'goods_id.number'=>'系统参数错误~~',
        'goods_name.require'=>'名称必须填写',
        'goods_pprice.require'=>'采购价必须填写',
        'goods_pprice.number'=>'采购价必须为数字~~',
        'goods_pprice.gt'=>'采购价必须大于 0~~',
        'goods_wprice.require'=>'批发价必须填写',
        'goods_wprice.number'=>'批发价必须为数字~~',
        'goods_wprice.gt'=>'批发价必须大于 0~~',
        'goods_srprice.number'=>'建议零售价必须为数字~~',
        'goods_rprice.require'=>'零售价必须填写',
        'goods_rprice.number'=>'零售价必须为数字~~',
        'goods_rprice.gt'=>'零售价必须大于 0~~',
        'lock_version.number'=>'系统参数错误~~',
        'lock_version.gt'=>'系统参数错误~~',
        'page.number'=>'系统参数错误~~',
        'rows.number'=>'系统参数错误~~'
    ];

    protected $scene = [
        'add' => ['goods_id','goods_name', 'goods_code','goods_pprice','goods_wprice',
            'goods_srprice','goods_rprice','goods_bnumber','lock_version'],
        'search_goods'=>["page", "rows"],
    ];
}