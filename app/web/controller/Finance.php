<?php


namespace app\web\controller;

use app\Request;
use think\Validate;
use app\common\utils\BuildCode;
use app\web\model\DiarySupplier as DiarySupplierModel;
use app\web\validate\Finance as FinanceValidate;
use app\web\model\DiaryFinance as DiaryFinanceModel;
use app\web\model\Supplier;
use think\Facade\Db;
use app\web\model\Supplier as SupplierModel;

class Finance extends Controller
{
    /**
     * 函数描述: 供应商对账查询
     * Date: 2021/3/2
     * Time: 11:28
     * @param Request $request
     * @author mll
     */
    public function querySupplierRecord(Request $request)
    {
        $data = $request->post();
        $data['search_end_date']= $data['search_end_date'] + 86400;
        $diaryModel = new DiarySupplierModel();
        $res = $diaryModel->queryListDiarySupplier($this->getData($data));
        return json($res);
    }

    /**
     * 账户流水-收入
     */
    public function saveAccountIn(Request $request)
    {
        $data = $request->post();

        $validate = new FinanceValidate();
        if (!$validate->scene('saveAccountIn')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $data['com_id'] = $this->com_id;
        $data['user_id'] = $this->user['user_id'];
        $data['surplus'] = $data['income'];
        $data['counterparty'] = 0;
        $data['order_date'] = strtotime($data['order_date']);
        $model = new DiaryFinanceModel();
        if (!isset($data['details_id']) || empty(trim($data['details_id']))) {
            $data['orders_code'] = BuildCode::dateCode('DF');
            $data['item_type'] = 9199;
            $res = $model->saveAccountIn($data);
            if ($res) {
                return $this->renderSuccess([],'账户收入流水创建成功');
            }
            return $this->renderError('创建收入流水创建失败。失败原因：'.$model->getError('写入失败'));
        }

        /* 修改 */
        $res = $model->updateAccountIn($data);
        if ($res) {
            return $this->renderSuccess([],'收入流水修改成功');
        }
        return $this->renderError($model->getError('修改失败'));

    }

    // 账户流失搜索列表
    public function queryAccountRecord()
    {
        $param = $this->request->get();
        $param = $this->getData($param);
        $model = new DiaryFinanceModel();
        $data = $model->queryAccountList($param);

        return json($data);
    }
    // 账户流水 支出
    public function saveAccountOut(Request $request)
    {
        $data = $request->post();
        $validate = new FinanceValidate();
        if (!$validate->scene('saveAccountOut')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $data['surplus'] = floatval($data['expend']) * -1;
        $data['order_date'] = strtotime($data['order_date']);
        $data['income'] = 0;
        $data['user_id'] = $this->user['user_id'];
        $data['com_id'] = $this->com_id;
        if (!isset($data['details_id']) || empty($data['details_id'])) {
            $data['orders_code'] = BuildCode::dateCode('DF');

            $data['counterparty'] = 0;
            $data['item_type'] = 9198;

            $model = new DiaryFinanceModel();
            $res = $model->saveAccountOut($data);
            if ($res) {
                return $this->renderSuccess([],'账户支出流水创建成功');
            }
            return $this->renderError('创建支出流水创建失败。失败原因：'.$model->getError('写入失败'));
        }
        // 修改
        $model = new DiaryFinanceModel();
        $res = $model->updateAccountOut($data);
        if ($res) {
            return $this->renderSuccess([],'账户支出流水修改成功');
        }
        return $this->renderError('创建支出流水创建失败。失败原因：'.$model->getError('写入失败'));


    }
    // 账户流水 账户互转
    public function saveAccountLoop(Request $request)
    {
        $data = $request->post();

        $validate = new FinanceValidate();
        if (!$validate->scene('saveAccountLoop')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $data = $this->getData($data);
        $data['user_id'] = $this->user['user_id'];
        $data['order_date'] = strtotime($data['order_date']);
        $model = new DiaryFinanceModel();
        if (!isset($data['orders_code']) || empty($data['orders_code'])) {
            $data['orders_code'] = BuildCode::dateCode('DF');
            $data['account_id'] = 1;
            $data['counterparty'] = 0;
            $data['item_type'] = 9107;
            $res = $model->saveAccountLoop($data);
            if ($res) {
                return $this->renderSuccess([],'账户支出流水创建成功');
            }
            return $this->renderError('账户互转创建失败。失败原因：'.$model->getError('写入失败'));
        }

        //修改互转
        $res = $model->updateAccountLoop($data);
        if ($res) {
            return $this->renderSuccess([],'账户互转修改成功');
        }
        return $this->renderError('账户互转修改失败。失败原因：'.$model->getError('修改失败'));
    }
    // 账户流失 期初调整
    public function saveAccountBegin(Request $request)
    {
        $data = $request->post();
        $validate = new FinanceValidate();
        if (!$validate->scene('saveAccountBegin')->check($data)) {
            return $this->renderError($validate->getError());
        }

        $data['user_id'] = $this->user['user_id'];
        $data['order_date'] = strtotime($data['order_date']);
        $data['com_id'] = $this->com_id;
        if (!isset($data['details_id']) || empty($data['details_id'])) {
            $data['orders_code'] = BuildCode::dateCode('DF');
            $data['account_id'] = 3; // 期初调整
            $data['counterparty'] = 0;
            $data['item_type'] = 9105; // 期初调整
            $model = new DiaryFinanceModel();
            $res = $model->saveAccountBegin($data);
            if ($res) {
                return $this->renderSuccess([],'账户支出流水成功');
            }
            return $this->renderError('创建支出流水创建失败。失败原因：'.$model->getError('写入失败'));
        }
        // 修改
        $model = new DiaryFinanceModel();
        $res = $model->updateAccountBegin($data);
        if ($res) {
            return $this->renderSuccess([],'期初调整修改成功');
        }
        return $this->renderError('期初调整修改失败。失败原因：'.$model->getError('写入失败'));

    }
    // 账户流失  查询记录详情
    public function loadAccountRecordItem(Request $request)
    {
        $details_id = $request->post('details_id');
        if (empty($details_id)) {
            return $this->renderError('账户流水ID不能为空');
        }
        $model = new DiaryFinanceModel();
        $info = $model->getOne(['details_id' => $details_id, 'com_id' => $this->com_id]);
        if ($info) {
            return $this->renderSuccess($info);
        }
        return $this->renderError('账务流水不存在');
    }

    // 加载账户互转记录
    public function loadAccountLoopItem(Request $request)
    {
        $orders_code = $request->post('orders_code');
        if (empty($orders_code)) {
            return $this->renderError('查询账户流失单号不能');
        }
        $model = new DiaryFinanceModel();
        $rowIn = $model->getList(['orders_code' => $orders_code, 'com_id' => $this->com_id]);
        if (count($rowIn) != 2) {
            return $this->renderError('无法找到数据，查询失败~~');
        }
        if ($rowIn[0]['income'] == 0) {
            $rowOut = $rowIn[0];
            $rowIn = $rowIn[1];
        } else {
            $rowOut = $rowIn[1];
            $rowIn = $rowIn[0];
        }

        $res = [
            'details_id' => $rowOut['details_id'],
            'orders_code' => $rowOut['orders_code'],
            'settlement_id_out' => $rowOut['settlement_id'],
            'settlement_id_in' => $rowIn['settlement_id'],
            'ta_money' => $rowOut['expend'],
            'create_time' => $rowOut['create_time'],
            'remark' => $rowOut['remark'],
            'lock_version' => $rowOut['lock_version']
        ];
        return $this->renderSuccess($res, '加载数据成功');
    }
    // 删除单据
    public function delAccountRecordItem(Request $request)
    {
        $details_id = $request->post('details_id');
        if (empty($details_id)) {
            return $this->renderError('删除单据ID不能为空');
        }
        $data  = [
          'details_id' => $details_id,
          'com_id' => $this->com_id,
        ];

        $model = new DiaryFinanceModel();
        $res = $model->delAccountRecordItem($data);
        if ($res) {
            return $this->renderSuccess([],'删除成功');
        }
        return $this->renderError($model->getError('删除失败'));
    }

     /* 函数描述: 供应商对账-付款
     * @param Request $request
     * Date: 2021/3/10
     * Time: 14:10
     * @author mll
     */
    public function saveSupplierOut(Request $request)
    {
        $data = $request->post();
        $supplierModel = new SupplierModel();
        $dsModel = new DiarySupplierModel();
        //根据供应商id查询出供应商余额
        $supplier_money = $supplierModel->where(['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']])->field('supplier_money')->find();
        //计算余额
        $money = bcadd($data['out_money'],$supplier_money['supplier_money']);
        //更新供应商余额
        $supplier_money = $supplierModel->where(['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']])->save(['supplier_money'=>$money]);
        if (!$supplier_money){
            return $this->renderError('更新供应商金额失败');
        }
        //生成付款单据
        if (empty(trim($data['details_id'])))
        {
            $data['orders_code'] = BuildCode::dateCode('FK');
            //查询更新之后的供应商金额
            $supplier_money = $supplierModel->where(['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']])->field('supplier_money')->find();
            $save_data = [
                'supplier_id' => $data['supplier_id'],
                'orders_code' => $data['orders_code'],
                'user_id' => $this->user['user_id'],
                'settlement_id' => $data['settlement_id'],
                'account_id' => $data['account_id'],
                'rmoney' => $data['out_money'],
                'supplier_balance' => $supplier_money['supplier_money'],
                'orders_date' => strtotime($data['create_time']),
                'item_type' => 9198,
                'remark' => $data['remark'],
                'com_id' => $this->com_id,
                'create_time' => time(),
                'update_time' => time()
            ];

            $res = $dsModel->insert($save_data);
            if (!$res) {
                return $this->renderError('添加付款流水失败');
            }
            return $this->renderSuccess('','修改成功');
        }else{
            /**
             * 如果有details为更新
             * 1.更新供应商金额
             * 2.修改单据内容
             */
            //1.更新供应商金额
            $su_where = ['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']];
            //查询供应商金额
            $resSuMoney = $supplierModel->where($su_where)->field('supplier_money')->find();
            $new_money = bcadd($data['out_money'],$resSuMoney['supplier_money']);
            $resSu = $supplierModel->where($su_where)->save(['supplier_money'=>$new_money]);
            if (!$resSu){

                return $this->renderError('更新供应商金额出错');
            }
            //2.修改单据内容
            $ds_where = ['com_id'=>$this->com_id,'details_id'=>$data['details_id'],'lock_version'=>$data['lock_version']];
            //先判断锁版本
            $lock = $dsModel->where($ds_where)->find();
            if (!$lock){
                return $this->renderError('为找到对应版本信息');
            }
            $ds_data = [
                'supplier_id' => $data['supplier_id'],
                'settlement_id' => $data['settlement_id'],
                'account_id' => $data['account_id'],
                'rmoney' => $data['out_money'],
                'orders_date' => time(),
                'remark' => $data['remark'],
                'lock_version' => $data['lock_version'] +1
            ];
            //开始更新单据内容
            $res = $dsModel->where($ds_where)->save($ds_data);
            if (!$res){
                return $this->renderError('修改单据内容失败');
            }
            return $this->renderSuccess('','修改成功');
        }
    }

    /**
     * 函数描述: 付款详情
     * @param Request $request
     * Date: 2021/3/11
     * Time: 16:31
     * @author mll
     */
    public function loadOrdersFinanceOut(Request $request)
    {
        $data = $request->post();
        $diaryModel = new DiarySupplierModel();
        $res = $diaryModel->getDetailsByOrdersCode($this->getData($data));
        if (!$res){
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res,'查询成功');
    }

    /**
     * 函数描述: 期初-选择供应商
     * @param Request $request
     * Date: 2021/3/12
     * Time: 14:34
     * @author mll
     */
    public function loadSupplierBeginItem(Request $request)
    {
        $data = $request->post();
        /* 根据id查询供应商金额 */
        $suModel = new Supplier();
        $res = $suModel->where(['com_id'=>$this->com_id,'supplier_id'=>$data['supplier_id']])
            ->field('supplier_money')
            ->find();
        if (!$res){
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res,'查询成功');
    }

    /**
     * 函数描述: 期初调整提交
     * @param Request $request
     * Date: 2021/3/12
     * Time: 15:08
     * @author mll
     */
    public function saveSupplierBegin(Request $request)
    {
        $data = $request->post();
        $supplier = new Supplier();
        // 修改供应商金额
        $sup = $supplier->
        where(['com_id'=>$this->com_id,'supplier_id' => $data['supplier_id']])
            ->save(['supplier_money'=>$data['begin_money']]);
        if (!$sup){
            return $this->renderError("修改供应商金额错误");
        }
        // 添加供应商期初调整单据
        // 如果details_id 为空 是新增
        if (empty(trim($data['details_id']))){
            $save_data = [
                "supplier_id" => $data['supplier_id'],
                "orders_code" => BuildCode::dateCode('DC'),
                "usr_id" => $this->user['user_id'],
                "supplier_balance" => $data['begin_money'],
                "item_type" => 9200,
                "com_id" => $this->com_id,
                "orders_date" => strtotime($data['create_time']),
                "create_time" => time(),
                "update_time" => time()
            ];
            $DiarySupplierModel = new DiarySupplierModel();
            if (!$DiarySupplierModel->save($save_data)){
                return $this->renderError('保存单据失败');
            }
            return $this->renderSuccess("保存单据成功");
        }
        // details_id 不为空，是修改
        $dsmodel = new DiarySupplierModel();
        //先修改供应商表金额
        $su_res = $supplier->where(['supplier_id'=>$data['supplier_id'],'com_id'=>$this->com_id])->save(['supplier_money'=>$data['begin_money']]);
        //修改的数组
        $data_save = [
            'orders_date' => strtotime($data['create_time']),
            'supplier_balance' => $data['begin_money'],
            'remark' => $data['remark']
        ];
        $res = $dsmodel->where([
            'details_id' => $data['details_id'],
            'com_id' => $this->com_id,
        ])->save($data_save);
        if (!$res){
            return $this->renderError('修改期初信息失败');
        }
        return $this->renderSuccess($res,'修改成功');
    }

    /**
     * 函数描述: 期初调整，根据单据号回显数据
     * Date: 2021/3/17
     * Time: 9:11
     * @author mll
     */
    public function loadBeginSupplier(Request $request)
    {
        $data = $request->post();
        $model = new DiarySupplierModel();
        $res = $model
            ->alias('d')
            ->where(['d.orders_code'=>$data['orders_code'],'d.com_id'=>$this->com_id])
            ->join('hr_supplier s','s.supplier_id = d.supplier_id','left')
            ->field('d.supplier_id,d.orders_code,d.details_id,d.supplier_balance,d.item_type,
                d.orders_date,d.remark,d.lock_version,s.supplier_money')
            ->find();
        if (!$res){
            return $this->renderError('查询失败');
        }
        return $this->renderSuccess($res,'查询成功');
    }

}