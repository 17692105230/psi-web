<?php


namespace app\web\model;

use app\common\model\web\Supplier as SupplierModel;
use think\Db;

class Supplier extends SupplierModel
{
    /*
     * 查询
     */
    public function getGridList($where, $rows, $page)
    {
        $condtion = $this->where('com_id', $where['com_id'])
            // 供应商名称
            ->when(isset($where['supplier_name']) && $where['supplier_name'], function ($query) use ($where) {
                $query->where('supplier_name', 'like', '%' . $where['supplier_name'] . '%');
            })
            // 负责人
            ->when(isset($where['goods_barcode']) && $where['goods_barcode'], function ($query) use ($where) {
                $query->where('supplier_director', 'like', '%' . $where['supplier_director'] . '%');
            })
            // 状态
            ->when(isset($where['supplier_status']) && $where['supplier_status'] != 'all', function ($query) use ($where) {
                $query->where('supplier_status', $where['supplier_status']);
            });
        $total = $condtion->count();
        $offset = ($page - 1) * $rows;
        $rows = $condtion
            ->limit($offset, $rows)
            ->order('sort', 'asc')
            ->field('*')
            ->select();
        return compact('total', 'rows');
    }

    /**
     * 新增
     */
    public function addSupplier($data)
    {
        $info = [
            'supplier_name' => $data['supplier_name'],
            'supplier_director' => $data['supplier_director'],
            'supplier_phone' => $data['supplier_phone'],
            'supplier_mphone' => $data['supplier_mphone'],
            'supplier_discount' => $data['supplier_discount'],
            'supplier_email' => $data['supplier_email'],
            'supplier_address' => $data['supplier_address'],
            'supplier_status' => $data['status'],
            'supplier_story' => $data['supplier_story'],
            'sort' => $data['supplier_sort'],
            'com_id' => $data['com_id'],
            'lock_version' => isset($data['lock_version']) ? 0 : $data['lock_version']
        ];
        return $this->save($info);
    }

    /**
     * 删除
     */
    public function delSupplier($where)
    {
        return $this->where($where)->delete();
    }

    /**
     * 更新
     */
    public function edit($data)
    {
        $info = $this->getOne(['supplier_id' => $data['supplier_id']]);
        if (empty($info)) {
            $this->setError('供应商不存在');
            return false;
        }
        if ($data['lock_version'] != $info['lock_version']) {
            $this->setError('不是最新版本');
            return false;
        }
        $data['lock_version'] = $data['lock_version'] + 1;
        $res = $info->addSupplier($data);
        return $res;
    }
    public function supplierDetail($where)
    {
        $condtion = $this->alias('s')
            ->where('s.com_id',$where['com_id'])
            ->when(isset($where['supplier_id']) && $where['supplier_id'],function ($query) use ($where){
              $query->where('s.supplier_id',$where['supplier_id']);
            })
            //如果只有开始时间
            ->when(isset($where['begin_date']) && $where['begin_date'],function ($query) use ($where){
                $query->where('s.update_time','>=',$where['begin_date']);
            })
            //如果只有结束时间
            ->when(isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->where('s.update_time','<=',$where['end_date']);
            })
            //开始时间结束时间都有
            ->when(isset($where['begin_date']) && $where['begin_date'] && isset($where['end_date']) && $where['end_date'],function ($query) use ($where){
                $query->whereBetween('s.update_time',[$where['begin_date'],$where['end_date']]);
            })
            ->join('hr_purchase_orders o','o.supplier_id = s.supplier_id','left')
            ->join('hr_purchase_orders_details d','d.orders_code = o.orders_code','left')
            ->field(['s.supplier_name','sum(d.goods_tdamoney)'=>'orders_rmoney','sum(o.goods_number)'=>'goods_number']);
        $total = $condtion->count();
        $offset = ($where['page'] - 1) * $where['rows'];
        $rows = $condtion
            ->limit($offset, $where['rows'])
//            ->field(['s.supplier_name','sum(d.goods_tdamoney)'=>'orders_rmoney','sum(o.goods_number)'=>'goods_number'])
            ->group('s.supplier_name')
            ->select();
        //商品数量
        $goods_count = $condtion->sum('o.goods_number');
        //商品金额
        $goods_money = $condtion->sum('d.goods_tdamoney');
        return compact('total','rows','goods_count','goods_money');
    }
}