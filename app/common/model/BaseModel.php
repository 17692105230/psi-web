<?php


namespace app\common\model;

use think\model;


class BaseModel extends model
{
    // 错误信息
    protected $error;

    public function getOne($where, $field = '*')
    {
        return $this->where($where)->field($field)->find();
    }

    public function getList($where, $field = '*', $order = 'create_time desc')
    {
        return $this->field($field)->where($where)->order($order)->select();
    }

    public function getError($default = '')
    {
        if (!empty($this->error)) {
            return $this->error;
        }
        return $default;
    }

    public function setError($error)
    {
        if (empty($this->error)) {
            $this->error = $error;
        } else {
            $this->error = $this->error . '/' . $error;
        }
    }

    public function easyPaginate($where, $order = 'create_time desc', $field = '*', $number = 'total', $data = 'rows')
    {
        $page = request()->param('page', 1);
        $limit = request()->param('rows', 15);
        $offset = ($page - 1) * $limit;
        $rows = $this->where($where)->order($order)->limit($offset, $limit)->field($field)->select();
        $total = $this->where($where)->count();
        return [
            $number => $total,
            $data => $rows
        ];
    }



}