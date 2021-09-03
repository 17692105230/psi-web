<?php


namespace app\web\model;

use app\common\model\web\PurchaseOrdersDetails as PurchaseOrdersDetailsModel;

class PurchaseOrdersDetails extends PurchaseOrdersDetailsModel
{

    public function add($data)
    {
        return $this->insert([
           'orders_id' => $data['orders_id'],
           'orders_code' => $data['orders_code'],
           'goods_code' => $data['goods_code'],
           'goods_id' => $data['goods_id'],
           'goods_status' => $data['goods_status'],
           'color_id' => $data['color_id'],
           'size_id' => $data['size_id'],
           'goods_number' => $data['goods_number'],
           'goods_price' => $data['goods_price'],
           'goods_tmoney' => $data['goods_tmoney'],
           'goods_discount' => $data['goods_discount'],
           'goods_daprice' => $data['goods_daprice'],
           'goods_tdamoney' => $data['goods_tdamoney'],
           'create_time' => $data['time_now'],
           'update_time' => $data['time_now'],
           'com_id' => $data['com_id'],
        ]);
    }

    public function saveData($data)
    {
        return $this->save([
            'orders_code' => $data['orders_code'],
            'orders_id' => $data['orders_id'],
            'goods_code' => $data['goods_code'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id'],
            'goods_number' => $data['goods_number'],
            'goods_price' => $data['goods_price'],
            'goods_tmoney' => $data['goods_tmoney'],
            'goods_discount' => $data['goods_discount'],
            'goods_daprice' => $data['goods_daprice'],
            'goods_tdamoney' => $data['goods_tdamoney'],
            'goods_status' => isset($data['goods_status']) ? $data['goods_status'] : 0,
            'lock_version' => isset($data['lock_version']) ? $data['lock_version'] : 0,
            'com_id' => $data['com_id'],
        ]);
    }

    /**
     * 函数描述:采购明细
     * @param $where
     * @return array
     * Date: 2021/3/9
     * Time: 9:35
     * @author mll
     */
    public function getPurchaseDetail($where)
    {
        $condtion = $this->alias('d')
            ->where(['d.com_id'=>$where['com_id']])
            ->where('o.orders_status','<>',7)
            ->when(isset($where['supplier_id']) && $where['supplier_id'],function ($query) use ($where){
                $query->where('o.supplier_id',$where['supplier_id']);
            })
            ->when(isset($where['warehouse_id']) && $where['warehouse_id'],function ($query) use ($where){
                $query->where('oi.org_id',$where['warehouse_id']);
            })
            //商品名称
            ->when(isset($where['goods_ncb']) && $where['goods_ncb'],function ($query) use ($where){
                $query->where('g.goods_name','like','%'.$where['goods_ncb'].'%');
            })
            ->when(isset($where['orders_code']) && $where['orders_code'],function ($query) use ($where){
                $query->where('o.orders_code',$where['orders_code']);
            })
            ->when(isset($where['category_id']) && $where['category_id'],function ($query) use ($where){
                $query->where('co.category_id',$where['category_id']);
            })
            ->when(isset($where['brand_id']) && $where['brand_id'],function ($query) use ($where){
                $query->where('t.dict_id',$where['brand_id']);
            })
            //年份
//            ->when(isset($where['goods_year']) && $where['goods_year'],function ($query) use ($where){
//                $query->where('g.goods_year',$where['goods_year']);
//            })
            ->when(isset($where['goods_season']) && $where['goods_season'],function ($query) use ($where){
                $query->where('g.goods_season',$where['goods_season']);
            })
            ->when(isset($where['goods_status']) && $where['goods_status'],function ($query) use ($where){
                $query->where('o.orders_status',$where['goods_status']);
            })
            //如果只有开始时间
            ->when(isset($where['begin_date']) && $where['begin_date'],function ($query) use ($where){
                $query->where('d.update_time','>=',$where['begin_date']);
            })
            //如果只有结束时间
            ->when(isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->where('d.update_time','<=',$where['end_date']);
            })
            //开始时间结束时间都有
            ->when(isset($where['begin_date']) && $where['begin_date'] && isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->whereBetween('d.update_time',[$where['begin_date'],$where['end_date']]);
            })
            ->join('hr_purchase_orders o','d.orders_code=o.orders_code','left')
            ->join('hr_goods g','d.goods_id=g.goods_id','left')
            ->join('hr_size s','d.size_id=s.size_id','left')
            ->join('hr_color c','d.color_id=c.color_id','left')
            ->join('hr_supplier sl','sl.supplier_id=o.supplier_id','left')
            ->join('hr_organization oi','o.warehouse_id=oi.org_id','left')
            ->join('hr_settlement st','o.settlement_id=st.settlement_id','left')
            ->join('hr_user u','u.user_id=o.user_id','left')
            ->join('hr_dict t','t.dict_id=g.brand_id','left')
            ->join('hr_dict dt','dt.dict_id=g.unit_id','left')
            ->join('hr_category co','co.category_id=g.category_id','left');

