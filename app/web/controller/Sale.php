<?php


namespace app\web\controller;

use app\common\utils\BuildCode;
use app\Request;
use app\web\model\SalePlan as SalePlanModel;
use app\web\model\SalePlan;
use app\web\validate\Sale as SaleVilidate;

class Sale extends Controller
{
    /*----销售订单页面-------*/
    /**
     * Notes:销售订单
     * User:ccl
     * DateTime:2021/1/18 17:03
     * @param Request $request
     * @return array|\think\response\Json
     */
    public function loadPlanList(Request $request)
    {
        $page = $request->post('page', 1);
        $rows = $request->post('rows', 20);
        $where = $request->post();
        $sale_plan = new SalePlanModel();
        $res = $sale_plan->loadData($this->getData($where), $page, $rows);
        if (!$res) {
            return $this->renderError($sale_plan->getError('查询失败'));
        }
        return json($res);

    }

    /**
     * Notes:销售订单详情
     * User:ccl
     * DateTime:2021/1/18 17:04
     * @param Request $request
     * @return array
     */
    public function loadPlanDetails(Request $request)
    {
        $orders_code = $request->post();
        $sale_plane = new SalePlanModel();
        $res = $sale_plane->getPlanDetails($this->getData($orders_code));
        if (!$res) {
            return $this->renderError($sale_plane->getError('查询失败'));
        }
        return $this->renderSuccess($res);
    }

    /**
     * Notes:ccl
     * User:保存销售订单草稿
     * DateTime:2021/2/4 10:32
     * @param Request $request
     * @return array
     */
    public function savePlanRoughDraft(Request $request)
    {
        $data = $request->post();
        $saleValidate = new SaleVilidate();
        if (!$saleValidate->scene('update')->check($data)) {
            return $this->renderError($saleValidate->getError());
        }
        $salePlane = new SalePlanModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 0;
        $res = $salePlane->updatePlane($this->getData($data));
        if (!$res) {
            return $this->renderError($salePlane->getError());
        }
        $orders = $salePlane->getPlanDetails($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * Notes:提交销售订单
     * User:ccl
     * DateTime:2021/2/4 10:32
     * @param Request $request
     * @return array
     */
    public function savePlanFormally(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleVilidate();
        if (!$saleVilidate->scene('update')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $salePlane = new SalePlanModel();
        $data['orders_status'] = 9;
        $data['user_id'] = $this->user['user_id'];
        $res = $salePlane->updatePlane($this->getData($data));
        if (!$res) {
            return $this->renderError($salePlane->getError('保存失败'));
        }
        $orders = $salePlane->getPlanDetails($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * Notes:删除订单
     * User:ccl
     * DateTime:2021/2/4 10:32
     * @return array
     */
    public function delPlan()
    {
        $data = $this->request->post();
        $saleVilidate = new SaleVilidate();
        if (!$saleVilidate->scene('delete')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $salePlane = new SalePlanModel();
        $res = $salePlane->delSalePlan($this->getData($data));
        if (!$res) {
            return $this->renderError($salePlane->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:页面加载时提前生成订单号
     * User:ccl
     * DateTime:2021/2/4 10:34
     */
    public function loadOrdersCode(){
        $sale_plan = new SalePlanModel();
        $where = [
            'orders_status' => 7,
            'com_id' => $this->com_id
        ];
        $orders_data = $sale_plan->getOne($where,'orders_code,orders_status');
        if($orders_data){
            return json($orders_data);
        }else{
            $orders_code = BuildCode::dateCode('SP');
            $data = [
                'orders_code' => $orders_code,
                'com_id' => $this->com_id,
                'orders_status' => 7
            ];
            $res = $sale_plan->save($data);
            if(!$res){
                return $this->renderError('临时单据创建失败');
            }
            return json($data);
        }
    }


}