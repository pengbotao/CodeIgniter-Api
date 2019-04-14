<?php
/**
 * 示例控制器。控制器中type=api表示走接口认证协议。
 */
class demo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('api');
    }

    /**
     * 示例方法
     */
    public function index()
    {
        $user_id = intval($this->api()->get('user_id'));
        if($user_id < 0) {
            $this->api()->output(CODE_API_INVALID_PARAM);
        }
        if($user_id == 0) {
            $this->api()->output(CODE_API_INVALID_PARAM, "请输入用户ID");
        }
        $this->load->service('demo_user_service');
        $data = $this->demo_user_service->getUserInfo($user_id);
        $this->api()->output(CODE_API_SUCCESS, '', $data);
    }
}