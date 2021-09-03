<?php


namespace app\web\controller;

use app\common\utils\BuildCode;
use app\Request;
use app\web\model\GoodsStock as GoodsStockModel;
use app\web\model\OrdersAssist as OrdersAssistModel;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\PurchasePlan as PurchasePlanModel;
use app\web\validate\PurchaseOrders as PurchaseOrdersValidate;
use app\web\validate\PurchasePlan as PurchasePlanValidate;
use app\web\model\PurchaseReject as PurchaseRejectModel;
use app\web\model\Goods as GoodsModel;
use app\web\validate\PurchaseRejectOrder as PurchaseRejectOrderValidate;
use Symfony\Component\VarDumper\Cloner\Data;
use app\common\library\storage\Driver as FileDriver;
use think\App;
use think\facade\View;
class Purchase extends Controller
{

    public function build()
    {

        $validate = new PurchaseOrdersValidate();
        $post_data = $this->request->post();
        if (!$validate->scene('build')->check($post_data)) {
            return $this->renderError($validate->getError());
        }
        $goodsCode = $post_data['goods_code'];
        $warehouseId = $post_data['warehouse_id'];
        $ordersType = $post_data['orders_type'];
        if (strpos($warehouseId, '{') >= 0) {
            $warehouseId = json_decode($warehouseId, true);
        }
        $goodsModel = new GoodsModel();
        $result = $goodsModel->getDetail($this->getData(['goods_code' => $goodsCode]))->toArray();
        $list = $result["detail"];
        $colorList = [];
        $sizeList = [];
        foreach ($list as $item) {
            if (!isset($colorList[$item['color_id']])) {
                $colorList[$item['color_id']] = $item;
            }

            if (!isset($sizeList[$item['size_id']])) {
                $sizeList[$item['size_id']] = $item;
            }
        }

        $goodsStockModel = new GoodsStockModel();
        /* 构造行数据 */
        $arrColor = [];
        foreach ($colorList as $color) {
            $rowColor = array(
                'color_id' => $color['color_id'],
                'color_name' => $color['color_name']
            );
            foreach ($list as $size) {
                if ($size['color_id'] == $color['color_id']) {
                    $rowColor['code_' . $size['size_id']] = $size['size_id'];
                    $rowColor['name_' . $size['size_id']] = $size['size_name'];
                    $rowColor['value_' . $size['size_id']] = 0;

                    if (is_array($warehouseId)) {
                        $_n1 = $goodsStockModel->getStockValue(['warehouse_id' => $warehouseId['out'], 'goods_code' => $color["goods_code"], 'color_id' => $color['color_id'], 'size_id' => $size['size_id']], "stock_number");
                        $_n2 = $goodsStockModel->getStockValue(['warehouse_id' => $warehouseId['in'], 'goods_code' => $color["goods_code"], 'color_id' => $color['color_id'], 'size_id' => $size['size_id']], 'stock_number');
                        $rowColor['stock_' . $size['size_id']] = ($_n1 | 0) . ' | ' . ($_n2 | 0);
                    } else {
                        $_n = 0;
                        if ($warehouseId != 0) {
                            $_n = $goodsStockModel->getStockValue(['warehouse_id' => $warehouseId, 'goods_code' => $color["goods_code"], 'color_id' => $color['color_id'], 'size_id' => $size['size_id']], 'stock_number') | 0;
                        }
                        $rowColor['stock_' . $size['size_id']] = $_n;
                    }
                }
            }
            array_push($arrColor, $rowColor);
        }
        /* 构造列数据 */
        $arrSize = [[
            'field' => 'color_name',
            'fixed' => true,
            'width' => 100,
            'align' => 'center'
        ]];
        $arrStock = [[
            'field' => 'color_name',
            'fixed' => true,
            'width' => 100,
            'align' => 'center'
        ]];
        $i = 0;
        $remainSize = 0;
        $arrField = [];
        foreach ($sizeList as $key => $size) {
            $rowSize = array(
                'field' => 'value_' . $size['size_id'],
                'editor' => 'numberbox',
                'title' => '<strong>' . $size['size_name'] . '</strong>',
                'width' => 10,
                'align' => 'center'
            );
            $arrField[$i++] = 'value_' . $size['size_id'];
            array_push($arrSize, $rowSize);

            $rowStock = array(
                'field' => 'stock_' . $size['size_id'],
                'title' => '<strong>' . $size['size_name'] . '</strong>',
                'width' => 10,
                'align' => 'center'
            );
            array_push($arrStock, $rowStock);
        }

        $result_data = [
            'errcode' => 0,
            'errmsg' => '成功',
            'title' => $result['goods_name'],
            'columns' => [$arrSize],
            'stock' => [$arrStock],
            'rows' => $arrColor,
            'fields' => $arrField,
            'goods' => $result
        ];
        return json($result_data);

    }

