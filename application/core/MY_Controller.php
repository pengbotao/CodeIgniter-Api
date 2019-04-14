<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller 
{
    public function __construct($type = '') 
    {
        parent::__construct();
        $this->load->helper(array('string', 'array', 'api', 'url', 'date', 'http'));
        ! defined('API_RESPONSE_ID')  && define('API_RESPONSE_ID', get_response_id());
        ! defined('API_ALIAS_KEY')  && define('API_ALIAS_KEY', 'API_' . API_RESPONSE_ID);
        $this->load->library('log');
        include_once CI_APP_PATH.'/libraries/RedisCache.php';
        if($type) {
            $f = '_'.$type;
            if(method_exists($this, $f)) {
                $this->$f();
            }
        }
        register_shutdown_function(array($this, 'shutdown'));
    }

    public function shutdown()
    {
        foreach(get_object_vars($this) as $key => $val) {
            if(substr($key, 0, 3) == 'db_' && is_object($this->{$key}) && method_exists($this->{$key}, 'close')) {
                $this->{$key}->close();
            }
            if(substr($key, 0, 5) == 'conn_'  && is_resource($this->{$key})) {
                $this->db->_close($val);
                unset($this->{$key});
            }
        }
    }

    private function _api()
    {
        $this->load->service('api_caller_service', NULL, API_ALIAS_KEY);
        $this->api()->init();
    }

    private function _script()
    {
        $this->load->service('script_caller_service', NULL, API_ALIAS_KEY);
        $this->api()->init();
    }

    final public function api()
    {
        return $this->{API_ALIAS_KEY};
    }
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */