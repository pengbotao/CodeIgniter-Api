<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Service
{
    function __get($key)
    {
        $CI = & get_instance();
        return $CI->$key;
    }
}

/* End of file MY_Service.php */
/* Location: ./application/core/MY_Service.php */