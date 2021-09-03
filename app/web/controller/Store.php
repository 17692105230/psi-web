<?php

namespace app\web\controller;

use app\web\logic\StoreInventoryOrdersLogic;
use app\web\model\GoodsStock as GoodsStockModel;
use app\Request;
use app\web\model\InventoryOrders;
use app\web\model\InventoryOrdersChildren;
use app\web\model\InventoryOrdersDetails;
use app\web\model\TransferOrders;
use app\web\model\Size as SizeModel;
use app\web\model\Color as ColorModel;
use app\web\logic\StoreTransferOrdersLogic;
use app\web\model\Organization as OrganizationModel;
use app\web\validate\Store as StoreValidate;
use app\web\model\StockDiary as StockDiaryModel;

/**
 * 库存管理
 * Class Store
 * @package app\web\controller
 */
class Store extends Controller
{

    public function loadWarehouseList()
    {
        return json(json_decode('[{"org_id":19,"org_pid":2,"org_name":"鹰城世贸仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":17,"update_time":1589897831},{"org_id":20,"org_pid":23,"org_name":"丹尼斯店仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":7,"update_time":1522574170},{"org_id":21,"org_pid":24,"org_name":"东安路仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817},{"org_id":22,"org_pid":24,"org_name":"黄楝树仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817},{"org_id":23,"org_pid":24,"org_name":"郑州华联仓库","org_head":"张志超","org_phone":"15836986691","org_type":2,"org_status":1,"org_sort":100,"lock_version":2,"update_time":1522573817}]'));
    }

    public function queryGoods(Request $request)
    {
        $page = $request->get('page', 1);
        $rows = $request->get('rows', 30);
        $where = $request->get();
        $goods_stock = new GoodsStockModel();
        $res = $goods_stock->getGoodsList($this->getData($where), $page, $rows);
        //组合 所有颜色和尺寸
        $rows = $res['rows']->toArray();
        $total = $res['total'];
        $size_list = [];
        $color_list = [];
        foreach ($rows as &$row) {
            $temp = $goods_stock->getSizeColor($row['warehouse_id'], $row['goods_code']);
            $row['color_ids'] = $temp['color'];
            $row['size_ids'] = $temp['size'];
            if ($temp['color']) {
                $color_list = array_merge($color_list, $temp['color']);
            }
            if ($temp['size']) {
                $size_list = array_merge($size_list, $temp['size']);
            }
        }
        $size_name_list = (new SizeModel())->whereIn('size_id', array_unique($size_list))->column('size_name', 'size_id');
        $color_name_list = (new ColorModel())->whereIn('color_id', array_unique($color_list))->column('color_name', 'color_id');
        foreach ($rows as &$row) {
            $row_color_name = [];
            $row_size_name = [];
            $row['color_ids'] = array_unique($row['color_ids']);
            foreach ($row['color_ids'] as $one_color) {
                if (isset($color_name_list[$one_color])) {
                    $row_color_name[] = $color_name_list[$one_color];
                }
            }
            $row['size_ids'] = array_unique($row['size_ids']);
            foreach ($row['size_ids'] as $one_size) {
                if (isset($size_name_list[$one_size])) {
                    $row_size_name[] = $size_name_list[$one_size];
                }
            }
            $row['row_color_name'] = $row_color_name;
            $row['row_size_name'] = $row_size_name;
        }

        return json(compact('total', 'rows'));
    }

    public function queryDetailGoods(Request $request)
    {
        $goods_code = $request->get('goods_code');
        $warehouseId = $request->get('warehouse_id');
        $goods_stock = new GoodsStockModel();
        $res = $goods_stock->getGoodsStockDetail($goods_code, $warehouseId);
        return json($res);
    }

    /**
     * 保存调拨单（草稿）
     */
    public function saveTransferRoughDraft()
    {
        $logic = new StoreTransferOrdersLogic();
        $data = $this->request->post();
        $data['user_id'] = $this->user['user_id'];
        $res = $logic->baseSaveOrders(0, $this->getData($data));
        if (!$res) {
            return $this->renderError($logic->getError('保存失败'));
        }

        return $this->renderSuccess($logic->getResult());
    }

    public function saveTransferFormally()
    {
        $logic = new StoreTransferOrdersLogic();
        $data = $this->request->post();
        $data = $this->getData($data);
        $data['user_id'] = $this->user['user_id'];
        $res = $logic->baseSaveOrders(9, $data);
        if (!$res) {
            return $this->renderError($logic->getError('保存失败'));
        }

        return $this->renderSuccess($logic->getResult('orders'));
    }

    /**
     * @desc 库存调拨单列表
     * @return \think\response\Json
     * Date: 2021/1/16
     * Time: 15:48
     * @author myy
     */
    public function loadTransferList()
    {
        $data = $this->request->post();
        $model = new TransferOrders();
        $result = $model->loadList($this->getData($data));
        return json($result);
    }

    public function loadTransferDetails()
    {
        $orders_code = $this->request->post('orders_code');
        $model = new TransferOrders();
        $data = $model->getDetail($this->getData(['orders_code' => $orders_code]));
        if ($data) {
            $data = $data->toArray();
            // 确定仓库名称
            $organizationModel = new OrganizationModel();
            $outInfo = $organizationModel->getOne(['org_id' => $data['out_warehouse_id']], 'org_name');
            $inInfo = $organizationModel->getOne(['org_id' => $data['in_warehouse_id']], 'org_name');
            $data['out_warehouse_name'] = $outInfo ? $outInfo['org_name'] : '';
            $data['in_warehouse_name'] = $inInfo ? $inInfo['org_name'] : '';
        }
        return $this->renderSuccess($data);
    }

