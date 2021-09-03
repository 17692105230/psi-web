<?php


namespace app\web\logic;


use app\common\utils\BuildCode;
use app\web\model\Goods;
use app\web\model\GoodsStock;
use app\web\model\InventoryOrders;
use app\web\model\InventoryOrdersChildren;
use app\web\model\InventoryOrdersDetails;
use app\web\model\InventoryOrdersDetailsC;
use app\web\model\StockDiary;
use think\Facade\Db;
use think\Exception;

class StoreInventoryOrdersLogic extends BaseLogic
{


    /**
     * 新增商品到盘点单
     */
    public function baseSaveInventoryRow($orders, $userInfo)
    {
        //分离数据
        $rowsInsert = json_decode($orders["rows"], true);
        unset($orders["rows"]);
        $childrenCode = $orders['children_code'];
        unset($orders["children_code"]);
        $inventoryOrderModel = new InventoryOrders();
        //开启事务
        Db::startTrans();
        try {
            //新增单据
            if (!isset($orders["orders_code"]) || empty($orders["orders_code"])) {
                do {
                    $orders["orders_code"] = BuildCode::dateCode("SI");
                } while ($inventoryOrderModel->getFielValue(["orders_code" => trim($orders['orders_code']), "com_id" => $orders["com_id"]], "orders_id") !== null);
                $orders["create_time"] = time();
                $orders["update_time"] = time();
                $orders_id = $inventoryOrderModel->insertGetId($orders);
                if (!$orders_id) {
                    throw new Exception("保存库存盘点单，单据信息错误~~");
                }
                //保存字单据
                $childrenCode = $orders["user_id"] . '1';
                $childrenName = $userInfo["user_name"] . "(单据1)";
                $cldOrders = [
                    "orders_id" => $orders_id,
                    "serial_number" => 1,
                    "user_id" => $orders["user_id"],
                    "orders_code" => $orders["orders_code"],
                    "children_code" => $childrenCode,
                    "children_name" => $childrenName,
                    'orders_status' => 0,
                    "curr_use" => 1,
                    "com_id" => $orders["com_id"],
                    "create_time" => time(),
                    "update_time" => time(),
                ];
                $inventoryOrderChildrenModel = new InventoryOrdersChildren();
                $res = $inventoryOrderChildrenModel->save($cldOrders);
                if (!$res) {
                    throw new Exception("保存库存盘点单子单据错误~~");
                }
                $data["children_orders"] = [["children_code" => $cldOrders["children_code"], 'children_name' => $cldOrders['children_name']]];
                $data["children_use"] = $cldOrders["children_code"];
                $this->setResult($data);
            }
            $orders_info = $inventoryOrderModel->getOne(["orders_code" => trim($orders["orders_code"]), "com_id" => $orders["com_id"]]);
            if (count($rowsInsert) > 0) {
                $inventoryOrdersDetailModel = new InventoryOrdersDetails();
                $goodsModel = new Goods();
                foreach ($rowsInsert as $key => $row) {
                    $res = $inventoryOrdersDetailModel->where("orders_code", trim($orders["orders_code"]))
                        ->where("children_code", trim($childrenCode))
                        ->where("goods_code", $row["goods_code"])
                        ->where('color_id', $row["color_id"])
                        ->where("size_id", $row["size_id"])
                        ->where("com_id", $orders["com_id"])
                        ->where("user_id", $orders["user_id"])
                        ->inc("goods_number", $row["goods_number"])
                        ->update();
                    if (!$res) {
                        $goodsInfo = $goodsModel->getOne(["com_id" => $orders["com_id"], "goods_code" => $row["goods_code"]]);
                        $row['orders_code'] = trim($orders['orders_code']);
                        $row['children_code'] = $childrenCode;
                        $row['create_time'] = time();
                        $row['update_time'] = $row['create_time'];
                        $row["goods_id"] = $goodsInfo["goods_id"];
                        $row["com_id"] = $orders["com_id"];
                        $row["orders_id"] = $orders_info["orders_id"];
                        $row["user_id"] = $orders["user_id"];
                        $inventoryOrdersDetailModel->insert($row);
                    }
                }
            }
            Db::commit();
            //查询单据信息
            $inventOrderInfo["orders"] = $inventoryOrderModel->getOne(["orders_code" => trim($orders["orders_code"]), "com_id" => $orders["com_id"]])->toArray();
            $this->setResult($inventOrderInfo);
            return true;
        } catch (\Exception $e) {
            $this->setError($e->getMessage());
            $this->setError($e->getFile());
            $this->setError($e->getLine());
            Db::rollback();
            return false;
        }
    }

