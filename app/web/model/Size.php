<?php


namespace app\web\model;

use app\common\model\web\Size as SizeModel;

class Size extends SizeModel
{
    public function addParent($size_parent,$com_id,$sort = '1'){
        $this->save(['size_name' => $size_parent,'size_group' => 0,'lock_version' => 1,'com_id' => $com_id,'sort' => $sort]);
        return $this->size_id;
    }
    public function addSon($parent_id,$size_name,$com_id,$size_sort){
        return $this->save(['size_group' => $parent_id,'size_name' => $size_name,'lock_version' => 1,'com_id' => $com_id,'sort' => $size_sort]);
    }
}