<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Demo_order_list_model Extends MY_Model
{
    public function __construct()
    {
        parent::__construct(DB_GROUP_DEMO);
    }

    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'order_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'order_list';
    }
}

/* End of file demo_order_list_model.php */
/* Location: ./application/models/demo_order_list_model.php */