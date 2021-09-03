<?php


namespace app\web\model;

use app\common\model\web\Color as ColorModel;
class Color extends ColorModel
{
    /**
     * 函数功能描述 更新颜色表数据
     * @param $data
     * Date: 2021/1/4
     * Time: 11:28
     * @author gxd
     */
    public function updateColor($info, $data, $allow_field) {
        foreach ($allow_field as $field) {
            $info->$field = $data[$field];
        }
        $info->lock_version += 1;
        return $info->save();
    }

    /**
     * 函数描述:获取列表
     * @param $where
     * @param string $field
     * @param string $order
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * Date: 2021/1/8
     * Time: 10:50
     * @author mll
     */
    public function getColorList($where)
    {
        $data = $this->getList($where,'*','sort asc')->toArray();
        //转为树
        return createTree($data, 'color_id', 'color_group');
    }

    public function loadTreeList($where)
    {
        $data = $this->getList($where,'color_name as text,color_id as id,color_group,sort,lock_version','sort asc')->toArray();
        //转为树
        return createTree($data, 'id', 'color_group');
    }


    public function edit($data)
    {
        $info = $this->canEdit($data['color_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        // 不允许修改上级
        if ($info['color_group'] != $data['color_group']) {
            $this->setError('不允许更改上级节点');
            return false;
        }
        unset($data['color_group']);
        // 版本 +1
        $data['lock_version'] = $info->lock_version + 1;
        return $info->save($data);
    }


    public function del($data)
    {
        $info = $this->canEdit($data['color_id'], $data['com_id'], $data['lock_version']);
        if (!$info) {
            return false;
        }
        return $info->delete();
    }

    public function add($data)
    {
        return $this->save([
            'color_group' => $data['color_group'],
            'color_name' => $data['color_name'],
            'sort' => $data['sort'],
            'com_id' => $data['com_id'],
        ]);
    }





    public function canEdit($color_id, $com_id, $lock_version)
    {
        $info = $this->getOne(['color_id' => $color_id, 'com_id' => $com_id]);

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

    /**
     * 函数描述: 添加颜色
     * @param $data
     * @return bool
     * Date: 2020/12/31
     * Time: 14:24
     * @author mll
     */
    public function saveColor($data)
    {
        return $this->save($data);
    }

    /**
     * 函数描述:删除一行
     * @param $where
     * @return bool
     * Date: 2020/12/31
     * Time: 15:53
     * @author mll
     */
    public function delColor($where)
    {
        return $this->where($where)->delete();
    }

}