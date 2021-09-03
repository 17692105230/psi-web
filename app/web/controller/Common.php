<?php


namespace app\web\controller;


use app\web\model\GoodsDetails;

class Common extends Controller
{
    public function getGoodsSizeData(){
        $gdModel = new GoodsDetails();
        $res = $gdModel->getSizeDetail($this->getData($this->request->post()));
        return json($res);
    }

    public function getGoodsColorData() {
        $gdModel = new GoodsDetails();
        $res = $gdModel->getColorDetail($this->getData($this->request->post()));
        return json($res);
    }

}