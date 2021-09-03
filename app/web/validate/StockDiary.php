<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/12
 * Time: 9:24
 */

namespace app\web\validate;
use think\Validate;

class StockDiary extends Validate
{
    protected $rule = [
        'warehouse_id' => 'number',
        'search_keyword' => 'alphaNum',
        'color_id' => 'number',
        'size_id' => 'number',
        'begin_date' => 'alphaNum',
        'end_date' => 'alphaNum'
    ];

    protected $message = [
        'warehouse_id'
    ];

    protected $scene = [

    ];
}