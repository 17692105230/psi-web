<?php


namespace app\web\controller;

use app\Request;
use app\web\model\SaleRejectApply as SaleRejectApplyModel;
use app\web\validate\SaleRejectApply as SaleRejectApplyValidate;
class SaleRejectApply extends Controller
{

    /**
     * Notes:加载销售退货申请
     * User:ccl
     * DateTime:2021/1/29 11:51
     * @param Request $request
     * @return array|\think\response\Json
     */
    public function loadApplyList(Request $request){
        $page = $request->post('page',1);
        $rows = $request->post('rows',30);
        $where = $request->post();
        $sale_orders =new SaleRejectApplyModel();
        $res = $sale_orders->loadData($this->getData($where),$page,$rows);
        if(!$res){
            return $this->renderError($sale_orders->getError('查询失败'));
        }
        return json($res);
    }

    /**
     * Notes:加载销售退货申请详情
     * User:ccl
     * DateTime:2021/1/29 14:30
     * @param Request $request
     */
    public function loadApplyDetails(Request $request){
        $orders_code = $request->post();
        $sale_reject_apply = new SaleRejectApplyModel();
        $res = $sale_reject_apply->getRejectApply($this->getData($orders_code));
        if(!$res){
            return $this->renderError($sale_reject_apply->getError('查询失败'));
        }
        return $this->renderSuccess($res);
    }

    /**
     * Notes:保存销售退货申请为草稿
     * User:ccl
     * DateTime:2021/1/29 14:33
     * @param Request $request
     */
    public function saveRejectApplyDraft(Request $request){
        $data = $request->post();
        $saleRejectApplyVilidate = new SaleRejectApplyValidate();
        if(!$saleRejectApplyVilidate->scene('saveDraft')->check($data)){
            return $this->renderError($saleRejectApplyVilidate->getError());
        }
        $sale_reject_apply = new SaleRejectApplyModel();
        $user_info = $this->user;
        $data['user_id'] = $user_info['user_id'];
        $data['orders_status'] = 0;
        $res = $sale_reject_apply->updateRejectApply($this->getData($data));
        if(!$res){
            return $this->renderError($sale_reject_apply->getError());
        }
        $orders = $sale_reject_apply->getRejectApply($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders],'保存成功');
    }

    /**
     * Notes:删除销售退货申请
     * User:ccl
     * DateTime:2021/1/29 17:28
     * @return array
     */
    public function delRejectApply(){
        $data = $this->request->post();
        $saleRejectApplyVilidate = new SaleRejectApplyValidate();
        if (!$saleRejectApplyVilidate->scene('delete')->check($data)) {
            return $this->renderError($saleRejectApplyVilidate->getError());
        }
        $sale_reject_apply = new SaleRejectApplyModel();
        $res = $sale_reject_apply->delRejectApply($this->getData($data));
        if (!$res) {
            return $this->renderError($sale_reject_apply->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:提交销售退货申请为正式(并流转至销售退货单)
     * User:ccl
     * DateTime:2021/2/1 9:32
     * @param Request $request
     * @return array
     */
    public function saveRejectApplyFormally(Request $request){
        $data = $request->post();
        $saleRejectApplyVilidate = new SaleRejectApplyValidate();
        if(!$saleRejectApplyVilidate->scene('saveDraft')->check($data)){
            return $this->renderError($saleRejectApplyVilidate->getError());
        }
        $sale_reject_apply = new SaleRejectApplyModel();
        $data['orders_status'] = 9;
        $data['user_id'] = $this->user['user_id'];
        $res = $sale_reject_apply->updateRejectApply($this->getData($data));
        if(!$res){
            return $this->renderError($sale_reject_apply->getError('保存失败'));
        }
        $orders = $sale_reject_apply->getRejectApply($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders],'保存成功');
    }


}