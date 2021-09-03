<?php
/**
 * 销售单详情
 * User: YB02
 * Date: 2021/1/15
 * Time: 10:51
 */

namespace app\common\model\web;
use app\common\model\BaseModel;

class SaleOrdersDetails extends BaseModel
{
  protected $table = 'hr_sale_orders_details';
  protected $pk = 'details_id';
  protected $autoWriteTimestamp = true;
}