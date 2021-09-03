<?php


namespace app\web\validate;


class Size extends BaseValidate
{
    protected $rule = [
      'size_group|所属上级' => 'require',
      'size_name|尺寸名称' => 'require',
      'lock_version|版本信息' => 'require',
      'sort|排序' => 'require',
    ];

    protected $scene = [
      'edit' => ['size_group', 'size_name'],
    ];
}