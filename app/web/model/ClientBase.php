<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/16
 * Time: 17:40
 */

namespace app\web\model;

use app\common\model\web\ClientBase as ClientBaseModel;
use think\Exception;

class ClientBase extends ClientBaseModel
{
    public function loadClient($where, $page, $rows)
    {
        $query = $this->alias('cb')
            ->join('hr_client_account ca', 'cb.client_id = ca.client_id', 'left')
            ->where(['cb.com_id' => $where['com_id'],'cb.client_status' => 0])
            ->when(isset($where['keyword']) && $where['keyword'], function ($query) use ($where) {
                $query->where('cb.client_name', 'like', '%' . $where['keyword'] . '%')
                    ->whereOr('cb.client_phone', 'like', '%' . $where['keyword'] . '%');
            });
        $total = $query->count();
        $offset = ($page - 1) * $rows;
        $rows = $query->limit($offset, $rows)->field('cb.client_id,cb.client_name,cb.client_phone,cb.client_discount,ca.account_money')->order('cb.client_id', 'desc')->select();
        return compact('total', 'rows');
    }
    //添加客户
    public function addClient($data) {
        $this->startTrans();
        try{
            //添加数据
            $add_data["client_name"] = $data["client_name"];
            $add_data["com_id"] = $data["com_id"];
            $add_data["client_category_id"] = $data["client_category_id"];
            $add_data["client_discount"] = $data["client_discount"];
            $add_data["client_status"] = $data["client_status"];
            $add_data["client_phone"] = $data["client_phone"];
            $add_data["client_email"] = $data["client_email"];
            $add_data["client_address"] = $data["client_address"];
            $add_data["client_story"] = $data["client_story"];
            $add_data['create_time'] = time();
            $add_data['update_time'] = $add_data['create_time'];
            $client_id = $this->insertGetId($add_data);
            if (!$client_id) {
                throw new Exception("添加数据失败");
            }
            //添加账号记录
            $account["client_id"] = $client_id;
            $account["update_time"] = $add_data['update_time'];
            $clientAccountModel = new ClientAccount();
            $clientAccountModel->insert($account);
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }
    //更新
    public function updateClient($data) {
        $this->startTrans();
        try {
            $lockVersion = $this->where("client_id", $data["client_id"])->where("com_id", $data["com_id"])->value("lock_version");
            if ($lockVersion ==  null) {
                throw new Exception("系统数据错误，没有找到锁版本~~");
            }
            if ($lockVersion != $data["lock_version"]) {
                throw new Exception("错误，数据已被其他用户更改，请重新获取~~");
            }
            $edit_data["client_name"] = $data["client_name"];
            $edit_data["client_category_id"] = $data["client_category_id"];
            $edit_data["client_discount"] = $data["client_discount"];
            $edit_data["client_status"] = $data["client_status"];
            $edit_data["client_phone"] = $data["client_phone"];
            $edit_data["client_email"] = $data["client_email"];
            $edit_data["client_address"] = $data["client_address"];
            $edit_data["client_story"] = $data["client_story"];
            $edit_data["lock_version"] = $data["lock_version"]+1;
            $res = $this->exists(true)->where("client_id", $data["client_id"])->where("com_id", $data["com_id"])->save($edit_data);
            if (!$res) {
                return new Exception("客户信息未改变~~");
            }
            $this->commit();
            return true;
        } catch (\Exception $e) {
            $this->rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }
    //根据client_id查询信息
    public function getInfoByClientId($data) {
        $data = $this->alias("cb")
            ->where("cb.com_id", $data["com_id"])
            ->where("cb.client_id", $data["client_id"])
            ->leftJoin("hr_client_account ca","ca.client_id=cb.client_id")
            ->field("cb.*,ca.account_money,ca.account_fmoney, ca.account_fmoney,ca.account_ptmoney,ca.account_potmoney,ca.account_number,ca.account_last_money,ca.account_last_time")
            ->find();
        return $data;
    }
}