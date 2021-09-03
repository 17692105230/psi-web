<?php

namespace app\common\library\storage\engine;

use think\Exception;

/**
 * 存储引擎抽象类
 * Class server
 * @package app\common\library\storage\drivers
 */
abstract class Server
{
    /* @var $file \think\File */
    protected $file;
    protected $error;
    protected $fileName;
    protected $fileInfo;
    protected $filePath;

    // 是否为内部上传
    protected $isInternal = false;
    // 允许的最大文件大小
    protected $maxFileSize = 10;
    // 允许的文件后缀
    protected $allowedExt = [];
    // 不允许的文件后缀
    protected $notAllowedExt = ['php'];
    // 允许的mime类型
    protected $allowedMime = [];
    // 文件大小
    protected $size;
    // 文件后缀
    protected $ext;
    // 文件原始名字
    protected $originName;
    //  文件md5
    protected $fileMD5;
    // 文件sha1
    protected $fileHash;
    // 文件mime类型
    protected $fileMime;


    /**
     * 构造函数
     * Server constructor.
     */
    protected function __construct()
    {
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @throws Exception
     */
    public function setUploadFile($name)
    {
        // 接收上传的文件
        $this->file = \request()->file($name);
        if (empty($this->file)) {
            throw new Exception('未找到上传文件的信息');
        }
        // 文件信息
        $this->fileInfo = $this->file->getFileInfo();
        // 保存文件信息
        $this->saveFileInfo();

    }

    public function setMaxSize($size)
    {
        $this->maxFileSize = $size;
    }

    public function setAllowedMime($mime)
    {
        return $this->allowedMime = is_array($mime) ? $mime : explode(',', $mime);
    }

    public function setAllowedExt($ext)
    {
        $this->allowedExt = is_array($ext) ? $ext : explode(',', $ext);
    }

    public function setNotAllowedExt($ext)
    {
        $this->notAllowedExt = is_array($ext) ? $ext : explode(',', $ext);
        in_array('php', $this->notAllowedExt) || array_push($this->notAllowedExt, 'php');
    }


    /**
     * 设置上传的文件信息
     * @param string $filePath
     */
    public function setUploadFileByReal($filePath)
    {
        // 设置为系统内部上传
        $this->isInternal = true;
        // 文件信息
        $this->fileInfo = [
            'name' => basename($filePath),
            'size' => filesize($filePath),
            'tmp_name' => $filePath,
            'error' => 0,
        ];
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
    }

    public function setSaveFileName($fileName)
    {
        $this->fileName = $fileName;
    }


    /**
     * 文件上传
     * @return mixed
     */
    abstract protected function upload();

    /**
     * 文件删除
     * @param $fileName
     * @return mixed
     */
    abstract protected function delete($fileName);

    /**
     * 返回上传后文件路径
     * @return mixed
     */
    abstract public function getFileName();

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    protected function getRealPath()
    {
        return $this->getFileInfo()->getRealPath();
    }

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    public function setSaveName($fileName)
    {
        $this->fileName = $fileName;
    }

    public function setSavePath($savePath)
    {
        $this->filePath = $savePath;

    }

    /**
     * 生成保存文件名
     */
    protected function buildSaveName()
    {
        // 扩展名
        $ext = $this->file->extension();
        // 使用设置的文件名字
        if ($this->fileName) {
            return $this->fileName . ".{$ext}";
        }
        // 自动生成文件名
        $this->fileName = md5(uniqid()) . ".{$ext}";
        return $this->fileName;
    }

    protected function buildSavePath()
    {
        if (empty($this->filePath)) {
            return '/storage/' . date('Ymd') . '/';
        }
        return $this->filePath;
    }

    protected function validate()
    {
        // 验证文件大小
        if ($this->maxFileSize != 0) {
            if ($this->file->getSize() > $this->maxFileSize * 1024 * 1024) {
                $this->error = '文件过大！允许最大的上传文件大小为' . $this->maxFileSize . 'M';
                return false;
            }
        }
        // 验证mime
        if (!empty($this->allowedMime)) {
            if (!in_array($this->file->getMime(), $this->allowedMime)) {
                $this->error = '不允许的MIME类型';
                return false;
            }
        }
        // 允许的后缀
        if (!empty($this->allowedExt)) {
            if (!in_array($this->file->extension(), $this->allowedExt)) {
                $this->error = '不允许上传的文件后缀';
                return false;
            }
        }
        // 不允许的文件后缀
        if (in_array($this->file->extension(), $this->notAllowedExt)) {
            $this->error = '不允许的文件后缀';
            return false;
        }
        return true;
    }

    public function saveFileInfo()
    {
        $this->ext = $this->file->extension();
        $this->fileMD5 = $this->file->md5();
        $this->fileHash = $this->file->hash();
        $this->size = $this->file->getSize();
        $this->fileMime = $this->file->getMime();
        $this->originName = $this->file->getOriginalName();

    }

    public function getUploadFileInfo()
    {
        return [
            'origin_name' => $this->originName,
            'size' => $this->size,
            'ext' => $this->ext,
            'md5' => $this->fileMD5,
            'sha1' => $this->fileHash,
        ];
    }

}
