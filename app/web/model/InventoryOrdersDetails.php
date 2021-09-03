<?php


namespace app\web\model;

use app\common\model\web\InventoryOrdersDetails as InventoryOrdersDetailsModel;

class InventoryOrdersDetails extends InventoryOrdersDetailsModel
{
    //加载详细信息
    public function loadDetailList($data, $userInfo)
    {
        $status = isset($data["status"]) ? $data["status"] : 0;
        if ($status == 9) {//已完成
            $inventoryDetailsCModel = new InventoryOrdersDetailsC();
            $details = $inventoryDetailsCModel->loadDetailList($data,$userInfo);
        } else { //临时/草稿
            list($page, $rows) = pageOffset($data);
            $details = $this->alias("dt")
                ->where("dt.com_id", $data["com_id"])
                ->where("dt.orders_code", $data["orders_code"])
                ->where("dt.user_id", $userInfo["user_id"])
                ->where("dt.children_code", $data["children_code"])
                ->leftJoin("hr_inventory_orders_children ioc", "dt.orders_id=ioc.orders_id and dt.children_code=ioc.children_code")
                ->leftJoin("hr_inventory_orders io", "dt.orders_id=io.orders_id")
                ->leftJoin("hr_goods_stock stock", "dt.goods_id=stock.goods_id and dt.color_id=stock.color_id and dt.size_id=stock.size_id and io.warehouse_id=stock.warehouse_id")
                ->leftJoin("hr_goods goods", "dt.goods_id = goods.goods_id")
                ->leftJoin("hr_goods_details gd", "dt.goods_id=gd.goods_id and dt.color_id=gd.color_id and dt.size_id=gd.size_id")
                ->leftJoin("hr_color hc","dt.color_id=hc.color_id")
                ->leftJoin("hr_size hs", "dt.size_id=hs.size_id")
                ->when(isset($data["ws_goods"]) && !empty($data["ws_goods"]), function ($query) use ($data) {
                    $query->where("goods.com_id", $data["com_id"])
                        ->where(function ($query) use ($data) {
                            $query->whereOr("goods.goods_code", "like", "%" . $data["ws_goods"] . "%")
                                ->whereOr("goods.goods_name", "like", "%" . $data["ws_goods"] . "%")
                                ->whereOr("goods.goods_barcode", "like", "%" . $data["ws_goods"] . "%");
                        });
                })
                ->when(isset($data["ws_color_id"]) && !empty($data["ws_color_id"]), function ($query) use ($data) {
                    $query->where("dt.color_id", $data["ws_color_id"]);
                })
                ->when(isset($data["ws_size_id"]) && !empty($data["ws_size_id"]), function ($query) use ($data) {
                    $query->where("dt.size_id", $data["ws_size_id"]);
                })
                ->when(isset($data["ws_gain_loss"]) && !empty($data["ws_gain_loss"]), function ($query) use ($data) {
                    switch ($data["ws_gain_loss"]) {
                        case 1:
                            $query->whereRaw("(dt.goods_number - stock.stock_number) > 0");
                            break;
                        case 2:
                            $query->whereRaw("(dt.goods_number - stock.stock_number) = 0");
                            break;
                        case 3:
                            $query->whereRaw("(dt.goods_number - stock.stock_number) < 0");
                            break;
                    }
                })
                ->field("dt.details_id,dt.orders_code,dt.goods_code,dt.color_id,dt.size_id,dt.goods_number,ioc.children_code,ioc.user_id,IFNULL(stock.stock_number, 0) goods_anumber,(dt.goods_number-stock.stock_number) goods_lnumber, dt.goods_status,dt.create_time,dt.update_time,dt.lock_version,goods.goods_name,goods.goods_barcode,hc.color_name,hs.size_name")
                ->paginate($rows)->toArray();
        }
        return ["total"=>$details["total"],"rows"=>$details["data"]];
    }
}