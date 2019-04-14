<?php

class HttpRequest
{
    /**
     * 发送POST请求
     * 
     * @param string $url 请求的url
     * @param $data
     * @return mixed
     */
    public static function post($url, $data, $option = array())
    {
        $t1 = microtime(true);
        $rtn = array(
            'url' => $url,
            'method' => "POST",
            'request' => array(
                'header' => '',
                'data' => $data,
            ),
            'response' => array(
                'header' => '',
                'data' => '',
            ),
            'time' => 0,
            'error_no' => 0,
            'error_msg' => '',
            'http_info' => array(),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
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
        $content = curl_exec($ch);
        $rtn['request']['header'] = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        if(! empty($content)) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $rtn['response']['header'] = substr($content, 0, $header_size);
            $rtn['response']['data'] = substr($content, $header_size);
        }
        $rtn['error_no'] = curl_errno($ch);
        $rtn['error_msg'] = curl_error($ch);
        $rtn['http_info'] = curl_getinfo($ch);
        unset($rtn['http_info']['request_header']);
        curl_close($ch);
        $rtn['time'] = sprintf("%01.2f", microtime(true) - $t1);
        return $rtn;
    }

    /**
     * 发送GET请求
     * 
     * @param string $url 请求的url
     * @return mixed
     */
    public static function get($url, $option = array())
    {
        $t1 = microtime(true);
        $rtn = array(
            'url' => $url,
            'method' => "GET",
            'request' => array(
                'header' => '',
                'data' => array(),
            ),
            'response' => array(
                'header' => '',
                'data' => '',
            ),
            'time' => 0,
            'error_no' => 0,
            'error_msg' => '',
            'http_info' => array(),
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
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
        $content  = curl_exec($ch);
        $rtn['request']['header'] = curl_getinfo($ch, CURLINFO_HEADER_OUT);
        if(! empty($content)) {
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $rtn['response']['header'] = substr($content, 0, $header_size);
            $rtn['response']['data'] = substr($content, $header_size);
        }
        $rtn['error_no'] = curl_errno($ch);
        $rtn['error_msg'] = curl_error($ch);
        $rtn['http_info'] = curl_getinfo($ch);
        unset($rtn['http_info']['request_header']);
        curl_close($ch);
        $rtn['time'] = sprintf("%01.2f", microtime(true) - $t1);
        return $rtn;
    }
}