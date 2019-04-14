<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Api
{
    function __get($key)
    {
        $CI = & get_instance();
        return $CI->$key;
    }
}

/* End of file MY_Api.php */
/* Location: ./application/core/MY_Api.php */