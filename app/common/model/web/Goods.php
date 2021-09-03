<?php


namespace app\common\model\web;

use app\common\model\BaseModel;


class Goods extends BaseModel
{
    protected $table = 'hr_goods';
    protected $pk = 'goods_id';

    public function images()
    {
        return $this->hasMany(GoodsAssist::class,'goods_id', 'goods_id')
            ->where(['assist_category' => 'image', 'assist_status' => 1])
            ->order('assist_sort asc update_time desc');
    }

    public function detail()
    {
        return $this->hasMany(GoodsDetail::class,'goods_id', 'goods_id')
            ->alias('d')
            ->join('hr_color c','c.color_id = d.color_id', 'left')
            ->join('hr_size s','s.size_id = d.size_id', 'left')
            ->field('d.*,c.color_name,s.size_name');
    }
}