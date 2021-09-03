<?php


namespace app\web\model;

use app\common\model\web\Dict as DictModel;
use app\web\model\Goods;

class Dict extends DictModel
{

    protected $readonly = ['dict_id', 'com_id', 'dict_type'];

    public function edit($data)
    {
        $info = $this->canEdit($data['dict_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        // 版本 +1
        $data['lock_version'] = $info->lock_version + 1;
        return $info->save($data);
    }

    public function del($data)
    {
        $info = $this->canEdit($data['dict_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        // 没有使用过允许删除
       $goodsInfo =(new Goods())->whereOr('brand_id', $data['dict_id'])
            ->whereOr('brand_id', $data['dict_id'])
            ->whereOr('material_id', $data['dict_id'])
            ->whereOr('unit_id', $data['dict_id'])
            ->whereOr('goods_season', $data['dict_id'])
            ->whereOr('style_id', $data['dict_id'])
           ->field('goods_id')->find();
        if (!$goodsInfo) {
            return $info->delete();
        }
        // 已经使用不允许删除
        $this->setError('已经在使用的数据不允许删除');
        return false;
    }

    public function canEdit($dict_id, $com_id, $lock_version)
    {
        $info = $this->getOne(['dict_id' => $dict_id, 'com_id' => $com_id]);

        if (empty($info)) {
            $this->error = '信息不存在';
            return false;
        }

        if ($info->dict_disabled) {
            $this->error = '信息不可编辑';
            return false;
        }

        if ($info->lock_version != $lock_version) {
            $this->error = '信息不是最新版本';
            return false;
        }

        return $info;
    }




}