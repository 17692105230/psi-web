<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/9
 * Time: 18:15
 */

namespace app\web\validate;


use think\Validate;

class Store extends  Validate
{
    protected $rule = [
        'warehouse_id' => 'number|gt:-1',
        'category_id' => 'number|gt:-1',
        'bland_id' => 'number|gt:-1',
        'goods_year' => 'number|gt:-1',
        'goods_season' => 'number|gt:-1',
        'goods_warn' => 'number|gt:-1',
        'goods_no' => 'number|gt:-1',
        'details_id'=>'require|number',
        'tag'=>'require|number',

    ];

    protected $message = [

        'warehouse_id.number' => '系统参数错误~~',
        'category_id.number' => '系统参数错误~~',
        'bland_id.number' => '系统参数错误~~',
        'goods_year.number' => '系统参数错误~~',
        'goods_season.number' => '系统参数错误~~',
        'goods_warn.number' => '系统参数错误~~',
        'goods_no.number' => '系统参数错误~~',
        "details_id.require"=>"商品ID错误~~",
        "details_id.number"=>"商品ID错误~~",
        'tag.require'=>'参数错误~~',
        'tag.number'=>'参数错误~~',
    ];

    protected $scene = [
        'search' =>['warehouse_id','category_id','bland_id','goods_year','goods_season','goods_warn','goods_no'],
        'saveinventoryrow'=>['warehouse_id'],
        'savegoodsnumber'=>["details_id"],
        'savegoodscolorsize'=>["details_id","tag"],
    ];
}