    /**
     * 函数描述: 采购单加载订单号
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/2/4
     * Time: 10:32
     * @author mll
     */
    public function loadOrdersCode()
    {
        /**
         * 1.查询当前单据中是否有临时单据。
         * 2.如果有，查询出来 return出去。
         * 3.如果没有，创建一个临时单据存储数据库中
         * 4.临时状态在数据库中 orders_status = 7
         */
        $model = new PurchaseOrdersModel();
        $where = [
            'orders_status' => 7,
            'com_id' => $this->com_id
        ];
        $orders_code = $model->where($where)->field('orders_code,orders_status')->find();
        if ($orders_code){
            $orders_code=[
                'orders_code'=>$orders_code['orders_code'],
                'orders_status'=>$orders_code['orders_status']
            ];
            return json($orders_code);
        }else{
            $orders_code = BuildCode::dateCode('PO');
            $save = [
                'orders_code' => $orders_code,
                'com_id' => $this->com_id,
                'orders_status' => 7
            ];
            $model->save($save);
            $orders_code=[
                'orders_code'=>$orders_code,
                'orders_status'=>7];
            return json($orders_code);
        }
    }

    /**
     * 函数描述:采购订单加载订单号
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/2/4
     * Time: 10:40
     * @author mll
     */
    public function loadPlanOrdersCode()
    {
        $model = new PurchasePlanModel();
        $where = [
            'com_id' => $this->com_id,
            'orders_status' => 7
        ];
        $orders_code = $model->where($where)->field('orders_code,orders_status')->find();
        if ($orders_code){
            $orders_code = [
                'orders_code' => $orders_code['orders_code'],
                'orders_status'=>$orders_code['orders_status']
            ];
            return json($orders_code);
        }else{
            $orders_code = BuildCode::dateCode('PO');
            $save = [
                'orders_code' => $orders_code,
                'com_id' => $this->com_id,
                'orders_status' => 7
            ];
            $model->save($save);
            $orders_code=[
                'orders_code'=>$orders_code,
                'orders_status'=>7];
            return json($orders_code);
        }
    }

    /**
     * 函数描述: 采购退货单
     * Date: 2021/2/5
     * Time: 12:01
     * @author mll
     */
    public function loadOrdersCodeReject()
    {
        $model = new PurchaseRejectModel();
        $where = [
            'com_id' => $this->com_id,
            'reject_status' => 7
        ];
        $orders_code = $model->where($where)->field('orders_code,reject_status')->find();
        if ($orders_code){
            $orders_code = [
                'orders_code' => $orders_code['orders_code'],
                'reject_status'=>$orders_code['reject_status']
            ];
            return json($orders_code);
        }else{
            $orders_code = BuildCode::dateCode('PR');
            $save = [
                'orders_code' => $orders_code,
                'com_id' => $this->com_id,
                'reject_status' => 7
            ];
            $model->save($save);
            $orders_code=[
                'orders_code'=>$orders_code,
                'reject_status'=>7];
            return json($orders_code);
        }
    }

    /**
     * @desc 采购单页表展示
     * @return \think\response\Json
     * Date: 2021/1/6
     * Time: 11:20
     * @author myy
     */
    public function loadOrdersList()
    {
        $model = new PurchaseOrdersModel();
        $data = $this->request->param();
        $result = $model->getPageList($this->getData($data));
        return json($result);
    }

    public function loadPlansList()
    {
        $purchase_plan_model = new PurchasePlanModel();
        $data = $this->request->get();
        $result = $purchase_plan_model->getPageList($this->getData($data));
        return json($result);
    }

