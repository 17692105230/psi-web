<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/18
 * Time: 9:38
 */

namespace app\web\controller;
use \app\web\model\Dict as DictModel;


class Customer extends Controller
{
    /**
     * 加载信息列表
     */
    public function loadCustomerClassify(){
        $dictModel = new DictModel();
        $rows = $this->request->get("rows", "25");
        $lists = $dictModel->where($this->getData(["dict_type"=>"client"]))->field("dict_id customer_classify_id,dict_name customer_classify_name, dict_value customer_classify_price, dict_text customer_classify_describe, sort, lock_version")->order("sort asc")->paginate($rows);
        $data["total"] = $lists->total();
        $data["rows"] = $lists->items();
        return json($data);
    }
    /**
     * 添加信息
     */
    public function add() {
        $data = $this->request->post();
        $validate = new \app\web\validate\Customer();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $info_data["dict_type"] = "client";
        $info_data["com_id"] = $this->com_id;
        $info_data["dict_name"] = $data['classify_name'];
        $info_data["dict_value"] = $data["classify_price"];
        $info_data["sort"] = $data["sort"];
        $info_data["dict_text"] = $data["describe_info"];
        $info_data["create_time"] = time();
        $dictModel = new DictModel();
        $res = $dictModel->save($info_data);
        if ($res) {
            return $this->renderSuccess([], "添加成功");
        } else {
            return $this->renderError("添加失败");
        }
    }
    /**
     * 修改
     */
    public function edit() {
        $data = $this->request->post();
        $validate = new \app\web\validate\Customer();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $info_data["dict_id"] = $data["custom_id"];
        $info_data["dict_name"] = $data["classify_name"];
        $info_data["dict_value"] = $data["classify_price"];
        $info_data["sort"] = $data["sort"];
        $info_data["dict_text"] = $data["describe_info"];
        $info_data["lock_version"] = $data["lock_version"];
        $info_data["update_time"] = time();
        $dictModel = new DictModel();
        $res = $dictModel->edit($this->getData($info_data));
        if ($res) {
            return $this->renderSuccess([], "修改成功");
        } else {
            return $this->renderError("修改失败");
        }
    }
    /**
     * 删除客户类别
     */
    public function del() {
        $data = $this->request->post();
        $validate = new \app\web\validate\Customer();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $dictModel = new DictModel();
        $res = $dictModel->del($this->getData(["dict_id"=>$data["classify_id"],"lock_version"=>$data["lock_version"]]));
        if ($res) {
            return $this->renderSuccess([], "操作成功");
        } else {
            return $this->renderError($dictModel->getError());
        }
    }

    /**
     * 加载客户列表
     */
    public function loadList() {
        $data = $this->request->get();
        $validate = new \app\web\validate\Customer();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $dictModel = new DictModel();
        $dict_list = $dictModel->getList($this->getData(["dict_type"=>$data["dict_type"]]),"dict_id,dict_name","sort asc");
        return json($dict_list);
    }
}