        $total = $condtion->count();
        $offset = ($where['page'] - 1) * $where['rows'];
        $rows = $condtion
            ->limit($offset,$where['rows'])
            ->field('d.goods_number,d.goods_price,d.goods_tmoney,d.goods_discount,d.goods_daprice,d.goods_status,
                    d.orders_code,o.orders_pmoney,o.orders_rmoney,o.orders_date,g.goods_barcode,g.goods_name,g.goods_rprice,
                    g.goods_year,g.goods_season,g.goods_code,s.size_name,c.color_name,sl.supplier_name,oi.org_name,
                    st.settlement_name,u.user_name,t.dict_name as brand_name,o.orders_status,co.category_name,
                    dt.dict_name as unit_name')
            ->select();
        $goods_count = $condtion->sum('d.goods_number');
        $goods_money = $condtion->sum('d.goods_tmoney');
        return compact('total','rows','goods_count','goods_money');
    }

    /**
     * 函数描述: 商品汇总
     * @param $where
     * Date: 2021/3/9
     * Time: 16:35
     * @author mll
     */
    public function getGoodsDetail($where)
    {
        $condtion = $this->alias('d')
            ->where('d.com_id',$where['com_id'])
            ->where('o.orders_status','<>',7)
            //如果只有开始时间
            ->when(isset($where['begin_date']) && $where['begin_date'],function ($query) use ($where){
                $query->where('d.update_time','>=',$where['begin_date']);
            })
            //如果只有结束时间
            ->when(isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->where('d.update_time','<=',$where['end_date']);
            })
            //开始时间结束时间都有
            ->when(isset($where['begin_date']) && $where['begin_date'] && isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->whereBetween('d.update_time',[$where['begin_date'],$where['end_date']]);
            })
            //商品,条码,货号
            ->when(isset($where['goods_ncb']) && $where['goods_ncb'],function ($query) use ($where){
                $query->where('g.goods_name|g.goods_code|g.goods_barcode','like','%'.$where['goods_ncb'].'%');
            })
            //分类编号
            ->when(isset($where['category_id']) && $where['category_id'],function ($query) use ($where){
                $query->where('c.category_id',$where['category_id']);
            })
            //品牌
            ->when(isset($where['brand_id']) && $where['brand_id'],function ($query) use ($where){
                $query->where('di.dict_id',$where['brand_id']);
            })
            //季节
            ->when(isset($where['goods_season']) && $where['goods_season'],function ($query) use ($where){
                $query->where('dc.dict_id',$where['goods_season']);
            })
            //年份
            ->when(isset($where['goods_year']) && $where['goods_year'],function ($query) use ($where){
                $query->where('g.goods_year',$where['goods_year']);
            })
            //状态
            ->when(isset($where['goods_status']) && $where['goods_status'],function ($query) use ($where){
                $query->where('o.orders_status',$where['orders_status']);
            })
            ->join('hr_purchase_orders o','o.orders_code = d.orders_code','left')
            ->join('hr_goods g','g.goods_code = d.goods_code','left')
            ->join('hr_category c','c.category_id = g.category_id','left')
            ->join('hr_dict di','di.dict_id = g.brand_id','left')
            ->join('hr_dict dt','dt.dict_id = g.unit_id','left')
            ->join('hr_dict dc','dc.dict_id = g.goods_season')
            ->field(['g.goods_name','g.goods_code','g.goods_barcode','di.dict_name as brand_name',
                    'c.category_name','dt.dict_name as unit_name','o.goods_number','g.goods_srprice',
                    'sum(d.goods_tmoney)'=>'goods_tmoney','d.goods_discount','sum(d.goods_daprice)'=>'goods_daprice',
                    'sum(d.goods_tdamoney)'=>'goods_tdamoney','g.goods_year',
                    'dc.dict_name'=>'goods_season'
                ]);
        $total = $condtion->count();
        $offest = ($where['page'] - 1) * $where['rows'];
        $rows = $condtion
            ->limit($offest,$where['rows'])
            ->group('g.goods_code')
            ->select();
        //商品数量
        $goods_count = $condtion->sum('d.goods_number');
        //商品金额
        $goods_money = $condtion->sum('d.goods_tmoney');
        return compact('total','rows','goods_count','goods_money');
    }
}