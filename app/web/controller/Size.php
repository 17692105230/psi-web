<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2020/12/31
 * Time: 9:30
 */

namespace app\web\controller;

use app\web\model\Size as SizeModel;
use app\Request;
use think\response\Json;
use app\web\model\GoodsDetails;
use app\web\validate\Size as SizeValidate;
use app\common\library\storage\Driver as FileDriver;

class Size extends Controller
{

    /**
     * Notes:获取尺寸列表
     * User:ccl
     * DateTime:2020/12/31 11:06
     * @param Request $request
     * @return Json
     */
    public function getSizeList(Request $request)
    {
        $size_model = new SizeModel();
        $com_id = $this->com_id;
        $data = $size_model->getList(['com_id' => $com_id], '*', 'sort asc');
        $data = createTree($data->toArray(), 'size_id', 'size_group');
        return Json($data);
    }

    public function loadTreeList()
    {
        $size_model = new SizeModel();
        $com_id = $this->com_id;
        $data = $size_model->getList(['com_id' => $com_id], 'size_id as id,size_name as text,size_group,sort,lock_version', 'sort asc');
        $data = createTree($data->toArray(), 'id', 'size_group');
        return Json($data);
    }

    public function loadCombobox()
    {
        $size_model = new SizeModel();
        return json($size_model->getList($this->getData(["size_group"=>["neq", 0]]), "size_id, size_name", "sort asc"));
    }

    /**
     * Notes:获取分组
     * User:ccl
     * DateTime:2020/12/31 11:45
     * @return Json
     */
    public function getGroupList(Request $request)
    {
        $size_model = new SizeModel();
        $com_id = $this->com_id;
        $data = $size_model->getList(['com_id' => $com_id, 'size_group' => 0], '*', 'sort asc');
        return Json($data);
    }

    /**
     * Notes:删除尺寸节点
     * User:ccl
     * DateTime:2020/12/31 15:12
     * @param Request $request
     * @return array
     */
    public function deletSize(Request $request)
    {
        $data = $request->post();
        $size_model = new SizeModel();
        if (!empty($size_model->getOne(['size_group' => $data['size_id']], '*'))) {
            return $this->renderError('请先删除子节点!');
        }
        // 确定是否在使用
        $goodsInfo = (new GoodsDetails())->getOne(['size_id' => $data['size_id']], 'details_id');
        if ($goodsInfo) {
            return $this->renderError('已经在使用的尺寸不允许删除');
        }
        $res = $size_model->where(['size_id' => $data['size_id']])->delete();
        if ($res) {
            return $this->renderSuccess('', '删除成功！');
        }
        return $this->renderError('删除失败');
    }

    /**
     * Notes:更新尺寸
     * User:ccl
     * DateTime:2021/1/4 15:36
     * @param Request $request
     * @return array
     */
    public function editSize(Request $request)
    {
        $data = $request->post();
        $size_model = new SizeModel();
        $size_parent = $data['size_group'];
        $size_name = $data['size_name'];
        $size_id = $data['size_id'];
        $size_sort = $data['sort'];
        $lock_version = $data['lock_version'];
        $com_id = $this->com_id;
        if ($size_parent == 'top') {
            $res = $size_model->addParent($size_name, $com_id, $size_sort);
            return $res ? $this->renderSuccess('', '添加成功') : $this->renderError('添加失败');
        }

        //size_id为空则新增 不为空则为更新
        if (!empty($size_id)) {
            $res = $size_model->where(['size_id' => $size_id, 'lock_version' => $lock_version])->inc('lock_version')->save(['size_name' => $size_name, 'sort' => $size_sort]);
            return $res ? $this->renderSuccess('', '更新成功') : $this->renderError('更新失败');
        } else {
            $res = $size_model->addSon($size_parent, $size_name, $com_id, $size_sort);
            return $res ? $this->renderSuccess('', '添加尺寸成功') : $this->renderError('添加尺寸失败');
        }
    }

    public function updateSize(Request $request)
    {
        $data = $request->post();
        $validate = new SizeValidate();
        if (!$validate->scene('edit')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $size_model = new SizeModel();
        // 添加
        if (!$data['size_id']) {
            //确定上级是否存在
            if ($data['size_group'] != 0) {
                $groupInfo = $size_model->getOne(['com_id' => $this->com_id, 'size_id' => $data['size_group']], 'size_id');
                if (!$groupInfo) {
                    return $this->renderError('父级不存在');
                }
            }
            $size_model->save([
                'com_id' => $this->com_id,
                'size_group' => $data['size_group'],
                'size_name' => $data['size_name'],
                'sort' => $data['sort'],
            ]);

            return $this->renderSuccess([], '尺寸添加成功');
        }
        // 修改
        $info = $size_model->getOne(['com_id' => $this->com_id, 'size_id' => $data['size_id']]);
        if (!$info) {
            return $this->renderError('尺寸信息不存在');
        }
        // lock_version
        if ($info['lock_version'] != $data['lock_version']) {
            return $this->renderError('版本信息已过期,请重新提交');
        }
        if ($info['size_group'] != $data['size_group']) {
            return $this->renderError('不允许更改上级');
        }
        $info->save([
            'size_name' => $data['size_name'],
            'sort' => $data['sort'],
            'lock_version' => ['inc', 1],
        ]);
        return $this->renderSuccess([], '尺寸信息修改成功');
    }




}