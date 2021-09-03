<?php


namespace app\web\model;


use app\common\model\web\Category as CategoryModel;
use app\web\model\Goods;

class Category extends CategoryModel
{

    protected $readonly = ['com_id'];

    public function loadTreeGrid($where)
    {
        $data = $this->getList($where,'category_name as text,category_id as id,category_pid,sort,lock_version','sort asc')->toArray();
        //转为树
        return createTree($data, 'id', 'category_pid');
    }


    public function edit($data)
    {
        $info = $this->canEdit($data['category_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        // 版本 +1
        $data['lock_version'] = $info->lock_version + 1;
        return $info->save($data);
    }

    public function del($data)
    {
        $info = $this->canEdit($data['category_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        // 有下级不允许删除
        $sonCate = $this->where('category_pid', $info['category_id'])->find();
        if ($sonCate) {
            $this->setError('该分类存在子类不允许直接删除');
            return false;
        }
        // 确定是否被使用过使用过软删除
        $goodsInfo = (new Goods())->getOne(['category_id' => $info['category_id']],'goods_id');
        if (!$goodsInfo) {
            return $info->delete();
        }
        $this->setError('已经存在不允许删除');
        return false;
        // 真实删除
        //return $info->force()->delete();

    }

    public function canEdit($category_id, $com_id, $lock_version)
    {
        $info = $this->getOne(['category_id' => $category_id, 'com_id' => $com_id]);

        if (empty($info)) {
            $this->error = '信息不存在';
            return false;
        }

        if ($info->lock_version != $lock_version) {
            $this->error = '信息不是最新版本';
            return false;
        }

        return $info;
    }

    public function add($data)
    {
        return $this->save([
            'category_pid' => $data['category_pid'],
            'category_name' => $data['category_name'],
            'sort' => $data['sort'],
            'com_id' => $data['com_id'],
        ]);
    }

}