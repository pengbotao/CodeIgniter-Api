<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Demo_user_list_model Extends MY_Model
{
    /**
     * 主键
     */
    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * 表名称
     */
    public function tableName()
    {
        return 'user_list';
    }
}

/* End of file user_list_model.php */
/* Location: ./application/models/user_list_model.php */