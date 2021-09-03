<?php


namespace app\web\controller;

use app\web\model\SaleOrdersDetails as SaleOrdersDetailsModel;


use app\Request;
use app\web\model\PurchaseOrders as PurchaseOrdersModel;
use app\web\model\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;
use app\web\model\SaleOrders as SaleOrdersModel;
use app\web\model\Supplier as SupplierModel;
use app\web\model\User as UserModel;
use app\web\model\Goods as GoodsModel;

class Report extends Controller
{


    public function sale()
    {
        $category = $this->request->get('category', 1);
        $orders_code = $this->request->get('orders_code');
        $orders_status = $this->request->get('orders_status', 9);
        $warehouse_id = $this->request->get('warehouse_id');
        $category_id = $this->request->get('category_id');
        $goods_season = $this->request->get('goods_season');

        $client_name = $this->request->get('client_name');
        $brand_id = $this->request->get('brand_id');
        $begin_date = $this->request->get('begin_date');
        $end_date = $this->request->get('end_date');
        $goods_ncb = $this->request->get('goods_ncb');
        $page = $this->request->get('page', 1);
        $rows = $this->request->get('rows', 20);

        $offset = ($page - 1) * $rows;

        if ($category == 1) {
            $model = new SaleOrdersDetailsModel();
            $baseQuery = $model->alias('d')
                ->join('hr_sale_orders o', 'o.orders_id=d.orders_id', 'left')
                ->join('hr_goods g', 'g.goods_id=d.goods_id', 'left')
                // 颜色
                ->join('hr_color c', 'c.color_id=d.color_id', 'left')
                // 尺寸
                ->join('hr_size s', 's.size_id=d.size_id', 'left')
                // 分类
                ->join('hr_category category', 'category.category_id=g.category_id', 'left')
                // 品牌
                ->join('hr_dict dict_branch', 'dict_branch.dict_id=g.brand_id', 'left')
                // 单位
                ->join('hr_dict dict_unit', 'dict_unit.dict_id=g.unit_id', 'left')
                // 季节
                ->join('hr_dict dict_season', 'dict_season.dict_id=g.goods_season', 'left')
                // 条码
                ->join('hr_goods_details hd', 'hd.goods_id=d.goods_id and hd.size_id=d.size_id and hd.color_id=d.color_id', 'left')
                // 仓库
                ->join('hr_organization organization', 'organization.org_id=o.warehouse_id', 'left')
                // 客户
                ->join('hr_client_base', 'hr_client_base.client_id=o.client_id', 'left')
                // 销售员 user_name
                ->join('hr_user', 'hr_user.user_id=o.salesman_id', 'left')
                // 结算账户 settlement_id
                ->join('hr_settlement', 'hr_settlement.settlement_id=o.settlement_id', 'left')
                ->where('o.com_id', $this->com_id)
                ->when($orders_code, function ($query) use ($orders_code) {
                    $query->where('o.orders_code', 'like', '%' . $orders_code . '%');
                })
                ->when($begin_date, function ($query) use ($begin_date) {
                    $query->where('o.update_time', '>', $begin_date);
                })
                ->when($end_date, function ($query) use ($end_date) {
                    $query->where('o.update_time', '<=', $end_date);
                })
                ->when($client_name, function ($query) use ($client_name) {
                    $query->where('hr_client_base.client_name', 'like', '%' . $client_name . '%');
                })
                ->when($warehouse_id, function ($query) use ($warehouse_id) {
                    $query->where('o.warehouse_id', '=', $warehouse_id);
                })
                ->when($goods_ncb, function ($query) use ($goods_ncb) {
                    $query->where('g.goods_name|g.goods_code|g.goods_barcode', 'like', '%' . $goods_ncb . '%');
                })
                ->when($category_id, function ($query) use ($category_id) {
                    $query->where('g.category_id', '=', $category_id);
                })
                ->when($brand_id, function ($query) use ($brand_id) {
                    $query->where('g.brand_id', '=', $brand_id);
                })
                ->when($goods_season, function ($query) use ($goods_season) {
                    $query->where('g.goods_season', '=', $goods_season);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                });
            $rows = $baseQuery->limit($offset, $rows)->field('d.goods_code,d.goods_id,d.goods_number,d.goods_price,d.goods_tmoney,d.goods_discount as discount,d.goods_daprice,
                g.goods_year,g.goods_barcode,
                o.orders_code,o.orders_date,g.goods_name,d.color_id,c.color_name,s.size_name,
                dict_branch.dict_name as bland_name,category.category_name,dict_unit.dict_name as unit_name,dict_season.dict_name as goods_season,
                organization.org_name as warehouse_name,
                hr_client_base.client_name,
                hr_user.user_name,
                hr_settlement.settlement_name,
                o.orders_pmoney,
                o.orders_rmoney
                ')
                ->order('o.update_time desc')
                ->select();
            $total = $baseQuery->count();
            $goods_money = $baseQuery->sum('o.orders_rmoney');
            $goods_count = $baseQuery->sum('o.goods_number');
            return json(compact('rows', 'total', 'goods_money', 'goods_count'));
        }
        if ($category == 2) {
            $model = new SaleOrdersDetailsModel();
            $baseQuery = $model->alias('d')
                ->join('hr_sale_orders o', 'o.orders_id=d.orders_id', 'left')
                ->join('hr_goods g', 'g.goods_id=d.goods_id', 'left')
                // 分类
                ->join('hr_category category', 'category.category_id=g.category_id', 'left')
                // 品牌
                ->join('hr_dict dict_branch', 'dict_branch.dict_id=g.brand_id', 'left')
                // 单位
                ->join('hr_dict dict_unit', 'dict_unit.dict_id=g.unit_id', 'left')
                // 季节
                ->join('hr_dict dict_season', 'dict_season.dict_id=g.goods_season', 'left')
                ->when($goods_ncb, function ($query) use ($goods_ncb) {
                    $query->where('g.goods_name|g.goods_code|g.goods_barcode', 'like', '%' . $goods_ncb . '%');
                })
                ->when($category_id, function ($query) use ($category_id) {
                    $query->where('g.category_id', '=', $category_id);
                })
                ->when($brand_id, function ($query) use ($brand_id) {
                    $query->where('g.brand_id', '=', $brand_id);
                })
                ->when($goods_season, function ($query) use ($goods_season) {
                    $query->where('g.goods_season', '=', $goods_season);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                });

            $rows = $baseQuery->field([
                'g.goods_year',
                'dict_branch.dict_name as bland_name','category.category_name','dict_unit.dict_name as unit_name',                'dict_season.dict_name as goods_season','g.goods_name','g.goods_code','g.goods_barcode','d.goods_price','d.goods_daprice',
                'SUM(d.goods_number) as goods_number','SUM(d.goods_tdamoney) as goods_tdamoney','SUM(d.goods_tmoney) as goods_tmoney'
            ])
                ->group('d.goods_id')
                ->limit($offset, $rows)
                ->select();
            $total = $baseQuery->count();
            $goods_money = $baseQuery->sum('o.orders_rmoney');
            $goods_count = $baseQuery->sum('o.goods_number');
            return json(compact('rows', 'total', 'goods_money', 'goods_count'));
        }
        // 按照单据汇总
        if ($category == 3) {
            $model = new SaleOrdersModel();
            $baseQuery = $model->alias('o')
                // 仓库
                ->join('hr_organization organization', 'organization.org_id=o.warehouse_id', 'left')
                // 销售员 user_name
                ->join('hr_user', 'hr_user.user_id=o.salesman_id', 'left')
                // 客户
                ->join('hr_client_base', 'hr_client_base.client_id=o.client_id', 'left')
                // 结算账户 settlement_id
                ->join('hr_settlement', 'hr_settlement.settlement_id=o.settlement_id', 'left')
                ->when($begin_date, function ($query) use ($begin_date) {
                    $query->where('o.update_time', '>', $begin_date);
                })
                ->when($end_date, function ($query) use ($end_date) {
                    $query->where('o.update_time', '<=', $end_date);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                });
            $rows = $baseQuery->limit($offset, $rows)->field('o.*,organization.org_name as  warehouse_name,hr_user.user_name,
                hr_settlement.settlement_name,hr_client_base.client_name')->select();
            $total = $baseQuery->count();
            $goods_money = $baseQuery->sum('orders_rmoney');
            $goods_count = $baseQuery->sum('goods_number');
            return json(compact('rows', 'total', 'goods_money', 'goods_count'));

        }
        // 客户汇总
        if ($category == 4) {
            $model = new SaleOrdersModel();
            $baseQuery = $model->alias('o')
                // 客户
                ->join('hr_client_base', 'hr_client_base.client_id=o.client_id', 'left')
                ->when($begin_date, function ($query) use ($begin_date) {
                    $query->where('o.update_time', '>', $begin_date);
                })
                ->when($end_date, function ($query) use ($end_date) {
                    $query->where('o.update_time', '<=', $end_date);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                });
            $rows = $baseQuery->field(['hr_client_base.client_name', 'SUM(o.orders_rmoney) as orders_rmoney', 'SUM(o.goods_number) as goods_number'])->group('o.client_id')->limit($offset, $rows)->select();
            $total = $baseQuery->count();
            $goods_money = $baseQuery->sum('o.orders_rmoney');
            $goods_count = $baseQuery->sum('o.goods_number');
            return json(compact('rows', 'total', 'goods_money', 'goods_count'));
        }
        if ($category == 5) {
            $model = new SaleOrdersModel();
            $baseQuery = $model->alias('o')
                // 销售员 user_name
                ->join('hr_user', 'hr_user.user_id=o.salesman_id', 'left')
                ->when($begin_date, function ($query) use ($begin_date) {
                    $query->where('o.update_time', '>', $begin_date);
                })
                ->when($end_date, function ($query) use ($end_date) {
                    $query->where('o.update_time', '<=', $end_date);
                })
                ->when($orders_status, function ($query) use ($orders_status) {
                    $query->where('o.orders_status', '=', $orders_status);
                });
            $rows = $baseQuery->field(['hr_user.user_name', 'SUM(o.orders_rmoney) as orders_rmoney', 'SUM(o.goods_number) as goods_number'])->group('o.salesman_id')->limit($offset, $rows)->select();
            $total = $baseQuery->count();
            $goods_money = $baseQuery->sum('o.orders_rmoney');
            $goods_count = $baseQuery->sum('o.goods_number');
            return json(compact('rows', 'total', 'goods_money', 'goods_count'));

        }
    }


    /**
     * 函数描述: 采购报表
     * Date: 2021/3/8
     * Time: 11:00
     * @param Request $request
     * @author mll
     */
    public function purchase(Request $request)
    {
        $data = $request->get();

        switch ($data['category']){

            case 1: //查询采购明细
                $purchaseDetail = new PurchaseOrdersDetailsModel();
                $detail = $purchaseDetail->getPurchaseDetail($this->getData($data));
                return json($detail);
                break;
            case 2: //商品汇总
                $purchaseDetail = new PurchaseOrdersDetailsModel();
                $res = $purchaseDetail->getGoodsDetail($this->getData($data));
                return json($res);
                break;
            case 3: //单据汇总
                $purchaseOrder = new PurchaseOrdersModel();
                $res = $purchaseOrder->getPurchaseOrders($this->getData($data));
                return json($res);
                break;
            case 4: //供应商汇总
                $supplier = new SupplierModel();
                $res = $supplier->supplierDetail($this->getData($data));
                return json($res);
                break;
            case 5: //采购员汇总
                $userModel = new UserModel();
                $res = $userModel->getUserDetails($this->getData($data));
                return json($res);
                break;
        }
    }
}