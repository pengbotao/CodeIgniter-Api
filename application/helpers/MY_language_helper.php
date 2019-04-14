<?php

/**
 * Rewrite lang methods to support parameter binding
 * @param string $line Language key
 * @param array $param Bind Param
 * @return string
 */
function lang($line, $param = array())
{
    $CI =& get_instance();
    $line = $CI->lang->line($line);
    if(is_array($param) && count($param) > 0) {
        array_unshift($param, $line);
        $line = call_user_func_array('sprintf', $param);
    }
    return $line;
}

/**
 * Return Setting Config Value
 *
 * @param string $key
 * @param string $sub_key
 * @return string|array
 */
function lang_setting($key, $sub_key = NULL)
{
    $CI = & get_instance();
    $CI->load->config('setting', true);
    $setting = $CI->config->item('setting');
    $key = 'setting_'.$key;
    if(! isset($setting[$key])) {
        return null;
    }
    if($sub_key === NULL) {
        return $setting[$key];
    }
    if(! isset($setting[$key][$sub_key])) {
        return null;
    }
    return $setting[$key][$sub_key];
}