<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/26
 * Time: 17:32
 */

namespace app\web\controller;


class CompanyReport extends Controller
{
    public function loadDataGrid(){
        $data = [
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
            ['report_id' => 1,'report_name' => '定个小目标赚1亿','report_type' => '已发布','report_user' => '李四','report_time' =>'2020-12-26'],
        ];
        return Json($data);
    }
}