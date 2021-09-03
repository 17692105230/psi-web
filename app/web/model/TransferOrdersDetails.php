<?php


namespace app\web\model;

use app\common\model\web\TransferOrdersDetails as TransferOrdersDetailsModel;

class TransferOrdersDetails extends TransferOrdersDetailsModel
{

    public function add($data)
    {
        return $this->save([
            'orders_id' => $data['orders_id'],
            'orders_code' => $data['orders_code'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'out_warehouse_number' => $data['out_warehouse_number'],
            'in_warehouse_number' => $data['in_warehouse_number'],
            'goods_status' => isset($data['goods_status']) ? $data['goods_status'] :0,
            'com_id' => isset($data['com_id']) ? $data['com_id'] :0,

        ]);
    }
}