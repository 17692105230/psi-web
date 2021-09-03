<?php
/**
 * 常用工具类
 *
 * @author     zhangzhichao <zh.zc@qq.com>
 */

namespace app\common\utils;

class BuildCode
{
    /**
     * 根据日期获得唯一序列（时间戳）
     * 最小单位（毫秒）
     * @parame prefix 前缀
     * @parame suffix 后缀
     * @return code
     *
     * Examples:
     * Utils::buildCode('D','');
     */
    static public function Code($prefix = '', $suffix = '')
    {
        list($t1, $t2) = explode(' ', microtime());
        $temp = (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        $code = strval(floatval(substr($temp, 0, 2)) + 1) . substr($temp, 2);
        $code = ($prefix ? $prefix : '') . $code . ($suffix ? $suffix : '');
        return $code;
    }

    /**
     * 根据日期获得年月日6位组合字符串
     * 年份、月份、日全部为两位数字
     * @parame prefix 前缀
     * @parame suffix 后缀
     * @return code
     *
     * Examples:
     * Utils::buildCode6('D');
     */
    static public function Code6($prefix = '', $suffix = '')
    {
        $code = date("ymd", time());
        $code = ($prefix ? $prefix : '') . $code . ($suffix ? $suffix : '');
        return $code;
    }

    /**
     * @desc 根据日期获得唯一序列（年月日时分秒毫秒）
     * @param string $prefix
     * @param string $suffix
     * @param bool $locked
     * @return string
     * Date: 2021/1/8
     * Time: 11:38
     */
    static public function dateCode($prefix = '', $suffix = '', $locked = true)
    {
        $lock = null;
        if ($locked) {
            $lock = new Lock(app()->getRuntimePath() . '../lock/dataCode' . $prefix . $suffix . '.lock');
            $lock->lock();
        }
        list($t1, $t2) = explode(' ', microtime());
        $microtime = sprintf("%03d", sprintf('%.0f', floatval($t1) * 1000));
        $date = date('ymdHis', $t2) . $microtime;
        $code = ($prefix ? $prefix : '') . $date . ($suffix ? $suffix : '');
        if ($lock) {
            $lock->unlock();
        }
        return $code;
    }
}