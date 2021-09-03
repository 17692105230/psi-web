<?php


namespace app\web\controller;
use app\web\model\User as UserModel;

class User extends Controller
{
    /**
     * 函数功能描述 加载用户信息
     * Date: 2021/1/16
     * Time: 14:59
     * @author gxd
     */
    public function loadUserCombobox() {
        $userModel = new UserModel();
        $res = $userModel->getList($this->getData(["user_status"=>0]), "user_id, user_name", "user_id desc");
        return json($res);
    }

}