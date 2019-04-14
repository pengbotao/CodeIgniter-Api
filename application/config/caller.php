<?php

if(ENVIRONMENT == "production") {
    $config['caller_list'] = array(
        array(
            'caller' => 'read',
            'api_secret' => 'a7c57a67717aa8efecf1f5bb9f2d600d',
            'is_forbid' => false,
            //是否校验时间参数
            'is_valid_time' => false,
            //只允许
            'api_allow_list' => array(
                '//demo/index/'
            ),
            //会被禁止访问的action，可调整api_sign_service._auth方法实现自定义。
            'api_forbid_list' => array(),
        ),
    );
} else {
    $config['caller_list'] = array(
        array(
            'caller' => 'test',
            'api_secret' => '123456',
            'is_forbid' => false,
        ),
    );
}
