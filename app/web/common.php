<?php

// 这是系统自动生成的公共文件
/**
 * 生成treeGrade数据格式
 * @param array $arr 原始数据
 * @param string $pk 主键字段名称
 * @param string $pid 父级字段名称
 * @param string $child 子级节点名称
 * @return array
 */
function createTree($arr = array(), $pk = 'id', $upid = 'pid', $child = 'children')
{
    $items = array();
    foreach ($arr as $val) {
        $items[$val[$pk]] = $val;
    }
    $tree = array();
    foreach ($items as $k => $val) {
        if (isset($items[$val[$upid]])) {
            $items[$val[$upid]][$child][] =& $items[$k];
        } else {
            $tree[] = &$items[$k];
        }
    }
    return $tree;
}

function pageOffset($arr, $page = 'page', $rows = 'rows', $default = 20)
{
    $c_page = isset($arr[$page]) ? $arr[$page] : 1;
    $c_rows = isset($arr[$rows]) ? $arr[$rows] : $default;
    $offset = ($c_page - 1) * $c_rows;
    return [$offset, $c_rows];
}

function array_get($arr, $key, $default = null)
{
    if (isset($arr[$key])) {
        return $arr[$key];
    }
    return $default;
}
