<?php


namespace app\web\logic;

use app\web\model\GoodsStock;
use app\web\model\TransferOrdersDetails;
use think\Facade\Db;
use app\web\model\TransferOrdersDetails as DetailsModel;
use app\web\model\TransferOrders as OrdersModel;
use app\web\validate\TransferOrdersValidate;
use app\web\model\GoodsStock as GoodsStockModel;
use app\common\utils\BuildCode;


class StoreTransferOrdersLogic extends BaseLogic
{

    public function baseSaveOrders($status, $orders)
    {
        $validate = new TransferOrdersValidate();
        if (!$validate->scene('save_orders')->check($orders)) {
            $this->setError($validate->getError());
            return false;
        }
        /* 分离数据 */
        $rowsInsert = json_decode($orders['data_insert'], true);
        $rowsUpdate = json_decode($orders['data_update'], true);
        $rowsDelete = json_decode($orders['data_delete'], true);
        $orders['orders_date'] = strtotime($orders['orders_date']);
        $orders['orders_status'] = $status;
        unset($orders['data_insert']);
        unset($orders['data_update']);
        unset($orders['data_delete']);
        $orderModel = new OrdersModel();
        // 新增单据
        if (empty($orders['orders_code'])) {
            unset($orders['orders_id']);
            /*--处理采购计划单号--*/
            do {
                $orders['orders_code'] = BuildCode::dateCode('SO');
            } while($orderModel->where('orders_code',$orders['orders_code'])->where("com_id", $orders["com_id"])->limit(1)->value('orders_id') !== null);
            $orderInfo = $orderModel;
        } else {
            $orderInfo = $orderModel->getDetail(['com_id' => $orders['com_id'], 'orders_code' => $orders['orders_code']]);
            /* 查询单据真实性 */
            if (empty($orderInfo)) {
                $this->setError('错误，没有找到单据信息~~');
                return false;
            }
            /* 查询单据状态 */
            if ($orderInfo['orders_status'] == 9) {
                $this->setError('已完成单据不允许操作~~');
                return false;
            }
            /* 查看锁版本是否正确 */
            if ($orderInfo['lock_version'] != $orders['lock_version']) {
                $this->setError('系统数据错误，数据已被其他用户更改，请重新获取~~');
                return false;
            }
            $orders["orders_id"] = $orderInfo["orders_id"];
        }

        $orders["status"] = $status;
        return $this->baseSaveOrdersCore($orderInfo, $orders, $rowsInsert, $rowsUpdate, $rowsDelete);
    }

    private function baseSaveOrdersCore($orderModel, $orders, $rowsInsert = [], $rowsUpdate = [], $rowsDelete = [])
    {
        try {
            Db::startTrans();
            // 添加或者修改
            $orderModel->saveData($orders);
            $out_warehouse_id = $orders['out_warehouse_id'];
            $in_warehouse_id = $orders['in_warehouse_id'];
            $detailModel = new DetailsModel();
            $goodsStockModel = new GoodsStockModel();
            // 插入详情
            if (count($rowsInsert) > 0) {
                foreach ($rowsInsert as $item_insert) {
                    $item_insert['orders_id'] = $orderModel['orders_id'];
                    $item_insert['orders_code'] = $orderModel['orders_code'];
                    $item_insert['com_id'] = $orderModel['com_id'];
                    // 查询当前调出仓库数量
                    $out_stock_info = $goodsStockModel->getOne([
                        'warehouse_id' => $out_warehouse_id,
                        'goods_code' => $item_insert['goods_code'],
                        'color_id' => $item_insert['color_id'],
                        'size_id' => $item_insert['size_id'],
                        'com_id' => $orders['com_id']
                    ], 'stock_number');
                    $in_stock_info = $goodsStockModel->getOne([
                        'warehouse_id' => $in_warehouse_id,
                        'goods_code' => $item_insert['goods_code'],
                        'color_id' => $item_insert['color_id'],
                        'size_id' => $item_insert['size_id'],
                        'com_id' => $orders['com_id']
                    ], 'stock_number');
                    $item_insert['in_warehouse_number'] = $in_stock_info ? $in_stock_info['stock_number'] : 0;
                    $item_insert['out_warehouse_number'] = $out_stock_info ? $out_stock_info['stock_number'] : 0;
                    $res1 = $detailModel->add($item_insert);
                }
            }
            // 修改
            if (count($rowsUpdate) > 0) {
                foreach ($rowsUpdate as $item_update) {
                    // 查询当前调出仓库数量
                    $out_stock_info = $goodsStockModel->getOne([
                        'warehouse_id' => $out_warehouse_id,
                        'goods_code' => $item_update['goods_code'],
                        'color_id' => $item_update['color_id'],
                        'size_id' => $item_update['size_id'],
                        'com_id' => $orders['com_id']
                    ], 'stock_number');
                    $in_stock_info = $goodsStockModel->getOne([
                        'warehouse_id' => $in_warehouse_id,
                        'goods_code' => $item_update['goods_code'],
                        'color_id' => $item_update['color_id'],
                        'size_id' => $item_update['size_id'],
                        'com_id' => $orders['com_id']
                    ], 'stock_number');

                    $item['in_warehouse_number'] = $in_stock_info ? $in_stock_info['stock_number'] : 0;
                    $item['out_warehouse_number'] = $out_stock_info ? $out_stock_info['stock_number'] : 0;
                    $detailModel->where([
                        'orders_code' => $orderModel['orders_code'],
                        'details_id' => $item_update['details_id']
                    ])->update($item_update);
                }
            }
            // 删除
            if (count($rowsDelete) > 0) {
                foreach ($rowsDelete as $item_delete) {
                    $detailModel->where([
                        'orders_code' => $orderModel['orders_code'],
                        'details_id' => $item_delete['details_id']
                    ])->delete();
                }
            }
            $currentData = $orderModel->getDetail(['orders_code' => $orders['orders_code'],"com_id"=>$orders["com_id"]]);
            // 保存为正式
            if ($orderModel['orders_status'] == 9) {
                $goodsStockModel = new GoodsStockModel();
                foreach ($currentData['details'] as $temp) {
                    //查询商品id
                    $gstockInfo = $goodsStockModel->where("goods_code")->where("com_id")->find();
                    $in_data = $out_data = [
                        'com_id' => $temp['com_id'],
                        'size_id' => $temp['size_id'],
                        'color_id' => $temp['color_id'],
                        'orders_code' => $temp['orders_code'],
                        'goods_code' => $temp['goods_code'],
                        'goods_id'=>$gstockInfo["goods_id"]
                    ];
                    $in_data['warehouse_id'] = $currentData['in_warehouse_id'];
                    $in_data['stock_number'] = $temp['goods_number'];
                    $goodsStockModel->updateOrInsert($in_data, '调拨单');
                    $out_data['warehouse_id'] = $temp['out_warehouse_id'];
                    $out_data['stock_number'] = $temp['goods_number'] * (-1);
                    $goodsStockModel->updateOrInsert($out_data, '调拨单');
                }
            }
            $this->setResult(['orders' => $currentData]);
            Db::commit();
            return true;
        } catch (\Exception $exception) {
            Db::rollback();
            $this->setError($exception->getMessage());
            $this->setError($exception->getFile());
            $this->setError($exception->getLine());
            return false;
        }
    }
}