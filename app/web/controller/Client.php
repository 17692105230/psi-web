<?php

namespace app\web\controller;
use app\Request;
use app\web\model\ClientAccount;
use app\web\model\ClientBase as ClientBaseModel;
use think\Exception;

/**
 * 客户管理
 * Class Client
 * @package app\web\controller
 */
class Client extends Controller
{
    //加载顾客信息
//    public function loadClientCombox(){
//        $clientModel = new ClientBaseModel();
//        $res = $clientModel->getList($this->getData(["client_status"=>0]), "*", "client_id desc");
//        return json($res);
//    }
    //搜索顾客
    public function loadClientCombox(Request $request){
        $page = $request->get('page',1);
        $rows = $request->get('rows',15);
        $data = $request->get();
        $clientModel = new ClientBaseModel();
        $res = $clientModel->loadClient($this->getData($data),$page,$rows);
        return json($res);
    }
    //加载客户信息
    public function loadList() {
        $rows = $this->request->get("rows", 20);
        $keyword = $this->request->get("keyword","");
        $clientModel = new ClientBaseModel();
        $lists = $clientModel->alias("base")
            ->where("base.com_id", $this->com_id)
            ->leftJoin("hr_dict dict", "base.client_category_id=dict.dict_id")
            ->leftJoin("hr_client_account account", "base.client_id=account.client_id")
            ->field("base.client_id,base.client_code,base.client_name,base.client_phone,base.client_discount,base.client_status,base.create_time,base.update_time,dict.dict_name client_category_name,account.account_id,account.account_money,account.account_fmoney")
            ->when(!empty($keyword),function($query)use($keyword){
                $query->where(function($query)use($keyword){
                    $query->whereOr("base.client_name","like","%{$keyword}%")
                        ->whereOr("base.client_phone", "like", "%{$keyword}%")
                        ->whereOr("base.client_code", "like", "%{$keyword}%");
                });
            })
            ->paginate($rows);
        return json(["total"=>$lists->total(),"rows"=>$lists->items()]);
    }
    //保存客户信息
    public function saveclient() {
        $data = $this->request->post();
        $validate = new \app\web\validate\Client();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $clientModel = new ClientBaseModel();
        //添加新信息
        if (empty($data["client_id"])) {
            $res = $clientModel->addClient($this->getData($data));
        } else {
            //更新信息
            $res = $clientModel->updateClient($this->getData($data));
        }
        if ($res) {
            return $this->renderSuccess([],"操作成功");
        } else {
            return $this->renderError($clientModel->getError());
        }
    }
    /**
     * 加载数据
     */
    public function loadData() {
        $data = $this->request->get();
        $validate = new \app\web\validate\Client();
        $res = $validate->scene($this->request->action(true))->append("client_id","require")->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        //查询账户信息
        $clientModel = new ClientBaseModel();
        $info = $clientModel->getInfoByClientId($this->getData($data));
        if($info) {
            return $this->renderSuccess($info, "数据加载成功");
        } else {
            return $this->renderError("数据加载失败");
        }
    }
    /**
     * 删除客户信息
     */
    public function delClient() {
        $data = $this->request->get();
        $validate = new \app\web\validate\Client();
        $res = $validate->scene($this->request->action(true))->append("client_id", "require")->check($data);
        if (false === $res) {
            $this->renderError($validate->getError());
        }
        $clientModel = new ClientBaseModel();
        $clientAccountModel = new ClientAccount();
        try {
            $clientModel->startTrans();
            $cres = $clientModel->where("client_id", $data["client_id"])->where("com_id", $this->com_id)->delete();
            if (!$cres) {
                throw new Exception("删除客户信息失败~~");
            }
            $cares = $clientAccountModel->where("client_id", $data["client_id"])->where("com_id", $this->com_id)->delete();
            if (!$cares) {
                throw new Exception("删除客户信息失败~~");
            }
            $clientModel->commit();
            return $this->renderSuccess("删除客户信息成功~~");
        } catch (\Exception $e) {
            $clientModel->rollback();
            return $this->renderError("删除客户信息错误~~");
        }
    }
}