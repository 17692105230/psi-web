<?php


namespace app\common\model\web;


use app\common\model\BaseModel;

class TransferOrders extends BaseModel
{
    protected $table = 'hr_transfer_orders';
    protected $pk = 'orders_id';

    public function details()
    {
        return $this->hasMany(TransferOrdersDetails::class, 'orders_id', $this->pk)
            ->alias('d')
            ->join('hr_size s', 's.size_id=d.size_id', 'left')
            ->join('hr_color c', 'c.color_id=d.color_id', 'left')
            ->join('hr_goods g', 'g.goods_code=d.goods_code', 'left')
            ->field('d.*,s.size_name,c.color_name,g.goods_name,g.goods_barcode') ;
    }


}