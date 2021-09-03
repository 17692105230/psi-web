<?php


namespace app\web\model;
use app\common\model\web\User as UserModel;

class User extends UserModel
{
    public function getUserDetails($where)
    {
        $condtion = $this->alias('u')
            ->where('u.com_id',$where['com_id'])
            ->where('o.orders_status','<>',7)
            //如果只有开始时间
            ->when(isset($where['begin_date']) && $where['begin_date'],function ($query) use ($where){
                $query->where('u.create_time','>=',$where['begin_date']);
            })
            //如果只有结束时间
            ->when(isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->where('u.create_time','<=',$where['end_date']);
            })
            //开始时间结束时间都有
            ->when(isset($where['begin_date']) && $where['begin_date'] && isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->whereBetween('u.create_time',[$where['begin_date'],$where['end_date']]);
            })
            ->join('hr_purchase_orders o','o.user_id = u.user_id','left')
            ->join('hr_purchase_orders_details d','o.orders_code = d.orders_code','left')
            ->field(['u.user_name','sum(d.goods_tdamoney)'=>'orders_rmoney','sum(o.goods_number)'=>'goods_number']);
        $total = $condtion->count();
        $offset = ($where['page'] - 1) * $where['rows'];
        $rows = $condtion
            ->limit($offset, $where['rows'])
            ->group('u.user_name')
            ->select();
        //商品数量
        $goods_count = $condtion->sum('o.goods_number');
        //商品金额
        $goods_money = $condtion->sum('d.goods_tdamoney');
        return compact('total','rows','goods_count','goods_money');
    }
}