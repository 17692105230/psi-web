<?php

namespace app\web\controller;

/**
 * 首页
 * Class Index
 * @package app\admin\controller
 */
class Main extends Controller
{
    public function index()
    {
        return $this->fetch();
    }
}