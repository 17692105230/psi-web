<?php

/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/11
 * Time: 11:45
 */


namespace app\common\model\web;


use app\common\model\BaseModel;

class StockDiary extends BaseModel
{
    protected $table = 'hr_stock_diary';
    protected $pk = 'details_id';
}