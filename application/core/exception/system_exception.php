<?php
/**
 * 系统异常
 */
class System_Exception extends Exception
{
    protected $data = array();

    public function __construct($message = '', $code = 0, $data = array())
    {
        if(empty($message)) {
            $message = CODE_API_SYSTEM_ERROR;
        }
        $code_default = substr(CODE_API_SYSTEM_ERROR, 0, strpos(CODE_API_SYSTEM_ERROR, ":"));
        $pos = strpos($message, ":");
        if($pos !== false && is_numeric(substr($message, 0, $pos))) {
            $code_default = substr($message, 0, $pos);
            $message = substr($message, $pos + 1);
        }
        $code = empty($code) ? $code_default : $code;
        $this->data = is_array($data) ? $data : array();
        parent::__construct($message, $code);
    }

    public function getData()
    {
        return $this->data;
    }
}