<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Loader extends CI_Loader
{
    protected $_ci_service_paths;

    protected $_ci_supplier_paths;

    public function __construct()
    {
        parent::__construct();
        $this->_ci_service_paths = array(APPPATH.'service');
        $this->_ci_supplier_paths = array(APPPATH.'supplier');
        $this->_ci_api_paths = array(APPPATH.'api');
    }

    public function service($service_name = '', $params = NULL, $object_name = NULL)
    {
        load_class('Service', 'core');
        if(is_array($service_name)) {
            foreach($service_name as $class) {
                $this->service($class, $params);
            }
            return;
        }
        if($service_name == '' or isset($this->_base_classes[$service_name])) {
            return FALSE;
        }
        if(! is_null($params) && ! is_array($params)) {
            $params = NULL;
        }
        $this->_ci_load_user_class($service_name, $params, $object_name, $this->_ci_service_paths);
    }

    public function api($service_name = '', $params = NULL, $object_name = NULL)
    {
        load_class('Api', 'core');
        if(is_array($service_name)) {
            foreach($service_name as $class) {
                $this->service($class, $params);
            }
            return;
        }
        if($service_name == '' or isset($this->_base_classes[$service_name])) {
            return FALSE;
        }
        if(! is_null($params) && ! is_array($params)) {
            $params = NULL;
        }
        $this->_ci_load_user_class($service_name, $params, $object_name, $this->_ci_api_paths);
    }

    public function _ci_load_user_class($class = '', $params = NULL, $object_name = NULL, $class_path = array(APPPATH))
    {
        $class = str_replace('.php', '', trim($class, '/'));
        $subdir = '';
        if (($last_slash = strrpos($class, '/')) !== FALSE) {
            $subdir = substr($class, 0, $last_slash + 1);
            $class = substr($class, $last_slash + 1);
        }
        foreach (array(ucfirst($class), strtolower($class)) as $class) {
            $is_duplicate = FALSE;
            foreach ($class_path as $path) {
                $filepath = $path.'/'.$subdir.$class.'.php';
                if ( ! file_exists($filepath)) {
                    continue;
                }
                if (in_array($filepath, $this->_ci_loaded_files)) {
                    if ( ! is_null($object_name)) {
                        $CI =& get_instance();
                        if ( ! isset($CI->$object_name)) {
                            return $this->_ci_init_class($class, '', $params, $object_name);
                        }
                    }
                    $is_duplicate = TRUE;
                    log_message('debug', $class." class already loaded. Second attempt ignored.");
                    return;
                }
                include_once($filepath);
                $this->_ci_loaded_files[] = $filepath;
                return $this->_ci_init_class($class, '', $params, $object_name);
            }
        }
        if ($is_duplicate == FALSE) {
            log_message('error', "Unable to load the requested class: ".$class);
            throw new System_Exception("Unable to Load Class: " . $class);
            //show_error("Unable to load the requested class: ".$class);
        }
    }

    public function database($params = '', $return = FALSE, $active_record = NULL, $group_name = '')
    {
        $CI =& get_instance();
        if (class_exists('CI_DB') AND $return == FALSE AND $active_record == NULL AND isset($CI->db) AND is_object($CI->db)) {
            return FALSE;
        }
        if(file_exists(APPPATH.'core/database/DB.php')) {
            require_once(APPPATH.'core/database/DB.php');
        } else {
            require_once(BASEPATH.'database/DB.php');
        }
        if ($return === TRUE) {
            return DB($params, $active_record, $group_name);
        }
        $CI->db = '';
        $CI->db =& DB($params, $active_record, $group_name);
    }
}

/* End of file MY_Loader.php */
/* Location: ./application/core/MY_Loader.php */