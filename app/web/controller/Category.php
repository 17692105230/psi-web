<?php


namespace app\web\controller;

use app\web\model\Category as CategoryModel;
use app\web\validate\Category as CategoryValidate;


class Category extends Controller
{

    public function loadData()
    {
        $model = new  CategoryModel();
        $data = $model->loadTreeGrid($this->getData());
        return json($data);
    }

    public function edit()
    {
        $data = $this->request->post();
        $validate = new CategoryValidate();
        if (!$validate->scene('edit')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new CategoryModel();
        $result = $model->edit($this->getData($data));
        if ($result) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError() ?: '修改失败');
    }

    public function delete()
    {
        $data = $this->request->post();
        $validate = new CategoryValidate();

        if (!$validate->scene('delete')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new CategoryModel();
        $result = $model->del($this->getData($data));

        if ($result) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError() ?: '删除失败');
    }

    public function add()
    {
        $data = $this->request->post();
        $validate = new CategoryValidate();
        if (!$validate->scene('add')->check($data)) {
            return $this->renderError($validate->getError());
        }
        $model = new CategoryModel();
        $result = $model->add($this->getData($data));
        if ($result) {
            return $this->renderSuccess();
        }
        return $this->renderError($model->getError() ?: '添加失败');
    }
}