<?php


namespace app\web\model;

use app\common\model\web\Goods as GoodsModel;
use app\web\model\Dict as DictModel;
use think\facade\Db;
use Exception;
use app\web\model\GoodsDetails as GoodsDetailsModel;
use app\web\model\GoodsAssist as GoodsAssistModel;
use app\web\logic\SystemLogic;


class Goods extends GoodsModel
{
  //  protected $append = ['detail_info'];

    public function getPageList($where, $rows, $page)
    {
        $total = $this->where($where)->count();
        $offset = ($page - 1) * $rows;
        $rows = $this->where($where)->limit($offset, $rows)->order('create_time', 'desc')->select();
        return compact('total', 'rows');
    }

    public function getGoodsList($where, $rows, $page)
    {
        $total = $this->where('com_id', $where['com_id'])
            ->where('goods_status', '<>', 100)
            // 商品名称
            ->when(isset($where['goods_name']) && $where['goods_name'], function ($query) use ($where) {
                $query->where('goods_name', 'like', '%' . $where['goods_name'] . '%');
            })
            // 商品条码
            ->when(isset($where['goods_barcode']) && $where['goods_barcode'], function ($query) use ($where) {
                $query->where('goods_barcode', 'like', '%' . $where['goods_barcode'] . '%');
            })
            // 货号
            ->when(isset($where['goods_code']) && $where['goods_code'], function ($query) use ($where) {
                $query->where('goods_code', 'like', '%' . $where['goods_code'] . '%');
            })
            // 状态
            ->when(isset($where['goods_status']) && $where['goods_status'] != 'all', function ($query) use ($where) {
                $query->where('goods_status', $where['goods_status']);
            })
            ->count();

        $offset = ($page - 1) * $rows;
        $rows = $this->alias('g')
            ->with(['images', 'detail'])
            ->where('g.com_id', $where['com_id'])
            ->where('g.goods_status', '<>', 100)
            // 商品名称
            ->when(isset($where['goods_name']) && $where['goods_name'], function ($query) use ($where) {
                $query->where('g.goods_name', 'like', '%' . $where['goods_name'] . '%');
            })
            // 商品条码
            ->when(isset($where['goods_barcode']) && $where['goods_barcode'], function ($query) use ($where) {
                $query->where('g.goods_barcode', 'like', '%' . $where['goods_barcode'] . '%');
            })
            // 货号
            ->when(isset($where['goods_code']) && $where['goods_code'], function ($query) use ($where) {
                $query->where('g.goods_code', 'like', '%' . $where['goods_code'] . '%');
            })
            // 状态
            ->when(isset($where['goods_status']) && $where['goods_status'] != 'all', function ($query) use ($where) {
                $query->where('g.goods_status', $where['goods_status']);
            })
            ->limit($offset, $rows)
            ->order('g.create_time', 'desc')
            ->join('hr_category c', 'c.category_id=g.category_id', 'left')
            ->field('g.*,c.category_name')
            ->select()
            ->toArray();
        $dict_arr = [];
        foreach ($rows as $row) {
            array_push($dict_arr, $row['style_id'],$row['brand_id'],$row['material_id'],$row['goods_season'],$row['unit_id']);
        }
        if ($dict_arr) {
            $dict_result = (new DictModel())->whereIn('dict_id',array_unique($dict_arr))->column('dict_name','dict_id');
            foreach ($rows as &$row) {
                $detail_info['style_name'] = isset($dict_result[$row['style_id']]) ? $dict_result[$row['style_id']] : '';
                $detail_info['branch_name'] = isset($dict_result[$row['brand_id']]) ? $dict_result[$row['brand_id']] : '';
                $detail_info['material_name'] = isset($dict_result[$row['material_id']]) ? $dict_result[$row['material_id']] : '';
                $detail_info['unit_name'] = isset($dict_result[$row['unit_id']]) ? $dict_result[$row['unit_id']] : '';
                $detail_info['season_name'] = isset($dict_result[$row['goods_season']]) ? $dict_result[$row['goods_season']] : '';
                $row['detail_info'] = $detail_info;
            }
        }

        return compact('total', 'rows');
    }

