<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/2/1
 * Time: 14:47
 */

namespace app\web\controller;

use app\Request;
use app\web\model\SaleRejectOrders as SaleRejectOrdersModel;
use app\web\validate\SaleRejectOrders as SaleRejectOrdersValidate;

class SaleRejectOrder extends Controller
{
    /**
     * Notes:加载销售退货单
     * User:ccl
     * DateTime:2021/2/1 14:48
     * @param Request $request
     * @return array|\think\response\Json
     */
    public function loadRejectOrdersList(Request $request)
    {
        $page = $request->post('page', 1);
        $rows = $request->post('rows', 30);
        $where = $request->post();
        $sale_orders = new SaleRejectOrdersModel();
        $res = $sale_orders->loadData($this->getData($where), $page, $rows);
        if (!$res) {
            return $this->renderError($sale_orders->getError('查询失败'));
        }
        return json($res);
    }

    /**
     * Notes:获取退货单详情
     * User:ccl
     * DateTime:2021/2/1 16:06
     * @param Request $request
     * @return array
     */
    public function loadOrderDetails(Request $request)
    {
        $orders_code = $request->post();
        $sale_reject_order = new SaleRejectOrdersModel();
        $res = $sale_reject_order->getRejectOrderDetail($this->getData($orders_code));
        if (!$res) {
            return $this->renderError($sale_reject_order->getError('查询失败'));
        }
        return $this->renderSuccess($res);
    }

    /**
     * Notes:保存销售退货单
     * User:ccl
     * DateTime:2021/2/1 18:00
     * @param Request $request
     * @return array
     */
    public function saveRejectOrdersRoughDraf(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('add')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $sale_reject_orders = new SaleRejectOrdersModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 0;
        $res = $sale_reject_orders->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_orders->getError());
        }
        $orders = $sale_reject_orders->getRejectOrderDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * Notes:删除销售退货单
     * User:ccl
     * DateTime:2021/2/3 9:08
     * @param Request $request
     * @return array
     */
    public function delRejectOrder(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('del')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $sale_reject_orders = new SaleRejectOrdersModel();
        $res = $sale_reject_orders->deleteRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_orders->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:提交销售单为正式
     * User:ccl
     * DateTime:2021/2/3 9:35
     * @param Request $request
     * @return array
     */
    public function saveRejectOrdersFormally(Request $request)
    {
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if (!$saleVilidate->scene('add')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $sale_reject_orders = new SaleRejectOrdersModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 9;
        $res = $sale_reject_orders->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_orders->getError());
        }
        $orders = $sale_reject_orders->getRejectOrderDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * Notes:撤销销售退货单
     * User:ccl
     * DateTime:2021/2/3 16:40
     * @param Request $request
     * @return array
     */
    public function saveRevokeRejectOrders(Request $request){
        $data = $request->post();
        $saleVilidate = new SaleRejectOrdersValidate();
        if(!$saleVilidate->scene('repeal')->check($data)){
            return $this->renderError($saleVilidate->getError());
        }

        $saleOrders = new SaleRejectOrdersModel();
        //获取销售单详情
        $where['orders_code'] = $data['orders_code'];
        $where['com_id'] = $this->com_id;
        $detail_info = $saleOrders->getRejectOrderDetail($where);
        if(!$detail_info){
            return $this->renderError('(撤销单据)查询销售退货单详情失败');
        }
        $res = $saleOrders->revokeSaleRejectOrders($detail_info,$data['lock_version']);
        if(!$res){
            return $this->renderError($saleOrders->getError());
        }
        $order_info = $saleOrders->getRejectOrderDetail($where);
        return $this->renderSuccess(['orders'=> $order_info],'撤销销售单成功');
    }

}