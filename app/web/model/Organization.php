<?php


namespace app\web\model;
use app\common\model\web\Organization as OrganizationModel;

class Organization extends OrganizationModel
{
    /**
     * 函数功能描述 根据组织机构类型获取组织机构信息
     * @param $type
     * Date: 2021/1/6
     * Time: 10:36
     * @author gxd
     */
    public function getListByType($type, $com_id, $field="*", $sort="asc") {
        return $this->where("org_type", $type)->where("org_status", 1)->where('com_id', $com_id)->field($field)->order("sort", $sort)->select();
    }
    //插入数据
    public function inserInfo($data) {
        return $this->insert($data);
    }
    //更新数据
    public function updateInfo($data) {
        return $this->exists(true)->where("org_id", $data["org_id"])->where("com_id", $data["com_id"])->save($data);
    }
}