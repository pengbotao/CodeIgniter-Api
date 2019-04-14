<?php
/**
 * 脚本流程。控制器中type传入为script，该参数不会走签名校验。
 */
class demo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('script');
    }

    public function user_order_report()
    {
        
    }
}