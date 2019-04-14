<?php
//ob_start(function_exists('ob_gzhandler') ? 'ob_gzhandler' : null);
class Api_Sign_Service extends MY_Service
{
    /**
     * 请求唯一标识
     * @var int
     */
    public $id;

    /**
     * 请求客户端信息 
     * @var array
     */
    public $client;

    /**
     * 请求业务数据
     * @var array
     */
    public $data;

    /**
     * 加密方式，目前支持md5、simple
     * @var string
     */
    public $encrypt;

    /**
     * 签名参数
     * @var string
     */
    public $sign;

    /**
     * ext返回数据
     */
    private $apiResponseExt;

    protected $_callerInfo;

    public function init()
    {
        if(HTTP_METHOD == "POST") {
            $this->_initParamFromPost();
        } else if(HTTP_METHOD == "GET") {
            $this->_initParamFromGet();
        } else {
            http_404();
        }
        //校验ID和caller
        if(empty($this->id) || empty($this->client['caller']) || strlen($this->id) > 100) {
            $this->output(CODE_API_INVALID_PARAM);
        }
        //签名校验
        if($this->_verify() == false) {
            $this->output(CODE_API_INVALID_SIGN);
        }
        //权限控制
        if(method_exists($this, '_auth')) {
            if($this->_auth() == false) {
                $this->output(CODE_API_INVALID_PERMISSION);
            }
        }
        if(method_exists($this, 'initialize')) {
            $this->initialize();
        }
        log_message('debug', API_RESPONSE_ID . ' Finish API Signature');
    }

    /**
     * 从GET获取数据
     */
    private function _initParamFromGet()
    {
        $_id = $this->input->get('_id');
        $_caller = $this->input->get('_caller');
        $this->encrypt = strtolower($this->input->get('_encrypt'));
        $this->sign = $this->input->get('_sign');
        if(empty($_id) || empty($_caller) || empty($this->encrypt) || empty($this->sign)) {
            $this->output(CODE_API_INVALID_PARAM);
        }
        $this->id = strval($_id);
        $this->client = array(
            'caller' => $_caller,
        );
        $this->data = $this->input->get();
        unset($this->data['_id']);
        unset($this->data['_caller']);
        unset($this->data['_encrypt']);
        unset($this->data['_sign']);
    }

    private function _initParamFromPost()
    {
        $data_input  = file_get_contents('php://input');
        $data = json_decode($data_input, true);
        if(empty($data) || ! is_array($data)
        || ! isset($data['id']) || ! isset($data['client'])
        || ! isset($data['client']['caller'])
        || ! isset($data['data']) || ! isset($data['encrypt'])
        || ! isset($data['sign']) || empty($data['sign'])
        ) {
            $this->output(CODE_API_INVALID_PARAM);
        }
        $this->id = $data['id'];
        $this->client = $data['client'];
        $this->data = $data['data'];
        $this->encrypt = strtolower($data['encrypt']);
        $this->sign = $data['sign'];
    }

    /**
     * 签名校验
     */
    protected function _verify()
    {
        if(false && ENVIRONMENT == "development") {
            $this->id = API_RESPONSE_ID;
            $this->client = array(
                'caller' => 'dev',
            );
            return true;
        }
        $caller = $this->getCallerInfo($this->client['caller']);
        if(! is_array($caller) || empty($caller)) {
            $this->output(CODE_API_INVALID_SIGN);
        }
        if(! isset($caller['caller']) || ! isset($caller['api_secret']) || $caller['is_forbid'] == true) {
            $this->output(CODE_API_FORBID_CALLER);
        }
        //校验时间
        $n = time();
        $t = isset($this->data['t']) ? intval($this->data['t']) : 0;
        if (! isset($caller['is_valid_time']) || $caller['is_valid_time'] == true) {
            if($t <= 0 || abs($n - $t) > 1800) {
                $this->output(CODE_API_EXPIRED);
            }
        }
        //校验签名方法
        if(! in_array($this->encrypt, array('md5', 'simple'))) {
            $this->output(CODE_API_INVALID_SIGN);
        }
        $sign = NULL;
        if($this->encrypt == 'simple') {//simple校验方法
            $sign = md5($caller['api_secret'] . $t);
        } else if($this->encrypt == 'md5') {//md5校验方法
            $sign = generate_sign($this->data, $caller['caller'], $caller['api_secret']);
        }
        if($sign != $this->sign) {
            $this->output(CODE_API_INVALID_SIGN, '', array(
                'sign' => get_sign_str($this->data),
            ));
        }
        return true;
    }

