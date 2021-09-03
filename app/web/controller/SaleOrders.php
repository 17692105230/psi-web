<?php


namespace app\web\controller;
use app\Request;
use app\common\utils\BuildCode;
use app\web\model\SaleOrders as SaleOrdersModel;
use app\web\validate\SaleOrders as SaleOrdersValidate;
use app\web\model\SaleOrdersDetails as SaleOrdersDetailsModel;
//销售单
class SaleOrders extends Controller
{
    /**
     * Notes:加载销售单信息
     * User:ccl
     * DateTime:2021/1/22 15:40
     * @param Request $request
     * @return array|\think\response\Json
     */
    public function loadOrdersList(Request $request){
        $page = $request->post('page',1);
        $rows = $request->post('rows',30);
        $where = $request->post();
        $sale_orders =new SaleOrdersModel();
        $res = $sale_orders->loadData($this->getData($where),$page,$rows);
        if(!$res){
            return $this->renderError($sale_orders->getError('查询失败'));
        }
        return json($res);
    }

    /**
     * Notes:获取销售单详情
     * User:ccl
     * DateTime:2021/1/22 15:40
     * @param Request $request
     * @return array
     */
    public function loadOrderDetails(Request $request){
        $orders_code = $request->post();
        $sale_order = new SaleOrdersModel();
        $res = $sale_order->getOrdersDetails($this->getData($orders_code));
        if(!$res){
            return $this->renderError($sale_order->getError('查询失败'));
        }
        return $this->renderSuccess($res);
    }

    /**
     * Notes:保存为销售单草稿
     * User:ccl
     * DateTime:2021/1/22 15:45
     * @param Request $request
     * @return array
     */
    public function saveOrdersRoughDraf(Request $request){
        $data = $request->post();
        $saleVilidate = new SaleOrdersValidate();
        if(!$saleVilidate->scene('update')->check($data)){
            return $this->renderError($saleVilidate->getError());
        }
        $saleOrders = new SaleOrdersModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 0;
        $res = $saleOrders->updatePlane($this->getData($data));
        if(!$res){
            return $this->renderError($saleOrders->getError());
        }
        $orders = $saleOrders->getOrdersDetails($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders],'保存成功');
    }

    /**
     * Notes:删除销售单
     * User:ccl
     * DateTime:2021/1/26 14:12
     * @return array
     */
    public function delOrder(){
        $data = $this->request->post();
        $saleVilidate = new SaleOrdersValidate();
        if (!$saleVilidate->scene('delete')->check($data)) {
            return $this->renderError($saleVilidate->getError());
        }
        $saleorders = new SaleOrdersModel();
        $res = $saleorders->delSaleOrders($this->getData($data));
        if (!$res) {
            return $this->renderError($saleorders->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:提交销售单
     * User:ccl
     * DateTime:2021/1/27 10:15
     * @param Request $request
     * @return array
     */
    public function saveOrdersFormally(Request $request){
        $data = $request->post();
        $saleVilidate = new SaleOrdersValidate();
        if(!$saleVilidate->scene('update')->check($data)){
            return $this->renderError($saleVilidate->getError());
        }
        $saleOrders = new SaleOrdersModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 9;
        $res = $saleOrders->updatePlane($this->getData($data));
        if(!$res){
            return $this->renderError($saleOrders->getError());
        }
        $orders = $saleOrders->getOrdersDetails($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders],'保存成功');
    }

    /**
     * Notes:撤销销售单
     * User:ccl
     * DateTime:2021/2/3 15:54
     * @param Request $request
     * @return array
     */
    public function saveRevokeOrders(Request $request){
        $data = $request->post();
        $saleVilidate = new SaleOrdersValidate();
        if(!$saleVilidate->scene('repeal')->check($data)){
            return $this->renderError($saleVilidate->getError());
        }

        $saleOrders = new SaleOrdersModel();
        //获取销售单详情
        $where['orders_code'] = $data['orders_code'];
        $where['com_id'] = $this->com_id;
        $detail_info = $saleOrders->getOrdersDetails($where);
        if(!$detail_info){
            return $this->renderError('(撤销单据)查询销售单详情失败');
        }
        $res = $saleOrders->revokeSaleOrders($detail_info,$data['lock_version']);
        if(!$res){
            return $this->renderError($saleOrders->getError());
        }
        $order_info = $saleOrders->getOrdersDetails($where);
        return $this->renderSuccess(['orders'=> $order_info],'撤销销售单成功');
    }

    public function loadSOCode()
    {
        $model = new SaleOrdersModel();
        $where = [
            'orders_status' => 7,
            'com_id' => $this->com_id
        ];
        $orders_data = $model->getOne($where,'orders_code,orders_status');
        if($orders_data){
            return json($orders_data);
        }
        $orders_code = BuildCode::dateCode('SO');
        $data = [
            'orders_code' => $orders_code,
            'com_id' => $this->com_id,
            'orders_status' => 7
        ];
        $res = $model->save($data);
        if(!$res){
            return $this->renderError('临时单据创建失败');
        }
        return json($data);
    }

}