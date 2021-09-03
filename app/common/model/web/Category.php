<?php


namespace app\common\model\web;

use app\common\model\BaseModel;
use think\model\concern\SoftDelete;

class Category extends BaseModel
{
    //软删除
   // use SoftDelete;
   // protected $deleteTime = 'delete_time';
   // protected $defaultSoftDelete = 0;

    protected $pk = 'category_id';
    protected $table = 'hr_category';

}