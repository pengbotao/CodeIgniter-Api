<?php
/**
 * 发送POST请求
 * 
 * @param string $url 请求的url
 * @param array $data
 * @return mixed
 * @throws System_Exception
 */
function http_post($url, $data, $option = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    if (preg_match("/^https:\/\//", $url)) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
    }
    if(! empty($option)) {
        foreach($option as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
    }
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type:application/json;charset=utf-8'));
    $data = curl_exec($ch);
    if($errno = curl_errno($ch)) {
        //throw new System_Exception("HTTP POST ERROR: " . curl_error($ch), $errno);
    }
    curl_close($ch);
    return $data;
}

/**
 * 发送GET请求
 * 
 * @param string $url 请求的url
 * @return mixed
 * @throws System_Exception
 */
function http_get($url, $option = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    if (preg_match("/^https:\/\//", $url)) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    if(! empty($option)) {
        foreach($option as $key => $val) {
            curl_setopt($ch, $key, $val);
        }
    }
    $data  = curl_exec($ch);
    if($errno = curl_errno($ch)) {
        //throw new System_Exception("HTTP GET ERROR: " . curl_error($ch), $errno);
    }
    //print_r(curl_getinfo($ch));
    curl_close($ch);
    return $data;
}