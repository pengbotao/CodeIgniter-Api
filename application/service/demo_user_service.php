<?php

/**
 * 示例服务层。调用流程（控制层 - 服务层 - 模型层）
 */
class Demo_User_Service extends MY_Service
{
    /**
     * 获取用户及订单（读取来自不同的数据库）
     *
     * @param int $user_id 用户ID
     * @return array
     */
    public function getUserInfo($user_id)
    {
        $this->load->model('demo_user_list_model');
        $user_info = $this->demo_user_list_model->findByPk($user_id, array(
            'status' => 1,
        ));
        if(empty($user_info)) {
            return array();
        }
        $this->load->model('demo_order_list_model');
        $order_list = $this->demo_order_list_model->findAll(array(
            'user_id' => $user_id,
        ), 10, 0, 'order_id DESC');
        return array(
            'user' => $user_info,
            'order' => $order_list,
        );
    }
}