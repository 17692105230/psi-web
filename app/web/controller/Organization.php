<?php


namespace app\web\controller;
use app\web\model\Organization as OrganizationModel;
use app\web\validate\Organization as OrganizationValidate;

class Organization extends Controller
{
    private $organization_model;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->organization_model = new OrganizationModel();
    }

    public function loadWarehouseList() {
        $list = $this->organization_model->getListByType(2,$this->com_id);
        return json($list);
    }

    /**
     * 加载组织机构树
     */
    public function loadTree() {
        $list =  $this->organization_model->where("com_id", $this->com_id)->field("org_id as id, org_pid, org_name as text, org_head,org_type,org_phone,org_status,sort,lock_version,update_time")->select();
        $tree = [];
        if (count($list) > 0) {
            $tree = createTree($list->toArray(),"id", "org_pid");
        }
        return json($tree);
    }
    /**
     * 保存组织机构信息
     * org_type 0:内部机构 1：外部门店 2：仓库
     */
    public function saveOrg() {
        $data = $this->request->post();
        $validate = new OrganizationValidate();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        //添加
        if(empty(trim($data["org_id"]))) {
            $insert_data["org_pid"] = $data["org_pid"];
            $insert_data["org_type"] = $data["org_type"];
            $insert_data["org_name"] = $data["org_name"];
            $insert_data["org_head"] = $data["org_head"];
            $insert_data["org_phone"] = $data["org_phone"];
            $insert_data["sort"] = $data["org_sort"];
            $insert_data["org_status"] = $data["org_status"];
            $insert_data["lock_version"] = $data["lock_version"];
            $res = $this->organization_model->inserInfo($this->getData($insert_data));
        } else {
            //修改
            $lockVersion = $this->organization_model->where("com_id", $this->com_id)->where('org_id', $data["org_id"])->value("lock_version");
            if ($lockVersion === null) {
                return $this->renderError("系统数据错误，没有找到锁版本~~");
            }
            if ($lockVersion != $data["lock_version"]) {
                return $this->renderError("警告，数据已被其他用户更改，请重新获取~~");
            }
            $update_data["lock_version"] = $data["lock_version"]+1;
            $update_data["org_pid"] = $data["org_pid"];
            $update_data["org_type"] = $data["org_type"];
            $update_data["org_name"] = $data["org_name"];
            $update_data["org_head"] = $data["org_head"];
            $update_data["org_phone"] = $data["org_phone"];
            $update_data["sort"] = $data["org_sort"];
            $update_data["org_status"] = $data["org_status"];
            $update_data["org_id"] = $data["org_id"];
            $update_data["update_time"] = time();
            $res = $this->organization_model->updateInfo($this->getData($update_data));
        }
        if ($res) {
            return $this->renderSuccess([], "保存组织机构信息成功~~");
        } else {
            return $this->renderError("保存组织机构信息失败~~");
        }
    }
    /**
     * 删除组织机构信息
     */
    public function delorg() {
        $org_id = $this->request->post("org_id","","intval");
        if (empty($org_id)) {
            return $this->renderError("操作有误~~");
        }
        //查询是否存在子级数据
        $son_count = $this->organization_model->where("org_pid", $org_id)->where("com_id", $this->com_id)->count();
        if ($son_count) {
            return $this->renderError("警告，删除的组织机构存在子项，不能操作~~");
        }
        //删除
        $res = $this->organization_model->where('com_id', $this->com_id)->where("org_id", $org_id)->delete();
        if ($res) {
            return $this->renderSuccess([], "保存组织机构信息成功~~");
        } else {
            return $this->renderError("保存组织机构信息失败~~");
        }
    }
}