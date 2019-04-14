<?php
class Script_Caller_Service extends MY_Service
{
    public function init()
    {
        if(! $this->input->is_cli_request()) {
            http_404();
        }
        set_time_limit(0);
    }

    public function get()
    {

    }

    public function set()
    {
        
    }

    public function output()
    {
        
    }
}