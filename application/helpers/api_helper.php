<?php

/**
 * HTTP 404页面
 */
function http_404()
{
    //header("HTTP/1.0 404 Not Found");
    echo <<< EOT
<h1>404 Not Found</h1>
EOT;
    exit;
}

/**
 * 系统输出
 *
 * @param int $code
 * @param string $msg
 * @param array $data
 * @param string $join_type
 */
function http_output($code , $msg = '', $data= array(), $ext = array())
{
    ob_clean();
    $pos = strpos($code, ":");
        if($pos !== false) {
            if(empty($msg)) {
                $msg = substr($code, $pos + 1);
            }
            $code = substr($code, 0, $pos);
        }
    if(empty($msg)) {
        $msg = '操作成功';
    }
    $res = array(
        'id' => API_RESPONSE_ID,
        'status' => array(
            'code' => (int)$code,
            'msg' => (string)$msg,
        ),
        'data' => (object)$data
    );
    if(! empty($ext)) {
        $res['status']['ext'] = $ext;
    }
    $res = json_encode($res);
    header("Content-type:application/json;charset=utf-8");
    echo $res;exit;
}

/**
 * 获取两个时间差
 * @param string $start_time
 * @param string $end_time
 * @return float
 */
function get_elapsed_time($start_time, $end_time)
{
    list($usec_start, $sec_start) = explode(" ", $start_time);
    list($usec_end, $sec_end) = explode(" ", $end_time);
    return sprintf("%01.2f", ((float)$usec_end + (float)$sec_end) - ((float)$usec_start + (float)$sec_start));
}

/**
 * 是否是合法的手机号
 * @param string $mobile
 * @return boolean
 */
function is_valid_mobile($mobile)
{
    if(empty($mobile)) {
        return false;
    }
    if(preg_match('/^(13|14|15|16|17|18|19)\d{9}$/', $mobile)) {
        return true;
    }
    return false;
}

/**
 * 是否是合法的密码
 * @param string $password
 * @return boolean
 */
function is_valid_passwd($passwd)
{
    if(empty($passwd) || strlen($passwd) < 6 || strlen($passwd) > 100) {
        return false;
    }
    return true;
}

/**
 * 生成密码
 * @param string $passwd
 * @param string $salt
 * @return string
 */
function gen_pwd($passwd, $salt = '')
{
    return md5($salt . md5($passwd) . $salt);
}

/**
 * 获取签名字符串
 * 
 * @param array $data
 * @param string $filter_null
 * @return string
 */
function get_sign_str($data, $filter_null = false)
{
    if(! is_array($data)) {
        return '';
    }
    ksort($data);
    $sign_str = '';
    foreach($data as $key => $val) {
        if(is_array($val)) {
            return uniqid();
        }
        if($filter_null && $val == '') {
            continue;
        }
        $sign_str .= $key.'='.$val.'&';
    }
    $sign_str = substr($sign_str, 0, -1);
    return $sign_str;
}

/**
 * 根据caller+secret_key生成签名
 * @param array $data
 * @param string $caller
 * @param string $secret_key
 * @return string
 */
function generate_sign($data, $caller, $secret_key)
{
    $sign_str = get_sign_str($data);
    $sign_str = $caller.$sign_str.$secret_key;
    return md5($sign_str);
}

/**
 * 应用版本检测
 *
 * @param string $ver_1 版本1
 * @param string $ver_2 版本2
 * @param int $level
 * @return int 返回1表示版本1大于版本2，返回-1表示小于，返回0表示相等
 */
function ver_comp($ver_1, $ver_2, $level = 5)
{
    $ver_1 = explode(".", $ver_1, $level);
    $ver_2 = explode(".", $ver_2, $level);

    for($i = 0; $i< $level; $i++) {
        if(! isset($ver_1[$i])) {
            $ver_1[$i] = '';
        }
        if(! isset($ver_2[$i])) {
            $ver_2[$i] = '';
        }
        $m = strcmp($ver_1[$i], $ver_2[$i]);
        if($m > 0) {
            return 1;
        } elseif ($m < 0) {
            return -1;
        }
    }
    return 0;
}


/**
 * 校验指定IP是否匹配
 * @param string $ip
 * @param string $ip_allow_str
 * @return boolean
 */
function is_allowed_ip($ip, $ip_allow_str)
{
    if($ip_allow_str == '*') {
        return true;
    }
    if(empty($ip_allow_str)) {
        return false;
    }
    $ip = explode(".", $ip, 4);
    $ip_allow = explode(".", $ip_allow_str, 4);
    if(count($ip) != 4 || count($ip_allow) != 4) {
        return false;
    }
    for($i = 0; $i<4; $i++) {
        if(! isset($ip_allow[$i])) {
            $ip_allow[$i] = '*';
        }
        if($ip_allow[$i] == '*') {
            continue;
        }
        if($ip_allow[$i] != $ip[$i]) {
            return false;
        }
    }
    return true;
}

function get_response_id()
{
    list($usec, $sec) = explode(" ", microtime());
    return date("YmdHis") . sprintf("%06s", round($usec * pow(10, 6)));
}

function is_prod()
{
    return ENVIRONMENT == "production";
}

