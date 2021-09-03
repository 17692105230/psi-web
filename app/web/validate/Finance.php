<?php


namespace app\web\validate;


class Finance extends BaseValidate
{

    protected $rule = [
        'settlement_id' => 'require|number|gt:0',
        'settlement_id_in' => 'require|number|gt:0',
        'settlement_id_out' => 'require|number|gt:0',
        'account_id' => 'require|number|gt:0',
        'income' => 'require|float|gt:0',
        'ta_money' => 'require|float|gt:0',
        'expend' => 'require|float|gt:0',
        'order_date' => 'require|date',
        'details_id' => 'require|number',
        'lock_version' => 'require|number',
    ];
    protected $message = [
        'settlement_id.require' => '结算账户必须选择~~',
        'settlement_id.number' => '结算账户选择参数错误~~',
        'settlement_id.gt' => '结算账户选择参数错误~~',
        'settlement_id_in.require' => '结算账户必须选择~~',
        'settlement_id_in.number' => '结算账户选择参数错误~~',
        'settlement_id_in.gt' => '结算账户选择参数错误~~',
        'settlement_id_out.require' => '结算账户必须选择~~',
        'settlement_id_out.number' => '结算账户选择参数错误~~',
        'settlement_id_out.gt' => '结算账户选择参数错误~~',
        'account_id.require' => '账目类别必须选择~~',
        'account_id.number' => '账目类别选择参数错误~~',
        'account_id.gt' => '账目类别选择参数错误~~',
        'income.require' => '收入金额必须选择~~',
        'income.float' => '收入金额输入错误',
        'income.gt' => '收入金额输入应大于0',
        'ta_money.require' => '收入金额必须选择~~',
        'ta_money.float' => '收入金额输入错误',
        'ta_money.gt' => '收入金额输入应大于0',
        'expend.float' => '支出金额输入错误',
        'expend.gt' => '支出金额输入应大于0',
        'order_date.require' => '业务日期必须填写~~',
        'order_date.date' => '业务日期格式错误~~'
    ];
    protected $scene = [
        'saveAccountIn' => ['settlement_id', 'account_id', 'income', 'order_date'],
        'saveAccountOut' => ['settlement_id', 'account_id', 'expend', 'order_date'],
        'saveAccountLoop' => ['settlement_id_in', 'settlement_id_out', 'ta_money','order_date','lock_version'],
        'saveAccountBegin' => ['settlement_id', 'income', 'order_date',],
    ];


}