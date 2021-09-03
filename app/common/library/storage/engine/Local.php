<?php

namespace app\common\library\storage\engine;

/**
 * 本地文件驱动
 * Class Local
 * @package app\common\library\storage\drivers
 */
class Local extends Server
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 上传图片文件
     * @return array|bool
     */
    public function upload()
    {
        return $this->isInternal ? $this->uploadByInternal() : $this->uploadByExternal();
    }

    /**
     * 外部上传(指用户上传,需验证文件类型、大小)
     * @return bool
     */
    private function uploadByExternal()
    {
        if (!$this->validate()) {
            return false;
        }
        $this->fileName = $this->buildSaveName();
        $this->filePath = $this->buildSavePath();

        $this->file->move('.' . $this->filePath, $this->fileName);
        return true;
    }

    /**
     * 内部上传(指系统上传,信任模式)
     * @return bool
     */
    private function uploadByInternal()
    {
        // 上传目录
        $uplodDir = WEB_PATH . 'uploads';
        // 要上传图片的本地路径
        $realPath = $this->getRealPath();
        if (!rename($realPath, "{$uplodDir}/$this->fileName")) {
            $this->error = 'upload write error';
            return false;
        }
        return true;
    }

    /**
     * 删除文件
     * @param $fileName
     * @return bool|mixed
     */
    public function delete($fileName)
    {
        // 文件所在目录
        $filePath = '.'.$fileName;
        return !file_exists($filePath) ?: unlink($filePath);
    }

    /**
     * 返回文件路径
     * @return mixed
     */
    public function getFileName()
    {
        return $this->filePath.$this->fileName;
    }

}
