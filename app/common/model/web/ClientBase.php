<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/16
 * Time: 17:39
 */

namespace app\common\model\web;


use app\common\model\BaseModel;

class ClientBase extends BaseModel
{
    protected $table = 'hr_client_base';
    protected $pk = 'client_id';
}