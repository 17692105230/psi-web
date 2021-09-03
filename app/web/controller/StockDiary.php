<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/11
 * Time: 11:50
 */

namespace app\web\controller;
use app\Request;
use app\web\model\StockDiary as StockDiaryModel;
use app\web\model\Goods as GoodsModel;
class StockDiary extends Controller
{
    public function loadStockDiaryData(){
        $stock_diary = new StockDiaryModel();
        $data = $stock_diary->getDiary();
        if(!$data){
            return $this->renderError('获取流水失败');
        }
        return json($data);

    }

    public function getGoodsColorData(Request $request){
        $goods_code = $request->post('goods_code');
        $goodsModel = new GoodsModel();
        $result = $goodsModel->getDetail($this->getData(['goods_code' => $goods_code]))->toArray();
        $list = $result["detail"];
        $colorList = [];
        foreach ($list as $item) {
            if (!isset($colorList[$item['color_id']])) {
                $colorList[$item['color_id']] = $item['color_name'];
            }
        }
        $data = [];
        $i = 0;
        foreach ($colorList as $key=>$value){
            $data[$i]['color_id'] = $key;
            $data[$i]['color_name'] = $value;
            $i++;
        }
        return json($data);
    }

    public function getGoodsSizeData(Request $request){
        $goods_code = $request->post('goods_code');
        $goodsModel = new GoodsModel();
        $result = $goodsModel->getDetail($this->getData(['goods_code' => $goods_code]))->toArray();
        $list = $result["detail"];
        $colorList = [];
        foreach ($list as $item) {
            if (!isset($colorList[$item['size_id']])) {
                $colorList[$item['size_id']] = $item['size_name'];
            }
        }
        $data = [];
        $i = 0;
        foreach ($colorList as $key=>$value){
            $data[$i]['size_id'] = $key;
            $data[$i]['size_name'] = $value;
            $i++;

        }
        return json($data);
    }

    public function queryRecordGoods(Request $request){
        $data = $request->get();


    }
}