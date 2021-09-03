<?php


namespace app\web\logic;


use app\web\model\PurchaseOrdersDetails;
use app\web\model\PurchasePlanDetails;
use app\web\model\PurchaseRejectDetails;
use app\web\model\SaleOrdersDetails;

class SystemLogic extends BaseLogic
{

    public function exist($where, $field = '*')
    {
        $poInfo = (new PurchaseOrdersDetails)->getOne($where, $field);
        if ($poInfo) {
            return true;
        }
        $ppInfo = (new PurchasePlanDetails)->getOne($where, $field);
        if ($ppInfo) {
            return true;
        }
        $prInfo = (new PurchaseRejectDetails)->getOne($where, $field);
        if ($prInfo) {
            return true;
        }
        $soInfo = (new SaleOrdersDetails)->getOne($where, $field);
        if ($soInfo) {
            return true;
        }
        return false;
    }
}