<?php

/**
 * 获取本月第一天
 * 
 * @param datetime $date
 * @return string
 */
function get_first_date($date)
{
    return date("Y-m-01", strtotime($date));
}

/**
 * 获取本月最后一天
 *
 * @param datetime $date
 * @return string
 */
function get_last_date($date)
{
    return date('Y-m-t', strtotime($date));
}

/**
 * 是否为时间格式
 *
 * @param datetime $date
 * @return boolean
 */
function is_date($date)
{
    if (strtotime($date)) {
        return true;
    }
    return false;
}

/**
 * 当前毫秒数
 *
 * @return number
 */
function millis($len = 3)
{
    list ($usec, $sec) = explode(" ", microtime());
    return round(((float) $usec + (float) $sec) * pow(10, $len));
}