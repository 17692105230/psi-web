<?php


namespace app\common\model\web;

use think\model\concern\SoftDelete;

use app\common\model\BaseModel;

class Dict extends BaseModel
{
   // use SoftDelete;
   // protected $deleteTime = 'delete_time';
   // protected $defaultSoftDelete = 0;

    protected $table = 'hr_dict';
}