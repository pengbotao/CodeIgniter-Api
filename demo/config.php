<?php
define('DEMO_DIR', str_replace("\\", "/", realpath(dirname(__FILE__))));
define('DEMO_ENV', 'development');

if(DEMO_ENV == 'development') {
    define('REQUEST_URL', 'http://api.local/');
    define('APP_CALLER', 'test');
    define('APP_SECRET_KEY', '123456');
} elseif(DEMO_ENV == 'testing') {
    define('REQUEST_URL', 'http://api.local/');
    define('APP_CALLER', 'test');
    define('APP_SECRET_KEY', '123456');
} else {
    throw new Exception('ERROR SIGN TYPE', 0);
}

function http_request($uri, $data, $option = array())
{
    http_post($uri, $data, $option);
}

function http_get($uri, $data, $option = array())
{
    $url = REQUEST_URL . $uri;
    $data['t'] = time();
    $param = array_merge(array(
        '_id' => time(),
        '_caller' => APP_CALLER,
        '_encrypt' => 'simple',
        '_sign' => '',
    ), $data);
    $param['_sign'] = md5(APP_SECRET_KEY . $data['t']);
    $url = $url . "?" . http_build_query($param);
    echo $url . PHP_EOL . PHP_EOL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 0);
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
    if($errno = curl_errno($ch)) {
        throw new Exception(curl_error($ch), $errno);
    }
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    $header_s = substr($content, 0, $header_size);
    $body = substr($content, $header_size);
    
    $header = explode("\r\n", $header_s);
    array_shift($header);
    
    echo $header_s;
    echo $body . PHP_EOL;
    return $body;
}

function http_post($uri, $query, $k, $option = array())
{
    $url = REQUEST_URL . $uri;
    $param = array(
        'id' => time(),
        'client' => array(
            'caller' => APP_CALLER,
            'ext' => '',
        ),
        'encrypt' => 'md5',
        'sign' => '',
    );
    $data = array_merge($query, array(
        't' => time(),
    ));

    $param['data'] = $data;
    $param['sign'] = md5(APP_CALLER.get_sign_str($data). APP_SECRET_KEY);
    $data = json_encode($param);
    echo $url . PHP_EOL;
    echo $data . PHP_EOL . PHP_EOL;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
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
    if($errno = curl_errno($ch)) {
        throw new Exception(curl_error($ch), $errno);
    }
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    $header_s = substr($content, 0, $header_size);
    $body = substr($content, $header_size);
    
    $header = explode("\r\n", $header_s);
    array_shift($header);
    
    echo $header_s;
    echo $body . PHP_EOL;
    return $body;
}

function get_sign_str($data, $filter_null = false)
{
    if(! is_array($data)) {
        return '';
    }
    ksort($data);
    $sign_str = '';
    foreach($data as $key => $val) {
        if($filter_null && $val == '') {
            continue;
        }
        $sign_str .= $key.'='.$val.'&';
    }
    $sign_str = substr($sign_str, 0, -1);
    return $sign_str;
}