    /**
     * 删除订单
     */
    public function baseDelOrder($where)
    {
        $inventoryOrderModel = new InventoryOrders();
        $orderInfo = $inventoryOrderModel->where($where)->find();
        if (!$orderInfo || $orderInfo["orders_status"] == 9) {
            $this->setError("单据状态错误不允许操作~~");
            return false;
        }
        Db::startTrans();
        try {
            //
            $res = $inventoryOrderModel->where("orders_id", $orderInfo["orders_id"])->delete();
            if (false === $res) {
                throw new Exception("删除单据数据失败~~");
            }
            $inventoryOrdersDetailModel = new InventoryOrdersDetails();
            $res = $inventoryOrdersDetailModel->where("orders_id", $orderInfo["orders_id"])->delete();
            if (false === $res) {
                throw new Exception("删除单据商品数据失败~~");
            }
            //删除子单据
            $iocModel = new InventoryOrdersChildren();
            $iocModel->where("orders_id", $orderInfo["orders_id"])->delete();
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //删除盘点商品
    public function baseDelGoods($data)
    {
        $ids = explode(",", $data["ids"]);
        $ordersCode = $data["orders_code"];
        if (empty($ids)) {
            $this->setError('没有选择需要删除的商品~~');
            return false;
        }
        if (empty($ordersCode)) {
            $this->setError('单据编号错误~~');
            return false;
        }
        //查询订单状态
        $inventoryOrderModel = new InventoryOrders();
        $orderInfo = $inventoryOrderModel->where(["orders_code" => $ordersCode, "com_id" => $data["com_id"], "user_id" => $data["user_id"]])->find();
        if (!$orderInfo || $orderInfo["orders_status"] == 9) {
            $this->setError("盘点单状态信息错误~~");
            return false;
        }
        Db::startTrans();
        try {
            $inventoryOrderDetailsModel = new InventoryOrdersDetails();
            $res = $inventoryOrderDetailsModel->whereIn("details_id", $ids)->delete();
            if (false === $res) {
                $this->setError('删除单据中的商品失败~~');
                return false;
            }
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //删除子单据
    public function baseDelChildren($data)
    {
        $iocModel = new InventoryOrdersChildren();
        $childrenCode = $data["children_code"];
        unset($data["children_code"]);
        $cnt = $iocModel->where($data)->count();
        if ($cnt <= 1) {
            $this->setError("删除子单据错误，子单据不允许全部删除~~");
            return false;
        }
        $iodModel = new InventoryOrdersDetails();
        Db::startTrans();
        try {
            //删除子单据
            $res = $iocModel->where($data)->where("children_code", $childrenCode)->delete();
            if (false === $res) {
                throw new Exception("删除子单据失败~~");
            }
            //删除子单据对应数据(不一定会有数据，无需判断是否成功)
            $res = $iodModel->where($data)->where("children_code", $childrenCode)->delete();
            //查询当前是否还有在用的子单据，如果没有设置一个
            $res = $iocModel->where($data)->where("curr_use", 1)->count();
            if ($res < 1) {
                $res = $iocModel->where($data)->order("serial_number", "desc")->limit(1)->update(["curr_use" => 1]);
            }
            //查询子单据
            $childrenOrders = $iocModel->getList($data, "children_code,children_name");
            $childrenUse = $iocModel->getFieldValue(array_merge($data, ["curr_use" => 1]), "children_code");
            if (null == $childrenUse) {
                throw new Exception("删除子单据错误~~");
            }
            $this->setResult(["children_orders" => $childrenOrders, "children_use" => $childrenUse]);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //添加子单据
    public function baseAddChildren($data, $userInfo)
    {
        $iocModel = new InventoryOrdersChildren();
        $serialNumber = $iocModel->where($data)->max("serial_number");
        $serialNumber++;
        $childrenCode = $userInfo["user_id"] . $serialNumber;
        $childrenName = $userInfo["user_name"] . "(单据" . $serialNumber . ")";
        Db::startTrans();
        try {
            //更新当前在用子订单
            $iocModel->where($data)->update(["curr_use" => 0]);
            $cldOrders = [
                "serial_number" => $serialNumber,
                "user_id" => $userInfo["user_id"],
                "com_id" => $data["com_id"],
                "orders_code" => $data["orders_code"],
                "children_code" => $childrenCode,
                "children_name" => $childrenName,
                "curr_use" => 1,
                "create_time" => time(),
                "update_time" => time()
            ];
            //插入数据
            $res = $iocModel->insertGetId($cldOrders);
            if (!$res) {
                throw new Exception("保存库存盘点单子单据错误~~");
            }
            Db::commit();//查询子单据
            $childrenOrders = $iocModel->getList($data, "children_code,children_name");
            $childrenUse = $iocModel->getFieldValue(array_merge($data, ["curr_use" => 1]), "children_code");
            $this->setResult(["children_orders" => $childrenOrders, "children_use" => $childrenUse]);
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //更新商品数量
    public function baseSaveGoodsNumber($data)
    {
        $row_goods_number = $data["row_goods_number"];
        unset($data["row_goods_number"]);
        Db::startTrans();
        try {
            $iodModel = new InventoryOrdersDetails();
            $iodModel->where($data)->update(["goods_number" => $row_goods_number]);
            Db::commit();
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //保存订单
    public function baseSaveOrders($status, $data)
    {
        if (empty($data["orders_code"])) {
            $this->setError("错误");
            return false;
        }
        $ordersModel = new InventoryOrders();
        $dborders = $ordersModel->field("orders_id, orders_status, lock_version")
            ->where("orders_code", $data["orders_code"])
            ->where("com_id", $data["com_id"])
            ->where("user_id", $data["user_id"])
            ->find();
        if (!$dborders) {
            $this->setError("错误，没有找到单据信息~~");
            return false;
        }
        //检查订单状态
        if (9 == $dborders["orders_status"]) {
            $this->setError("已完成单据不允许操作~~");
            return false;
        }
        //如果订单状态和要更改的状态一致不做操作
        if ($dborders["orders_status"] == $status) {
            $this->setError("订单已是对应状态，无需再次操作~~");
            return false;
        }
        //检查锁版本
        if (!isset($data["lock_version"]) || $dborders["lock_version"] != $data["lock_version"]) {
            $this->setError("系统数据错误，数据已被其他用户更改，请重新获取~~");
            return false;
        }
        $childrenCode = $data['children_code'];
        unset($data["children_code"]);
        Db::startTrans();
        try {
            //更新单据信息
            $res = $ordersModel->exists(true)
                ->where("orders_code", trim($data["orders_code"]))
                ->where("user_id", $data["user_id"])
                ->where("com_id", $data["com_id"])
                ->save([
                    "orders_status" => $status,
                    "orders_remark" => $data["orders_remark"],
                    "update_time" => time(),
                    "lock_version" => Db::raw("lock_version+1")
                ]);
            //更新当前使用的子单据
            $iocModel = new InventoryOrdersChildren();
            $res_u_0 = $iocModel->exists(true)
                ->where("orders_code", trim($data["orders_code"]))
                ->where("com_id", $data["com_id"])
                ->where("curr_use", 1)
                ->where("user_id", $data["user_id"])
                ->save(["curr_use"=>0]);
            $res_u_1 = $iocModel->exists(true)
                ->where("orders_code", trim($data["orders_code"]))
                ->where("com_id", $data["com_id"])
                ->where("children_code", $childrenCode)
                ->where("user_id", $data["user_id"])
                ->save(["curr_use"=>1]);

            if (false === $res || false === $res_u_0 || false === $res_u_1) {
                throw new Exception("更新盘点单单据信息错误~~");
            }
            //如果是草稿
            $iodModel = new InventoryOrdersDetails();
            if ($status == 1) {
                /* 删除登录用户使用过的，没有保存的临时盘点单据 */
                $tmpOrders = $ordersModel->where("user_id", $data["user_id"])
                    ->where("orders_status", 0)
                    ->where("com_id", $data["com_id"])
                    ->select();
                foreach ($tmpOrders as $tmpInfo) {
                    //删除订单
                    $ordersModel->where('orders_id', $tmpInfo["orders_id"])->delete();
                    //删除details
                    $iodModel->where("orders_id", $tmpInfo["orders_id"])->delete();
                    $iocModel->where("orders_id", $tmpInfo["orders_id"])->delete();
                }
            } elseif ($status == 9) {
                /* 更新盘点前数量、盈亏数量到盘点单中 */
                $pdInfos = $iodModel->alias("iod")
                    ->where("iod.orders_code", $data["orders_code"])
                    ->where("iod.com_id", $data["com_id"])
                    ->where("iod.user_id", $data["user_id"])
                    ->group("iod.goods_code,iod.color_id,iod.size_id")
                    ->leftJoin("hr_inventory_orders orders", "iod.orders_id=orders.orders_id")
                    ->leftJoin("hr_goods_stock stock", "iod.goods_code=stock.goods_code and iod.color_id=stock.color_id and iod.size_id=stock.size_id and orders.warehouse_id=stock.warehouse_id")
                    ->leftJoin("hr_goods goods", "iod.goods_id=goods.goods_id")
                    ->field("iod.orders_code,iod.goods_id,orders.warehouse_id,iod.orders_id, iod.goods_code, iod.color_id, iod.size_id, sum(iod.goods_number) as goods_number,iod.create_time,IFNULL(stock.stock_number, 0) AS goods_anumber,IFNULL((iod.goods_number - IFNULL(stock.stock_number, 0)),0) as goods_lnumber,CONVERT((iod.goods_number-IFNULL(stock.stock_number, 0)) * goods.goods_pprice, DECIMAL(10,2)) as goods_lmoney")
                    ->select();
                $iodcModle = new InventoryOrdersDetailsC();
                foreach ($pdInfos as $pdInfo) {
                    $dc_data["orders_code"] = $pdInfo["orders_code"];
                    $dc_data["goods_code"] = $pdInfo["goods_code"];
                    $dc_data["color_id"] = $pdInfo["color_id"];
                    $dc_data["size_id"] = $pdInfo["size_id"];
                    $dc_data["orders_id"] = $pdInfo["orders_id"];
                    $dc_data["goods_id"] = $pdInfo["goods_id"];
                    $dc_data["user_id"] = $data["user_id"];
                    $dc_data["com_id"] = $data["com_id"];
                    $dc_data["warehouse_id"] = $pdInfo["warehouse_id"];
                    $dc_data["goods_number"] = $pdInfo["goods_number"];
                    $dc_data["goods_anumber"] = $pdInfo["goods_anumber"];
                    $dc_data["goods_lnumber"] = $pdInfo["goods_lnumber"];
                    $dc_data["goods_lmoney"] = $pdInfo["goods_lmoney"];
                    $dc_data["create_time"] = $pdInfo["create_time"];
                    $res = $iodcModle->insertGetId($dc_data);
                    if (!$res) {
                        throw new Exception("分析盘点单商品信息错误~~");
                    }
                }
                /* 更新盘点单商品数量到库存 */
                $odkcInfos = $iodcModle->alias("d")
                    ->where("d.orders_code", $data["orders_code"])
                    ->where("d.com_id", $data["com_id"])
                    ->where("d.user_id", $data["user_id"])
                    ->where("d.warehouse_id", $data["warehouse_id"])
                    ->whereRaw("d.details_id is not null")
                    ->rightJoin("hr_goods_stock stock", "d.warehouse_id=stock.warehouse_id and d.goods_code=stock.goods_code and d.color_id=stock.color_id and d.size_id=stock.size_id")
                    ->field("stock.stock_id,d.details_id,d.warehouse_id,d.orders_code,d.goods_code,d.color_id,d.size_id,d.goods_number,d.create_time")
                    ->select();
                $gsModel = new GoodsStock();
                foreach ($odkcInfos as $odkcInfo) {
                    $res = $gsModel->exists(true)->where(["stock_id" => $odkcInfo["stock_id"]])->save(["stock_number" => $odkcInfo["goods_number"]]);
                    if (!$res) {
                        throw new Exception("更新盘点单商品数据到库存错误~~");
                    }
                }
                /* 新增库存不存在但盘点单中出现的商品 */
                $kcnpdyInfos = $ordersModel->alias("o")
                    ->where("o.orders_code", $data["orders_code"])
                    ->where("o.warehouse_id", $data["warehouse_id"])
                    ->where("o.com_id", $data["com_id"])
                    ->where("o.user_id", $data["user_id"])
                    ->whereRaw("stock.stock_id is null")
                    ->leftJoin("hr_inventory_orders_details d", "d.orders_id=o.orders_id")
                    ->leftJoin("hr_goods_stock stock", "o.warehouse_id=stock.warehouse_id and d.goods_code=stock.goods_code and d.color_id=stock.color_id and d.size_id=stock.size_id")
                    ->field("o.warehouse_id, d.goods_id, d.goods_code,d.color_id,d.size_id,d.goods_number stock_number")
                    ->select();
                foreach ($kcnpdyInfos as $kcnpdyInfo) {
                    $stock_data["warehouse_id"] = $kcnpdyInfo["warehouse_id"];
                    $stock_data["goods_code"] = $kcnpdyInfo["goods_code"];
                    $stock_data["goods_id"] = $kcnpdyInfo["goods_id"];
                    $stock_data["color_id"] = $kcnpdyInfo["color_id"];
                    $stock_data["size_id"] = $kcnpdyInfo["size_id"];
                    $stock_data["stock_number"] = $kcnpdyInfo["stock_number"];
                    $stock_data["com_id"] = $data["com_id"];
                    $stock_data["create_time"] = time();
                    $res = $gsModel->insertGetId($stock_data);
                    if (!$res) {
                        throw new Exception("更新盘点单商品数据到库存错误~~");
                    }
                }
                /* 更新统计单据商品数量、金额 */
                $res = $iodcModle->field("sum(goods_number) goods_number, sum(goods_lnumber) goods_plnumber, sum(goods_lmoney) goods_plmoney")
                    ->where("orders_code", $data["orders_code"])
                    ->where("com_id", $data["com_id"])
                    ->where("user_id", $data["user_id"])
                    ->find();
                if ($res) {
                    $ordersModel->where("orders_code", $data["orders_code"])
                        ->where("com_id", $data["com_id"])
                        ->where("user_id", $data["user_id"])
                        ->update(["goods_number" => $res["goods_number"], "goods_plnumber" => $res["goods_plnumber"]]);
                }
                $this->baseSaveOrdersToDiary($data);
            }
            Db::commit();
            $this->setResult(["orders_code" => $data["orders_code"], "lock_version" => $data["lock_version"]]);
            return true;
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
            $this->setError($e->getMessage());
            return false;
        }
    }

    /**
     * 保存盘点单到库存流水
     */
    private function baseSaveOrdersToDiary($data)
    {
        $iodcModel = new InventoryOrdersDetailsC();
        $pdorders = $iodcModel->alias("d")
            ->where("d.orders_code", $data["orders_code"])
            ->where("d.com_id", $data["com_id"])
            ->where("d.user_id", $data["user_id"])
            ->leftJoin("hr_goods_stock stock", "stock.warehouse_id=d.warehouse_id and d.goods_code=stock.goods_code and d.color_id=stock.color_id and d.size_id=stock.size_id")
            ->field("d.warehouse_id,d.orders_code,d.goods_code,d.color_id,d.size_id,d.goods_number,stock.stock_number,d.create_time")
            ->select();
        $sdModel = new StockDiary();
        foreach ($pdorders as $pdo) {
            $pdo_data["warehouse_id"] = $pdo["warehouse_id"];
            $pdo_data["orders_code"] = $pdo["orders_code"];
            $pdo_data["goods_code"] = $pdo["goods_code"];
            $pdo_data["color_id"] = $pdo["color_id"];
            $pdo_data["size_id"] = $pdo["size_id"];
            $pdo_data["goods_number"] = $pdo["goods_number"];
            $pdo_data["stock_number"] = $pdo["stock_number"];
            $pdo_data["create_time"] = $pdo["create_time"];
            $pdo_data["orders_type"] = "盘点单";
            $pdo_data["com_id"] = $data["com_id"];
            $res = $sdModel->insertGetId($pdo_data);
            if (!$res) {
                throw new Exception("保存盘点单流水账错误~~");
            }
        }
    }

    //修改库存商品信息
    public function saveGoodsColorSize($data)
    {
        //查询是否存在重复记录
        $iodModel = new InventoryOrdersDetails();
        $info = $iodModel->where("orders_code", $data["orders_code"])
            ->where("children_code", $data["children_code"])
            ->where("goods_code", $data["goods_code"])
            ->where("color_id", $data["color_id"])
            ->where("size_id", $data["size_id"])
            ->where("com_id", $data["com_id"])
            ->where("user_id", $data["user_id"])
            ->where("details_id", "<>", $data["details_id"])
            ->find();
        if ($data["tag"] == 0 && $info != null) {
            $this->setError("记录重复是否要合并？");
            $this->setErrorCode(7);
            return false;
        }
        $repeat = 0;
        if ($info) {
            $repeat = 1;
            Db::startTrans();
            try {
                $iodModel->where("children_code", $data["children_code"])
                    ->where("goods_code", $data["goods_code"])
                    ->where("color_id", $data["color_id"])
                    ->where("size_id", $data["size_id"])
                    ->where("com_id", $data["com_id"])
                    ->where("user_id", $data["user_id"])
                    ->inc("goods_number", $data["goods_number"])
                    ->update();
                $iodModel->where("details_id", $data["details_id"])->where("com_id", $data["com_id"])->where("user_id", $data["user_id"])->delete();
                Db::commit();
            } catch (\Exception $e) {
                Db::rollback();
                $this->setError($e->getMessage());
                return false;
            }
        } else {
            if ($data["t"] == 1) {
                $iodModel->where("details_id", $data["details_id"])->where("com_id", $data["com_id"])->where("user_id", $data["user_id"])->update(["color_id" => $data["color_id"]]);
            } else {
                $iodModel->where("details_id", $data["details_id"])->where("com_id", $data["com_id"])->where("user_id", $data["user_id"])->update(["size_id" => $data["size_id"]]);
            }
        }
        $this->setResult(["repeat" => $repeat]);
        return true;
    }

}