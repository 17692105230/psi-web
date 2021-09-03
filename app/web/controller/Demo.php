<?php


namespace app\web\controller;


use app\common\library\storage\Driver as FileDriver;

class Demo  extends Controller
{

    public function uploadDemo()
    {
        //1. 获取文件上传对象   local指使用本地文件上传
        $driver = new FileDriver(config('upload'), 'local');
        //2. 设置POST文件对象name
        $driver->setUploadFile('image');
        /********     可选操作      ********/
        //3. 设置最大文件大小单位：M (非必须的步骤 默认为10M   0不限制大小)
        $driver->setMaxSize(8);
        //4. 设置文件后缀验证 (非必须的步骤 默认全部允许)
        $driver->setAllowedExt(['jpg', 'jpeg', 'png', 'gif']);
        //5. 设置不允许的文件后缀 (非必须的步骤 默认不允许php后缀的文件上传)
        $driver->setNotAllowedExt(['php']);
        //6. 设置文件的MIME验证 (非必须步骤 默认不限制)
        $driver->setAllowedMime(['image/jpeg', 'image/png', 'image/gif']);
        //7. 设置保存的目录 (非必须步骤 默认放到/storage/date('Ymd') )
        $driver->setSavePath('/storage/goods/demo/');
        //8. 设置保存的不带文件后缀的名字 (非必要步骤 不设置会生成为长度为32位的随机文件名)
        $driver->setSaveName('123');
        /********     可选操作     ********/
        //9. 执行文件上传操作
        $res = $driver->upload();
        //10. 判断上传失败
        if (!$res) {
            return $this->renderError($driver->getError());
        }
        //11. 获取文件保存的地址
        $file_name = $driver->getFileName();
        //12. 获取文件原始信息(大小,原始大小,扩展名,md5,sha1)
        $fileInfo = $driver->getUploadFileInfo();
        return $this->renderSuccess(array_merge(['file_name' => $file_name], $fileInfo));
    }

}