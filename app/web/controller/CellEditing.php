<?php

namespace app\web\controller;

use app\web\model\Size as SizeModel;
use app\web\model\GoodsDetails as GoodsDetailsModel;

/**
 * 单元格编辑
 * Class CellEditing
 * @package app\web\controller
 */
class CellEditing extends Controller
{
    public function LoadApplyList()
    {
        return json(json_decode('{"total":2,"rows":[{"orders_id":2,"orders_code":"SP180401093802243","orders_date":1525449600,"client_id":3,"client_name":"张志超","orders_status":9},{"orders_id":1,"orders_code":"SP180309234651672","orders_date":0,"client_id":2,"client_name":"小蜜","orders_status":9}],"status":null}'));
    }

    public function loadInventoryDetails()
    {
        return json(json_decode('{"errcode":0,"errmsg":"查询成功","rows":[{"details_id":622,"orders_code":"SI180726214952254","goods_code":"321","color_id":4,"size_id":8,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"黑色","size_name":"3XL"},{"details_id":621,"orders_code":"SI180726214952254","goods_code":"321","color_id":4,"size_id":8,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"黑色","size_name":"3XL"},{"details_id":620,"orders_code":"SI180726214952254","goods_code":"321","color_id":8,"size_id":6,"goods_number":1,"children_code":13,"user_id":1,"goods_anumber":0,"goods_lnumber":1,"goods_status":0,"create_time":1597655021,"update_time":1597655021,"lock_version":0,"goods_name":"裙子123","goods_barcode":"","color_name":"深灰色","size_name":"XL"}],"total":3}'));
    }

    public function getGoodsColorData()
    {
        $goods_code = $this->request->get('goods_code');
        $model = new GoodsDetailsModel();
        $color_list = [];
        $data = $model->getColorDetail($this->getData(['goods_code' => $goods_code]));
        // 去重
        foreach ($data as $item) {
            if (!isset($color_list[$item['color_id']])) {
                $color_list[$item['color_id']] = $item;
            }
        }
        return json(array_values($color_list));
    }

    public function getGoodsSizeData()
    {
        $goods_code = $this->request->get('goods_code');
        $model = new GoodsDetailsModel();
        $size_list = [];
        $data = $model->getSizeDetail($this->getData(['goods_code' => $goods_code]));
        // 去重
        foreach ($data as $item) {
            if (!isset($size_list[$item['size_id']])) {
                $size_list[$item['size_id']] = $item;
            }
        }
        return json(array_values($size_list));
    }

    public function loadModuleRows()
    {
        return json(json_decode(''));
    }
}