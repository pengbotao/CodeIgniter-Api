<?php

/**
 * 是否全英文
 */
function is_english_char($str)
{
    return preg_match("/^[A-Za-z]+$/", $str);
}

/**
 * 校验年月日
 */
function is_vaild_date($date)
{
    if(empty($date)) {
        return false;
    }
    return strtotime($date) ? true : false;
}