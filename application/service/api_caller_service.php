<?php

include_once CI_APP_PATH.'/service/api_sign_service.php';

class Api_Caller_Service extends Api_Sign_Service
{
    /**
     * 获取不同接口的请求者信息
     */
    public function getCallerInfo($caller)
    {
        $this->load->config('caller', TRUE);
        $cfg_caller = $this->config->item('caller');
        if(empty($cfg_caller) || ! is_array($cfg_caller) || ! isset($cfg_caller['caller_list'])) {
            return false;
        }
        foreach($cfg_caller['caller_list'] as $val) {
            if($caller == $val['caller']) {
                $this->_callerInfo = $val;
                return $val;
            }
        }
        return false;
    }
}