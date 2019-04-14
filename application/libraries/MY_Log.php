<?php if(! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Log extends CI_Log
{

    public $enable = true;

    public $filename = NULL;

    public function write($msg, $filename = NULL, $newline = true)
    {
        if(! $this->enable) {
            return false;
        }
        if(empty($filename)) {
            $filename = $this->filename;
        }
        $filename = trim($filename, "/");
        if(empty($filename)) {
            $filename = 'MY_log-' . date('Y-m-d') . '.php';
        } else {
            if(strpos($filename, '/') !== false) {
                $folder = $this->_log_path. substr($filename, 0, strrpos($filename, '/') + 1);
                if(! is_dir($folder)) {
                    mkdir($folder, 0777, true);
                }
            }
            if(strtolower(substr($filename, -4)) != '.php') {
                $filename .= '-' . date('Y-m-d') . '.php';
            }
        }

        $filepath = $this->_log_path . $filename;
        $message = '';

        if(! file_exists($filepath)) {
            $is_first_write = true;
            $message .= "<"."?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?".">\n\n";
        }

        if(! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
            return false;
        }
        $CI = & get_instance();
//      $message .= date($this->_date_fmt) . ' '.$CI->input->ip_address(). ($newline ? "\r\n" : " ") . $msg . "\n";
        $message .= ($newline ? "\r\n" : "") . $msg . "\n";
        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);

        if(isset($is_first_write) && $is_first_write === true) {
            @chmod($filepath, DIR_WRITE_MODE);
        }
        return true;
    }
    
    public function json($msg, $filename)
    {
        if(! $this->enable) {
            return false;
        }
        $filename = trim($filename, "/");
        if(strpos($filename, '/') !== false) {
            $folder = $this->_log_path. substr($filename, 0, strrpos($filename, '/') + 1);
            if(! is_dir($folder)) {
                @mkdir($folder, 0777, true);
            }
        }
        if(strtolower(substr($filename, -4)) != '.log') {
            $filename .= '-' . date('Y-m-d') . '.log';
        }

        $filepath = $this->_log_path . $filename;
        if(! $fp = @fopen($filepath, FOPEN_WRITE_CREATE)) {
            return false;
        }
        if(is_array($msg)) {
            $msg = json_encode($msg);
        }
        $CI = & get_instance();
        flock($fp, LOCK_EX);
        fwrite($fp, $msg . PHP_EOL);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
}
// END MY_Log Class

/* End of file MY_Log.php */
/* Location: ./application/libraries/MY_Log.php */