    public function getDetailInfoAttr($value, $data)
    {
        $detail_info = [];

        $dictModel = new DictModel();
        //品牌
        $branInfo = $dictModel->getOne(['dict_id' => $data['brand_id']], 'dict_name');
        $detail_info['branch_name'] = $branInfo ? $branInfo['dict_name'] : '';
        //材料
        $materialInfo = $dictModel->getOne(['dict_id' => $data['material_id']], 'dict_name');
        $detail_info['material_name'] = $materialInfo ? $materialInfo['dict_name'] : '';
        //单位
        $unitInfo = $dictModel->getOne(['dict_id' => $data['unit_id']], 'dict_name');
        $detail_info['unit_name'] = $unitInfo ? $unitInfo['dict_name'] : '';
        //季节
        $seasonInfo = $dictModel->getOne(['dict_id' => $data['goods_season']], 'dict_name');
        $detail_info['season_name'] = $seasonInfo ? $seasonInfo['dict_name'] : '';

        return $detail_info;
    }

    public function delGoods($where)
    {
        $info = $this->with('detail')->where($where)->find();
        if (empty($info)) {
            $this->setError('该商品不存在');
            return false;
        }
        return $info->together(['detail'])->delete();
    }


    public function getDetail($where)
    {
        return $this->alias('g')
            ->with(['images', 'detail'])
            ->where('g.com_id', $where['com_id'])
            // 货号ID
            ->when(isset($where['goods_id']) && $where['goods_id'], function ($query) use ($where) {
                $query->where('g.goods_id', $where['goods_id']);
            })
            // 货号
            ->when(isset($where['goods_code']) && $where['goods_code'], function ($query) use ($where) {
                $query->where('g.goods_code', $where['goods_code']);
            })
            ->join('hr_category c', 'c.category_id=g.category_id', 'left')
            ->field('g.*,c.category_name')
            ->find();
    }

    public function saveGoods($form_data, $com_id)
    {
        $data = [
            'goods_code' => $form_data['goods_code'],
            'goods_sort' => $form_data['goods_sort'],
            'goods_barcode' => $form_data['goods_barcode'],
            'goods_name' => $form_data['goods_name'],
            'goods_pprice' => $form_data['goods_pprice'],
            'goods_wprice' => $form_data['goods_wprice'],
            'goods_srprice' => $form_data['goods_srprice'],
            'goods_rprice' => $form_data['goods_rprice'],
            'goods_bnumber' => $form_data['goods_bnumber'],
            'category_id' => $form_data['category_id'],
            'brand_id' => $form_data['brand_id'],
            'material_id' => $form_data['material_id'],
            'goods_sex' => $form_data['goods_sex'],
            'unit_id' => $form_data['unit_id'],
            'goods_year' => $form_data['goods_year'],
            'goods_season' => $form_data['goods_season'],
            'goods_llimit' => $form_data['goods_llimit'],
            'goods_ulimit' => $form_data['goods_ulimit'],
            'goods_status' => $form_data['goods_status'],
            'goods_story' => $form_data['details'],
            'com_id' => $com_id,
            'lock_version' => isset($form_data['lock_version']) ? $form_data['lock_version'] : 0,

        ];
        return $this->save($data);
    }

