<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Input extends CI_Input {

    /**
     * Clean Keys
     *
     * This is a helper function. To prevent malicious users
     * from trying to exploit keys we make sure that keys are
     * only named with alpha-numeric text and a few other items.
     *
     * @access	private
     * @param	string
     * @return	string
     */
    function _clean_input_keys($str)
    {
        $config = &get_config('config');
        if ( ! preg_match("/^[".$config['permitted_uri_chars']."]+$/i", rawurlencode($str)))   
        {
            exit('Disallowed Key Characters.');
        }
        // Clean UTF-8 if supported
        if (UTF8_ENABLED === TRUE)
        {
            $str = $this->uni->clean_string($str);
        }

        return $str;
    }

    public function ip_address()
    {
        if($this->ip_address !== FALSE) {
            return $this->ip_address;
        }
        $proxy_ips = config_item('proxy_ips');
        if(! empty($proxy_ips)) {
            $proxy_ips = explode(',', str_replace(' ', '', $proxy_ips));
            foreach(array(
                'HTTP_X_FORWARDED_FOR',
                'HTTP_CLIENT_IP',
                'HTTP_X_CLIENT_IP',
                'HTTP_X_CLUSTER_CLIENT_IP' 
            ) as $header) {
                if(($spoof = $this->server($header)) !== FALSE) {
                    if(strpos($spoof, ',') !== FALSE) {
                        $spoof = explode(',', $spoof, 2);
                        $spoof = $spoof[0];
                    }
                    if(! $this->valid_ip($spoof)) {
                        $spoof = FALSE;
                    } else {
                        break;
                    }
                }
            }
            ! isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] = '0.0.0.0';
            $this->ip_address = ($spoof !== FALSE && in_array($_SERVER['REMOTE_ADDR'], $proxy_ips, TRUE)) ? $spoof : $_SERVER['REMOTE_ADDR'];
        } else {
            $this->ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
        }
        if(! $this->valid_ip($this->ip_address)) {
            $this->ip_address = '0.0.0.0';
        }
        return $this->ip_address;
    }
}

/* End of file MY_Input.php */
/* Location: ./application/core/MY_Input.php */