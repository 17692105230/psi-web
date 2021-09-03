<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/27
 * Time: 9:32
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class DiaryClient extends BaseModel
{
    protected $table = 'hr_diary_client';
    protected $pk = 'details_id';
}