<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/6
 * Time: 9:54
 */

namespace app\web\controller;

use app\Request;
use app\web\model\Supplier as SupplierModel;
use app\web\validate\Supplier as SupplierValidate;

class Supplier extends Controller
{
    /**
     * Notes:加载供应商列表
     * User:ccl
     * DateTime:2021/1/6 10:41
     * @param Request $request
     * @return \think\response\Json
     */
    public function loadGrid(Request $request)
    {
        $page = $request->get('page', 1);
        $rows = $request->get('rows', 20);
        $where = $request->get();
        $supplier = new SupplierModel();
        $data = $supplier->getGridList($this->getData($where), $rows, $page);
        return json($data);
    }

    /**
     * Notes:添加供应商
     * User:ccl
     * DateTime:2021/1/6 11:48
     * @param Request $request
     * @return array
     */
    public function supplierSave(Request $request)
    {
        $data = $request->post();
        $validate = new SupplierValidate();
        if (!$validate->scene('add')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $supplier = new SupplierModel();
        $res = $supplier->addSupplier($this->getData($data));
        if (!$res) {
            return $this->renderError('添加供应商失败');
        }
        return $this->renderSuccess('', '添加供应商成功');
    }

    /**
     * Notes:删除供应商
     * User:ccl
     * DateTime:2021/1/6 14:16
     * @param Request $request
     * @return array
     */
    public function delSupplier(Request $request)
    {
        $supplier_id = $request->get();
        $validate = new SupplierValidate();
        if (!$validate->scene('del')->check($supplier_id)) {
            return $this->renderError($validate->getError());
        }
        $supplier = new SupplierModel();
        $res = $supplier->delSupplier($this->getData($supplier_id));
        if (!$res){
            return $this->renderError('删除失败');
        }
        return $this->renderSuccess('','删除成功');
    }

    /**
     * Notes:获取详情
     * User:ccl
     * DateTime:2021/1/6 15:10
     * @param Request $request
     * @return array
     */
    public function loadSupperInfo(Request $request){
        $data = $request->get();
        $validate = new SupplierValidate();
        if (!$validate->scene('del')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $supplier = new SupplierModel();
        $data = ['supplier_id' => $data['supplier_id']];
        $res = $supplier->getOne($this->getData($data),'*');
        if (!$res){
            return $this->renderError($supplier->getError('获取详情失败'));
        }
        return $this->renderSuccess($res);
    }
    public function updateSupper(Request $request){
        $data = $request->post();
        $validate = new SupplierValidate();
        if (!$validate->scene('add')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $supplier = new SupplierModel();
        $res = $supplier->edit($this->getData($data));
        if(!$res){
            return $this->renderError($supplier->getError('更改供应商信息失败'));
        }
        return $this->renderSuccess('','更新成功');
    }
    //查询供应商
    public function loadCombobox() {
        $supplier_model = new SupplierModel();
        $suppliers = $supplier_model->where('supplier_status', 1)->where("com_id", $this->com_id)->field("supplier_id,supplier_name")->order("sort asc")->select();
        return json($suppliers);
    }
}