    /**
     * @desc 删除调拨单
     * @return array
     * Date: 2021/1/16
     * Time: 17:57
     * @author myy
     */
    public function delTransferOrders()
    {
        $orders_code = $this->request->post('orders_code');

        $model = new TransferOrders();

        $res = $model->delOrders($this->com_id, $orders_code);
        if ($res) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError());
    }

    /**
     * @desc  库存盘点单列表
     * @return \think\response\Json
     * Date: 2021/1/18
     * Time: 10:43
     * @author myy
     */
    public function loadInventoryList()
    {
        $inventoryOrdersModel = new InventoryOrders();
        $infos = $inventoryOrdersModel->loadList($this->getData($this->request->post()));
        return json($infos);
    }

    /**
     * 函数功能描述 查询库存流水
     * Date: 2021/1/20
     * Time: 11:10
     * @author gxd
     */
    public function queryRecordGoods()
    {
        $where_data = $this->request->get();
        $validate = new StoreValidate();
        $res = $validate->scene("search")->check($where_data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $model = new StockDiaryModel();
        $res = $model->searchDiarys($this->getData($where_data));
        return $this->renderSuccess($res, "数据查询成功~~");
    }

    //加载盘点单
    public function loadInventoryOrders()
    {
        $orders_code = $this->request->post("orders_code");
        $inventoryOrdersModel = new InventoryOrders();
        $orders = $inventoryOrdersModel->getInventoryOrder($this->getData(["orders_code" => $orders_code]));
        $inventoryOrdersChildren = new InventoryOrdersChildren();
        $children_orders = $inventoryOrdersChildren->getOrdersChildrenList($this->getData(["orders_code" => $orders_code, "user_id" => $this->user["user_id"]]), "children_code, children_name");
        $children_use = $inventoryOrdersChildren->getFieldValue($this->getData(["orders_code" => $orders_code, "user_id" => $this->user["user_id"], "curr_use" => 1]), "children_code");
        return $this->renderSuccess(compact('orders', 'children_orders', 'children_use'), "查询成功");
    }

    //加载详细信息
    public function loadInventoryDetails()
    {
        $inventoryDetailsModel = new InventoryOrdersDetails();
        $infos = $inventoryDetailsModel->loadDetailList($this->getData($this->request->post()), $this->user);
        return json($infos);
    }

    /**
     * 保存盘点单商品数据
     */
    public function saveInventoryRow()
    {
        $data = $this->request->post();
        $validate = new StoreValidate();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        //设置制单人id
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseSaveInventoryRow($this->getData($data), $this->user);
        if (!$res) {
            return $this->renderError($logic->getError());
        } else {
            return $this->renderSuccess($logic->getResult(), "操作成功");
        }
    }

    /**
     * 删除盘点单
     */
    public function delInventory()
    {
        $data = $this->request->post();
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseDelOrder($this->getData($data));
        if ($res) {
            return $this->renderSuccess([], "删除成功");
        } else {
            return $this->renderError($logic->getError());
        }
    }

    //删除盘点商品
    public function delGoods()
    {
        $data = $this->request->post();
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseDelGoods($this->getData($data));
        if ($res) {
            return $this->renderSuccess([], "删除成功");
        } else {
            return $this->renderError($logic->getError());
        }
    }

    //删除子单据
    public function delUserChildrenOrders()
    {
        $data = $this->request->post();
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseDelChildren($this->getData($data));
        if ($res) {
            return $this->renderSuccess($logic->getResult(), "删除成功");
        } else {
            return $this->renderError($logic->getError());
        }
    }

    //添加子单据
    public function addUserChildrenOrders()
    {
        $data = $this->request->post();
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseAddChildren($this->getData($data), $this->user);
        if ($res) {
            return $this->renderSuccess($logic->getResult(), "添加成功");
        } else {
            return $this->renderError($logic->getError());
        }
    }

    /**
     * 更新盘点数量
     */
    public function saveGoodsNumber()
    {
        $data = $this->request->post();
        $data = json_decode($data["data"], true);
        $data["user_id"] = $this->user["user_id"];
        $validate = new \app\web\validate\Store();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->baseSaveGoodsNumber($this->getData($data));
        if ($res) {
            return $this->renderSuccess([], "修改库存盘点单商品信息成功~~");
        } else {
            return $this->renderError($logic->getError());
        }
    }

    //保存修改
    public function saveGoodsColorSize()
    {
        $data = json_decode($this->request->post("data"), "true");
        $validate = new \app\web\validate\Store();
        $res = $validate->scene($this->request->action(true))->check($data);
        if (false === $res) {
            return $this->renderError($validate->getError());
        }
        $data["user_id"] = $this->user["user_id"];
        $logic = new StoreInventoryOrdersLogic();
        $res = $logic->saveGoodsColorSize($this->getData($data));
        if ($res) {
            return $this->renderSuccess($logic->getResult(), "修改库存盘点单商品信息成功~~");
        } else {
            return $this->renderError($logic->getError(), $logic->getErrorCode());
        }
    }


    /**
     * 保存草稿
     */
    public function saveInventoryRoughDraft()
    {
        return $this->baseSaveInventory(1);
    }
    /**
     * 保存正式
     */
    public function saveInventoryFormally()
    {
        return $this->baseSaveInventory(9);
    }

    //保存草稿
    protected function baseSaveInventory($status)
    {
        $logic = new StoreInventoryOrdersLogic();
        $data = $this->request->post();

        $data["user_id"] = $this->user["user_id"];
        $res = $logic->baseSaveOrders($status, $this->getData($data));
        if ($res) {
            return $this->renderSuccess($logic->getResult(), "操作成功");
        } else {
            return $this->renderError($logic->getError());
        }
    }

}