    public function edit($form_data, $com_id)
    {
        $info = $this->getOne(['goods_id' => $form_data['goods_id'], 'com_id' => $com_id]);
        if (empty($info)) {
            $this->setError('商品不存在');
            return false;
        }
        if ($form_data['lock_version'] != $info['lock_version']) {
            $this->setError('不是最新版本');
            return false;
        }
        Db::startTrans();
        try {
            $form_data['lock_version'] = $form_data['lock_version'] + 1;
            $info->saveGoods($form_data, $com_id);
            $goods_details = new GoodsDetails();
            $goods_details->where('goods_id', $form_data['goods_id'])->delete();
            $details = json_decode($form_data['details'], true);
            foreach ($details as $key => $rows) {
                $res2 = $goods_details->saveDetails($rows, $com_id, $form_data['goods_code'], $form_data['goods_id']);
                if (!$res2) {
                    throw new Exception('修改失败');
                }
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }

    //采购单查询用
    public function getGoodsPaginate($com_id, $orwhere, $rows, $fields = "*")
    {
        return $this->where(["com_id" => $com_id])->where(function ($query) use ($orwhere) {
            $query->whereOr($orwhere);
        })->field($fields)->order("goods_id", "desc")->paginate($rows);
    }

    public function createGoods($data)
    {
        // 该商户下面 goods_code 不允许重复
        $doneInfo = $this->getOne(['goods_code' => $data['goods_code'],'com_id' => $data['com_id']], 'goods_code');
        if ($doneInfo) {
            $this->setError('商品货号重复！');
            return false;
        }
        Db::startTrans();
        try {
            //添加goods数据
            $hasInfo = $this->where('goods_id', $data['goods_id'])->find();
            if (empty($hasInfo)) {
                throw new Exception('添加订单ID错误');
            }
           // $this = $hasInfo;
            $data['goods_status'] = 0;
            $hasInfo->saveGoods($data, $data['com_id']);
            // 添加goods_details
            $details = json_decode($data['details'], true);
            foreach ($details as $key => $rows) {
                $rows['com_id'] = $hasInfo['com_id'];
                $rows['goods_code'] = $hasInfo['goods_code'];
                $rows['goods_id'] = $hasInfo['goods_id'];
                (new  GoodsDetailsModel())->createDetails($rows);
            }
            // 处理商品图片

        /*    if (isset($data['images']) && $data['images']) {
                $images = explode(',' ,$data['images']);
                foreach ($images as $image) {
                    $assistInfo = (new GoodsAssistModel())->getOne(['com_id' => $data['com_id'], 'assist_id' => $image],'assist_url');
                    if (!$assistInfo) {
                        continue ;
                    }
                    // 写入关联的商品ID
                    $assistUpdate['goods_id'] = $this['goods_id'];
                    // 目录位置为： /storage/企业ID/goods/产品ID/
                    if (file_exists(toRelativePath($assistInfo['assist_url']))) {
                        $file_name = basename($assistInfo['assist_url']);
                        $formal_path = '/storage/'.$data['com_id'].'/goods/'.$this['goods_id'];
                        $newName = $formal_path.'/'.$file_name;
                        if ($newName != $assistInfo['assist_url']) {
                            if (!is_dir(toRelativePath($formal_path))) {
                                mkdir(toRelativePath($formal_path),0777,true);
                            }
                            rename(toRelativePath($assistInfo['assist_url']), toRelativePath($newName));
                            $assistUpdate['assist_url'] = $newName;
                        }
                    }
                    $assistInfo->save($assistUpdate);
                }
            }*/


            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            $this->setError('line.'.$e->getLine());
            return false;
        }
    }


    public function updateGoods($data)
    {
        // 该商户下面 goods_code 不允许重复
        $doneInfo = $this->getOne(['goods_code' => $data['goods_code'],'com_id' => $data['com_id']], 'goods_code');
        if ($doneInfo) {
            $this->setError('商品货号重复！');
            return false;
        }
        Db::startTrans();
        try {
            //添加goods数据
            $this->saveGoods($data, $data['com_id']);
            // 添加goods_details
            $details = json_decode($data['details'], true);
            foreach ($details as $key => $rows) {
                $rows['com_id'] = $this['com_id'];
                $rows['goods_code'] = $this['goods_code'];
                $rows['goods_id'] = $this['goods_id'];
                (new  GoodsDetailsModel())->createDetails($rows);
            }
            // 处理商品图片

            if (isset($data['images']) && $data['images']) {
                $images = explode(',' ,$data['images']);
                foreach ($images as $image) {
                    $assistInfo = (new GoodsAssistModel())->getOne(['com_id' => $data['com_id'], 'assist_id' => $image],'assist_url');
                    if (!$assistInfo) {
                        continue ;
                    }
                    // 写入关联的商品ID
                    $assistUpdate['goods_id'] = $this['goods_id'];
                    // 目录位置为： /storage/企业ID/goods/产品ID/
                    if (file_exists(toRelativePath($assistInfo['assist_url']))) {
                        $file_name = basename($assistInfo['assist_url']);
                        $formal_path = '/storage/'.$data['com_id'].'/goods/'.$this['goods_id'];
                        $newName = $formal_path.'/'.$file_name;
                        if ($newName != $assistInfo['assist_url']) {
                            if (!is_dir(toRelativePath($formal_path))) {
                                mkdir(toRelativePath($formal_path),0777,true);
                            }
                            rename(toRelativePath($assistInfo['assist_url']), toRelativePath($newName));
                            $assistUpdate['assist_url'] = $newName;
                        }
                    }
                    $assistInfo->save($assistUpdate);
                }
            }
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            $this->setError('line.'.$e->getLine());
            return false;
        }
    }

    public function editNew($form_data, $com_id)
    {
        $goods_id = $form_data['goods_id'];
        $info = $this->getOne(['goods_id' => $goods_id, 'com_id' => $com_id]);
        if (empty($info)) {
            $this->setError('商品不存在');
            return false;
        }
        if ($form_data['lock_version'] != $info['lock_version']) {
            $this->setError('不是最新版本');
            return false;
        }
        Db::startTrans();
        try {
            $form_data['lock_version'] = $form_data['lock_version'] + 1;
            $info->saveGoods($form_data, $com_id);
            $goods_details = new GoodsDetails();
            $goods_details->where('goods_id', $form_data['goods_id'])->delete();
            $details = json_decode($form_data['details'], true);
            //更新信息
            $update_details_list = [];
            foreach ($details as $temp) {
                $update_details_list[] = $temp['size_id'].'-'.$temp['color_id'];
            }
            // 当前修改的详情id
            $goodsDetailModel = new GoodsDetails();
            // 之前的
            $lastVersionDetail = $goodsDetailModel
                ->where('goods_id', $goods_id)
                ->field('size_id,color_id')
                ->select()
                ->toArray();
            $last_list = [];
            foreach ($lastVersionDetail as $item) {
                $last_list[] = $item['size_id'].'-'.$item['color_id'];
            }
            // 需要删除的子商品
            $delete_detail = array_diff($last_list, $update_details_list);
            $systmeLogic = new SystemLogic();
            foreach ($delete_detail as $del_item) {
                list($size_id, $color_id) = explode($del_item,'-');
                $search_where = ['goods_id' => $goods_id,'size_id' => $size_id, 'color_id' => $color_id];
                $exist_res = $systmeLogic->exist($search_where, 'goods_id');
                //没有使用过就删除掉
                if (!$exist_res) {
                    $goodsDetailModel->where($search_where)->delete();
                }
            }
            // 需要添加的
            $add_detail = array_diff( $update_details_list,$last_list);
            foreach ($details as $key => $rows) {
                if (in_array($rows['size_id'].'-'.$rows['color_id'], $add_detail)) {
                    $res2 = $goods_details->saveDetails($rows, $com_id, $form_data['goods_code'], $form_data['goods_id']);
                    if (!$res2) {
                        throw new Exception('修改失败');
                    }
                }

            }
            // 需要修改的
            $update_detail = array_intersect($update_details_list, $last_list);
            foreach ($details as $key => $rows) {
                if (in_array($rows['size_id'].'-'.$rows['color_id'], $update_detail)) {
                    $where = [
                      'size_id' => $rows['size_id'],
                      'color_id' => $rows['color_id'],
                      'goods_id' => $goods_id,
                    ];
                    $goodsDetailModel->where($where)->update([
                        'goods_scode' => $rows['goods_scode'],
                        'goods_sbarcode' => $rows['goods_sbarcode'],
                    ]);
                }

            }
            // 处理商品图片

            /*if (isset($form_data['images']) && $form_data['images']) {
                $images = explode(',' ,$form_data['images']);
                foreach ($images as $image) {
                    $assistInfo = (new GoodsAssistModel())->getOne(['com_id' => $com_id, 'assist_id' => $image],'assist_url');
                    if (!$assistInfo) {
                        continue ;
                    }
                    // 写入关联的商品ID
                    $assistUpdate['goods_id'] = $goods_id;
                    // 目录位置为： /storage/企业ID/goods/产品ID/
                    if (file_exists(toRelativePath($assistInfo['assist_url']))) {
                        $file_name = basename($assistInfo['assist_url']);
                        $formal_path = '/storage/'.$com_id.'/goods/'.$goods_id;
                        $newName = $formal_path.'/'.$file_name;
                        if ($newName != $assistInfo['assist_url']) {
                            if (!is_dir(toRelativePath($formal_path))) {
                                mkdir(toRelativePath($formal_path),0777,true);
                            }
                            rename(toRelativePath($assistInfo['assist_url']), toRelativePath($newName));
                            $assistUpdate['assist_url'] = $newName;
                        }
                    }
                    $assistInfo->save($assistUpdate);
                }
            }*/


            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollback();
            $this->setError($e->getMessage());
            return false;
        }
    }
}