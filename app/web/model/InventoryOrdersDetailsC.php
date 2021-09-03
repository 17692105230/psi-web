<?php


namespace app\web\model;


class InventoryOrdersDetailsC extends \app\common\model\web\InventoryOrdersDetailsC
{
    public function loadDetailList($data,$userInfo) {
        $res = $this->alias("dc")
            ->where("dc.com_id", $data["com_id"])
            ->where("dc.user_id", $userInfo["user_id"])
            ->leftJoin("hr_goods g", "g.goods_id=dc.goods_id")
            ->leftJoin("hr_goods_details gd", "gd.goods_id=dc.goods_id and dc.color_id=gd.color_id and dc.size_id=gd.size_id")
            ->leftJoin("hr_color color", "gd.color_id=color.color_id")
            ->leftJoin("hr_size size", "gd.size_id=size.size_id")
            ->where("dc.orders_code", $data["orders_code"])
            ->field("dc.*, g.goods_name,g.goods_barcode,color.color_name,size.size_name")
            ->paginate()->toArray();
        return $res;
    }

}