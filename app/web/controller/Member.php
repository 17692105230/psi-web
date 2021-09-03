<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/25
 * Time: 14:32
 */

namespace app\web\controller;


use think\response\Json;

class Member extends Controller
{
     public function loadGridData(){
         $data = [
           'total' => 30,
           'rows' =>[
               ['member_id' => 1,'member_code' => 007,'member_name' => '张三','member_iphone' => 1785656565656,'member_gender' => '男','receive_clerk_name' => '男','member_category_name' => '白银','member_birthday'=>'2020-12-21'],
           ]
         ];
         return Json($data);
     }
}