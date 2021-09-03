<?php


namespace app\web\controller;

use app\web\model\Settlement as SettlementModel;

class Settlement extends Controller
{
    /**
     * @desc  结算账户列表
     * @return \think\response\Json
     * Date: 2021/1/6
     * Time: 15:10
     * @author myy
     */
    public function loadGridData()
    {
        $model = new SettlementModel();
        return json($model->easyPaginate($this->getData()));
    }

    public function loadCombobox()
    {
        $model = new SettlementModel();
        return json($model->getList($this->getData()));
    }
}