    /**
     * 权限控制
     */
    protected function _auth()
    {
        $caller = $this->_callerInfo;
        $ip = $this->input->ip_address();
        $directory = $this->router->fetch_directory();
        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        $action = isset($this->data['action']) ? strtolower($this->data['action']) : '';
        $flag = "/" . $directory . "/" . $class . "/" . $method . "/" . $action;
        //设置只允许访问的ACTION
        if(isset($caller['api_allow_list']) && ! empty($caller['api_allow_list'])) {
            foreach($caller['api_allow_list'] as $val) {
                if($flag == $val) {
                    return true;
                }
            }
            return false;
        }
        //设置只禁止访问的ACTION
        if(isset($caller['api_forbid_list']) && ! empty($caller['api_forbid_list'])) {
            foreach($caller['api_forbid_list'] as $val) {
                if($flag == $val) {
                    return false;
                }
            }
            return true;
        }
        return true;
    }

    /**
     * 获取请求参数
     * 
     * @param string $key
     * @return string
     */
    public function get($key = '')
    {
        if(empty($key)) {
            return $this->data;
        }
        if($key == API_RESPONSE_EXT) {
            return $this->apiResponseExt;
        }
        if(isset($this->data[$key])) {
            return $this->data[$key];
        }
        return '';
    }

    /**
     * 设置返回参数
     *
     * @param string|array $key
     * @param string $val
     * @return $this
     */
    public function set($key, $val)
    {
        if($key == API_RESPONSE_EXT) {
            $this->apiResponseExt = $val;
        }
        return $this;
    }

    /**
     * 输出
     * @param int|string $code 错误码
     * @param string $msg 错误描述
     * @param array $data 返回数据
     * @param array $ext 扩展数据
     */
    public function output($code, $msg = '', $data= array(), $ext = array())
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
            $msg = $code == 0 ? '操作成功' : '操作失败';
        }
        if(empty($data)) {
            $data = (object)array();
        }
        $res = array(
            'id' => API_RESPONSE_ID,
            'status' => array(
                'code' => (int)$code,
                'msg' => (string)$msg,
            ),
            'data' => $data
        );
        if(! empty($ext)) {
            $res['status']['ext'] = $ext;
        } elseif(! empty($this->apiResponseExt)) {
            $res['status']['ext'] = $this->apiResponseExt;
        }
        $res = json_encode($res);
        header("Content-type:application/json;charset=utf-8");

        $elapsed_time = get_elapsed_time($this->benchmark->marker['total_execution_time_start'], microtime());
        if(defined('API_LOG_WRITE') && API_LOG_WRITE) {
            $directory = $this->router->fetch_directory();
            $class = $this->router->fetch_class();
            $method = $this->router->fetch_method();
            $action = $class . ":" . $method;
            if(! empty($directory)) {
                $action = $directory . ":" . $action;
            }
            $this->log->json(array(
                'time' => time(),
                'env' => ENVIRONMENT,
                'from' => strval($this->client['caller']),
                'trace_id' => strval($this->id),
                'action' => $action,
                'code' => $code,
                'msg' => $msg,
                'client_ip' => $this->input->ip_address(),
                'server_ip' => $_SERVER['SERVER_ADDR'],
                'response_id' => API_RESPONSE_ID,
                'duration' => $elapsed_time,
                'param' => $this->get(),
                'response' => $res,
                'ext' => new stdClass(),
            ), "apilog/log");
        }
        log_message('debug', API_RESPONSE_ID . ' End');
        echo $res;exit;
    }
}