    /**
     * @desc  采购单详情接口
     * @return array
     * Date: 2021/1/6
     * Time: 11:19
     * @author myy
     */
    public function loadOrdersDetails()
    {
        $data = $this->request->param();
        $validate = new PurchaseOrdersValidate();
        if (!$validate->scene('detail')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseOrdersModel();
        $data = $model->getOrderDetail($this->getData($data));
        if (!$data) {
            return $this->renderError($model->getError('查询失败'));
        }
        return $this->renderSuccess(['orders' => $data]);
    }

    /**
     * 函数功能描述 加载订单详细信息
     * Date: 2021/1/8
     * Time: 11:01
     * @author gxd
     */
    public function loadPlanDetails()
    {
        $data = $this->request->param();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('detail')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $data = $model->getPlanDetail($this->getData($data));
        if (!$data) {
            return $this->renderError($model->getError('查询失败'));
        }
        return $this->renderSuccess(['orders' => $data]);
    }


    /**
     * Notes:采购退货单展示
     * User:ccl
     * DateTime:2021/1/6 17:02
     * @return \think\response\Json
     */
    public function loadOrdersReject()
    {
        $model = new PurchaseRejectModel();
        $data = $this->request->param();
        $res = $model->getPageList($this->getData($data));
        return json($res);
    }

    /**
     * Notes:采购退货单详情
     * User:ccl
     * DateTime:2021/1/6 17:14
     * @return array
     */
    public function loadOrdersRejectDetails()
    {
        $data = $this->request->param();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('detail')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $data = $model->getRejectOrderDetail($this->getData($data));
        if (!empty($data['settlement_name'])){
            $data['settlement_name'] = '';
        }
        if (!$data) {
            return $this->renderError($model->getError('查询失败'));
        }
        return $this->renderSuccess(['orders' => $data]);
    }

    /**
     * @desc 删除单据
     * @return array
     * Date: 2021/1/6
     * Time: 17:19
     * @author myy
     */
    public function delOrders()
    {
        $data = $this->request->post();
        $validate = new PurchaseOrdersValidate();
        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseOrdersModel();
        $res = $model->delOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * @desc 删除订单
     * @return array
     * Date: 2021/1/6
     * Time: 17:19
     * @author gxd
     */
    public function delPlan()
    {
        $data = $this->request->post();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $res = $model->delPlan($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * Notes:采购退货单删除
     * User:ccl
     * DateTime:2021/1/7 14:15
     * @return array
     */
    public function delRejectOrders()
    {
        $data = $this->request->post();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $res = $model->delRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('删除失败'));
        }
        return $this->renderSuccess([], '删除成功');
    }

    /**
     * @desc  采购单保存草稿
     * @return array
     * Date: 2021/1/7
     * Time: 14:21
     * @author myy
     */
    public function saveOrdersRoughDraft()
    {
        $data = $this->request->post();
        $validate = new PurchaseOrdersValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseOrdersModel();
        $data['orders_status'] = 0;
        $data['user_id'] = $this->user['user_id'];
        $res = $model->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('保存失败'));
        }
        return $this->renderSuccess(['orders' => $res], '保存成功');
    }

