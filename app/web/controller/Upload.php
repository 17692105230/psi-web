<?php


namespace app\web\controller;

use app\Request;
use app\web\model\GoodsAssist;
use think\facade\Filesystem;

class Upload extends Controller
{
    private $config;
    private $dir_path;
    private $disk_type;
    private $field_name;
    public function initialize()
    {
        parent::initialize(); // TODO: Change the autogenerated stub
        $this->dir_path="image";
        $this->disk_type = "public";
        $this->field_name = "image";
        $this->config = config("upload");
    }

    //使用不同的驱动上传
    public function uploadDriver($field_name="") {
        if (empty($this->field_name) && empty($filed_name)){
            return $this->renderError("请提供要上传的文件字段名");
        }
        if (!empty($filed_name)) {
            $this->field_name = $filed_name;
        }
        // 实例化存储驱动
        $StorageDriver = new Driver($this->config);
        // 设置上传文件的信息
        $StorageDriver->setUploadFile($this->field_name);
        // 上传图片
        if (!$StorageDriver->upload()) {
            return $this->renderError('图片上传失败' . $StorageDriver->getError());
        }
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = $StorageDriver->getFileInfo();
        $engine = $this->config['default'];
        $domain = isset($this->config['engine'][$engine]['domain']) ? $this->config['engine'][$engine]['domain'] : $this->request->domain();
        // 图片上传成功
        $data = [
            'url' => $domain . '/' . $fileName,
            'storage' => $engine,
            'fileInfo' => $fileInfo,
        ];
        return $this->renderSuccess($data,'图片上传成功');
    }

    //单文件上传
    public function uploadOne($filed_name='') {
        if (empty($this->field_name) && empty($filed_name)){
            return $this->renderError("请提供要上传的文件字段名");
        }
        if (!empty($filed_name)) {
            $this->field_name = $filed_name;
        }
        $file = request()->file($this->field_name);
        $file_data["savename"] = Filesystem::disk($this->disk_type)->putFile($this->dir_path, $file);
        if (!$file_data["savename"]) {
            return $this->renderError("上传失败");
        }
        $this->config = config("filesystem");
        $path_prefix = $this->config["disks"][$this->disk_type]["url"]??$this->config["disks"][$this->config["default"]]["root"];
        $file_data["savename"] = $path_prefix."/".$file_data["savename"];
        $file_data["ext"] = $file->extension();
        $file_data["hash"] = $file->hash();
        $file_data["origialname"] = $file->getOriginalName();
        $file_data["mime"] = $file->getOriginalMime();
        $file_data["md5"] = $file->md5();
        return $file_data;
    }
    //多文件上传
    public function uploadMulti($filed_name='') {
        if (empty($this->field_name) && empty($filed_name)){
            return $this->renderError("请提供要上传的文件字段名");
        }
        if (!empty($filed_name)) {
            $this->field_name = $filed_name;
        }
        $files = request()->file($this->field_name);
        $file_datas = [];
        $this->config = config("filesystem");
        $path_prefix = $this->config["disks"][$this->disk_type]["url"]??$this->config["disks"][$this->config["default"]]["root"];
        foreach ($files as $file) {
            $d["savename"] = Filesystem::disk($this->disk_type)->putFile($this->dir_path, $file);
            if($d["savename"]) {
                $d["savename"] = $path_prefix."/".$d["savename"];
                $d["ext"] = $file->extension();
                $d["hash"] = $file->hash();
                $d["origialname"] = $file->getOriginalName();
                $d["mime"] = $file->getOriginalMime();
                $d["md5"] = $file->md5();
                $file_datas[] = $d;
            }
            $d = [];
        }
        return $this->renderSuccess($file_datas, "上传成功");
    }

    public function imagesUpload(Request $request){
        $goodsCode = $request->post('goods_code');
        $mainImg = $request->post('main_img');
        $size = $request->post('size');
        $info = $this->uploadOne("file");
        $goods_assist = new GoodsAssist();
        $data = [
            'goods_id' => 0,
            'assist_category' => 'image',
            'assist_name' => $info['origialname'],
            'assist_extension' => $info['ext'],
            'assist_url' => str_replace("\\",'/', $info['savename']),
            'assist_size' => $size,
            'create_time' => time(),
            'assist_sort' => '',
            'assist_md5' => $info['md5'],
            'assist_sha1' => $info['hash'],
            'status' => 1,
            'com_id' => $this->com_id,
            'type' => 'localWeb',
        ];
        $res2 = $goods_assist->save($data);
        if(!$res2){
            return $this->renderError('图片保存失败');
        }
        return $this->renderSuccess(['path' => $info['savename'], 'assist_id' =>$goods_assist['assist_id'] ],'上传成功');

    }

    /**
     * @return mixed
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * @param mixed $field_name
     */
    public function setFieldName($field_name): void
    {
        $this->field_name = $field_name;
    }

    /**
     * @return mixed
     */
    public function getDiskType()
    {
        return $this->disk_type;
    }

    /**
     * @param mixed $disk_type
     */
    public function setDiskType($disk_type): void
    {
        $this->disk_type = $disk_type;
    }

    /**
     * @return mixed
     */
    public function getDirPath()
    {
        return $this->dir_path;
    }

    /**
     * @param mixed $dir_path
     */
    public function setDirPath($dir_path): void
    {
        $this->dir_path = $dir_path;
    }


}