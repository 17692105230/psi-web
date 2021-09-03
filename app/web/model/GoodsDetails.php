<?php
/**
 * Created by PhpStorm.
 * User: YB02
 * Date: 2021/1/5
 * Time: 14:20
 */

namespace app\web\model;

use app\common\model\web\GoodsDetails as GoodsDetailsModel;
use think\Exception;

class GoodsDetails extends GoodsDetailsModel
{
    public function saveDetails($rows, $com_id, $goods_code, $goods_id)
    {
        $item = [
            'goods_code' => $goods_code,
            'goods_id' => $goods_id,
            'color_id' => $rows['color_id'],
            'size_id' => $rows['size_id'],
            'history_number' => 0,
            'goods_scode' => $rows['goods_scode'],
            'goods_sbarcode' => $rows['goods_sbarcode'],
            'lock_version' => 0,
            'com_id' => $com_id,
            'create_time' => time(),
            'update_time' => time()
        ];
        return $this->insert($item);
    }

    public function getSizeDetail($where)
    {
        return $this->alias('d')
            ->join('hr_size s', 's.size_id = d.size_id', 'left')
            ->where('d.goods_code', $where['goods_code'])
            ->where('d.com_id', $where['com_id'])
            ->field('d.size_id, s.size_name')
            ->group("d.size_id")
            ->select();
    }

    public function getColorDetail($where)
    {
        return $this->alias('d')
            ->join('hr_color c', 'c.color_id = d.color_id', 'left')
            ->where('d.goods_code', $where['goods_code'])
            ->where('d.com_id', $where['com_id'])
            ->field('d.color_id, c.color_name')
            ->group("d.color_id")
            ->select();
    }

    /**
     * @desc  添加商品详情
     * @param $data
     * @return int|string
     * Date: 2021/1/21
     * Time: 11:28
     * @author myy
     */
    public function createDetails($data)
    {
        $this->checkRepeat($data);
        $item = [
            'goods_code' => $data['goods_code'],
            'goods_id' => $data['goods_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'history_number' => 0,
            'goods_scode' => $data['goods_scode'],
            'goods_sbarcode' => $data['goods_sbarcode'],
            'com_id' => $data['com_id']
        ];
        return $this->save($item);
    }

    public function checkRepeat($data)
    {
        // 单品货号不允许重复
        $info = $this->getOne(['com_id' => $data['com_id'], 'goods_scode'=> $data['goods_scode']], 'com_id');
        if ($info) {
            throw new  Exception('单品货号:'.$data['goods_scode'].'已经存在');
        }
    }
}