<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb');
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b');
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

define('PHP_SCRIPT_PATH', '/usr/local/php/bin/php');
define('HTTP_METHOD', isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : '');

//DEMO示例数据库（多数据库配置常量，对应database.php中的KEY）
define('DB_GROUP_DEMO', 'demo');

define('SQL_LOG_WRITE', TRUE);
define('API_LOG_WRITE', TRUE);
define('API_SLOW_LOG_WRITE', 60);

define("API_RESPONSE_EXT", "API:RESPONSE:EXT");

define('CODE_API_SUCCESS', '0:操作成功');
define('CODE_API_INVALID_PARAM', '1:参数错误');
define('CODE_API_INVALID_SIGN', '2:签名错误');
define('CODE_API_FORBID_CALLER', '3:账号已被禁用');
define('CODE_API_INVALID_PERMISSION', '4:无权限');
define('CODE_API_EXPIRED', '5:请求已过期');
define('CODE_API_FAILURE', '100:操作失败');
define('CODE_API_EXCEPTION', '101:系统异常');
define('CODE_API_SYSTEM_ERROR', '500:系统错误');

/* End of file constants.php */
/* Location: ./application/config/constants.php */