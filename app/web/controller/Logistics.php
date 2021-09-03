<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/26
 * Time: 14:08
 */

namespace app\web\controller;


use think\response\Json;

class logistics extends Controller
{
    public function loadGridData(){
        $data = [
            'total' => 30,
            'rows'  =>[
                    ['logistics_id' => '1','logistics_name' => '顺丰速递','logistics_type' => '普通快递单','logistics_company' => '','logistics_Url' => 'http://www.sf-express.com'],
                    ['logistics_id' => '2','logistics_name' => '顺丰速递','logistics_type' => '特快递单','logistics_company' => '','logistics_Url' => 'http://www.sf-express.com'],
                    ['logistics_id' => '3','logistics_name' => '顺丰速递','logistics_type' => '普通快递单','logistics_company' => '','logistics_Url' => 'http://www.sf-express.com'],
                    ['logistics_id' => '4','logistics_name' => '顺丰速递','logistics_type' => '普通快递单','logistics_company' => '','logistics_Url' => 'http://www.sf-express.com'],
            ]
        ];
        return Json($data);
    }

    /**
     * Notes:快递单类型
     * User:ccl
     * DateTime:2020/12/26 15:34
     * @return Json
     */
    public function loadType(){
        $data = [
            ['id' => 1 , 'name' => '普通快递单'],
            ['id' => 1 , 'name' => '菜鸟电子面单']
        ];
        return Json($data);
    }

    /**
     * Notes:快递公司
     * User:ccl
     * DateTime:2020/12/26 15:40
     * @return Json
     */
    public function loadCompany(){
        $data = [
            ['id' => 1 , 'name' => '中通速递'],
            ['id' => 1 , 'name' => '顺丰快递'],
            ['id' => 1 , 'name' => '韵达快递'],
            ['id' => 1 , 'name' => '申通快递'],
            ['id' => 1 , 'name' => '圆通快递'],
            ['id' => 1 , 'name' => '百世快递'],
            ['id' => 1 , 'name' => 'EMS'],
            ['id' => 1 , 'name' => '邮政平邮/小包'],
            ['id' => 1 , 'name' => '百世汇通'],
            ['id' => 1 , 'name' => '天天快递'],
            ['id' => 1 , 'name' => '京东快递'],
            ['id' => 1 , 'name' => '德邦'],
            ['id' => 1 , 'name' => '国通快递']
        ];
        return Json($data);
    }

    /**
     * Notes:快递单模板
     * User:ccl
     * DateTime:2020/12/26 15:40
     * @return Json
     */
    public function loadModel(){
        $data = [
            ['id' => 1 , 'name' => '中通'],
            ['id' => 1 , 'name' => '顺丰'],
            ['id' => 1 , 'name' => '申通'],
            ['id' => 1 , 'name' => '圆通'],
            ['id' => 1 , 'name' => 'EMS'],
            ['id' => 1 , 'name' => '宅急送']
        ];
        return Json($data);
    }
}