    //保存采购订单草稿
    public function savePlanRoughDraft()
    {
        $data = $this->request->post();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $data['orders_status'] = 0;
        $res = $model->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('保存失败'));
        }
        $orders = $model->getPlanDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }
    /* 保存采购退货单（草稿，退货） */
    public function saveRejectOrderRoughDraft()
    {
        $data = $this->request->post();
        $data['reject_status'] = $this->request->get('reject_status');
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseRejectModel();
        $res = $model->updateRejectOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError('保存失败'));
        }
        $orders = $model->getRejectOrderDetail($this->getData(['orders_code' => $res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * @desc  提交采购
     * @return array
     * Date: 2021/1/8
     * Time: 17:00
     * @author myy
     */
    public function saveOrdersFormally()
    {
        $data = $this->request->post();
        $validate = new PurchaseOrdersValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchaseOrdersModel();
        $data['orders_status'] = 9;
        $data['user_id'] = $this->user['user_id'];
        $res = $model->updateOrder($this->getData($data));
        if ($res) {
            return $this->renderSuccess(['orders' => $res], '保存成功');
        }
        return $this->renderError($model->getError('保存失败'));
    }
    //提交采购订单
    public function savePlanFormally()
    {
        $data = $this->request->post();
        $validate = new PurchasePlanValidate();
        if (!$validate->scene('update')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new PurchasePlanModel();
        $data['orders_status'] = 9;
        $data["user_id"] = $this->user["user_id"];
        $res = $model->updateOrder($this->getData($data));
        if (!$res) {
            return $this->renderError($model->getError("保存失败"));
        }
        $orders = $model->getPlanDetail($this->getData(["orders_code"=>$res]));
        return $this->renderSuccess(['orders' => $orders], '保存成功');
    }

    /**
     * 函数描述: 采购单撤销
     * @param Request $request
     * Date: 2021/1/27
     * Time: 15:03
     * @author mll
     */
    public function saveRevokeOrders(Request $request)
    {
        $data = $request->post();
        $orderModel = new PurchaseOrdersModel();
        $orderVali = new PurchaseOrdersValidate();
        if (!$orderVali->scene('detail')->check($data)){
            return $this->renderError($orderVali->getError());
        }
        //获取订单详情
        $where['orders_code'] = $data['orders_code'];
        $where['com_id'] = $this->com_id;
        $detail_info = $orderModel->getOrderDetail($where);
        if (!$detail_info){
            return $this->renderError('未找到单据详情');
        }
        $res = $orderModel->revokePuchaseOrders($detail_info);
        if (!$res){
            return $this->renderError('撤销单据出错');
        }
        //如果成功，比较一下锁版本。更改状态 orders_status = 1
        $update_info = [
            'orders_code'=>$data['orders_code'],
            'com_id' => $this->com_id,
            'orders_id' => $detail_info['orders_id'],
        ];
        $update_state = $orderModel->where($update_info)->save(['orders_status' => 1]);
        if (!$update_state){
            return $this->renderError('撤销单据失败');
        }
        return $this->renderSuccess(['orders'=>$res],'撤销单据成功');
    }

    /**
     * 函数描述: 采购退货单撤销
     * @param Request $request
     * Date: 2021/2/8
     * Time: 10:39
     * @author mll
     */
    public function purchaseRejectRevoke(Request $request)
    {
        $data = $request->post();
        $model = new PurchaseRejectModel();
        $validate = new PurchaseRejectOrderValidate();
        if (!$validate->scene('detail')->check($data)){
            return $this->renderError($validate->getError());
        }
        //获取订单详情
        $detail_info = $model->getRejectOrderDetail($this->getData(['orders_code'=>$data['orders_code']]));

        if (!$detail_info){
            return $this->renderError('撤销出错：查询订单详情异常');
        }
        $res = $model->revokeRejectPuchase($detail_info);
        if (!$res){
            return $this->renderError('撤销单据出错');
        }
        //如果成功，比较一下锁版本。更改状态 orders_status = 1
        $update_info = [
            'orders_code'=>$data['orders_code'],
            'com_id' => $this->com_id,
            'orders_id' => $detail_info['orders_id'],
        ];
        $update_state = $model->where($update_info)->save(['reject_status' => 1]);
        if (!$update_state){
            return $this->renderError('撤销单据失败');
        }
        return $this->renderSuccess(['orders'=>$res],'撤销单据成功');
    }
    public function uploadfile(Request $request)
    {
        $data = $request->post();
        $driver = new FileDriver(config('upload'), 'local');
        //设置POST文件对象name (前端传输)
        $driver->setUploadFile('file');
        //设置不允许的文件后缀
        $driver->setNotAllowedExt(['php']);
        //执行文件上传操作
        $res = $driver->upload();
        if (!$res){
            return $this->renderError($driver->getError());
        }
        //获取文件保存的地址
        $file_name = $driver->getFileName();
        $oamodel = new OrdersAssistModel();
        $insert_data = [
            'orders_code' => $data['orders_code'],
            'assist_category' => $data['type'],
            'assist_name' => substr($data['name'],0,strripos($data['name'],".")),
            'assist_extension' => substr($data['name'],strripos($data['name'],".")+1),
            'assist_url' => $file_name,
            'assist_size' => $data['size'],
            'orders_categories' => 10,//采购单
            'com_id' => $this->com_id,
        ];
        $res_insert = $oamodel->save($insert_data);
        if (!$res_insert){
            unlink($file_name);
            return $this->renderError('保存图片信息失败');
        }
        return $this->renderSuccess('上传成功');
    }

    /**
     * 函数描述:  查询文件列表
     * @param Request $request
     * @return array|\think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/3/1
     * Time: 9:41
     * @author mll
     */
    public function seeOrdersFile(Request $request)
    {
        $data = $request->get();
        if (!$data['ordersCode']){
            return $this->renderError('请传入订单号');
        }
        $oamodel = new OrdersAssistModel();
        $res = $oamodel->where(['orders_code'=>$data['ordersCode']])->field(['assist_id','assist_name','create_time','assist_extension','assist_url'])->select();
        return json($res);
    }
    public function delOneFile(Request $request)
    {
        $data = $request->get();
        $id = $data['id'];
        $assist_url = $data['assist_url'];
        $model = new OrdersAssistModel();

        if (unlink('../public'.$assist_url)){
            $res = $model->where("assist_id",$id)->delete();
            if (!$res){
                return $this->renderError('删除失败');
            }
            return $this->renderSuccess('删除文件成功');
        }
        return $this->renderError('删除失败');
    }
}