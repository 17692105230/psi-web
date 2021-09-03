<?php


namespace app\web\logic;


class BaseLogic
{
    protected $error;
    protected $result = [];
    protected $errorCode=0;

    public function getError($default = '')
    {
        if (!empty($this->error)) {
            return $this->error;
        }
        return $default;
    }

    public function setError($error)
    {
        if (empty($this->error)) {
            $this->error = $error;
        } else {
            $this->error = $this->error . '/' . $error;
        }
    }

    public function setResult($data)
    {
        $this->result = array_merge($this->result, $data);
    }

    public function getResult($key = '')
    {
        if (!empty($key)) {
            if (isset($this->result[$key])) {
                return $this->result[$key];
            }
            return [];
        }
        return $this->result;
    }

    /**
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * @param int $errorCode
     */
    public function setErrorCode(int $errorCode): void
    {
        $this->errorCode = $errorCode;
    }
}