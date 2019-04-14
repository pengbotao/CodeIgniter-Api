<?php

class err extends MY_Controller
{
    public function index()
    {
        $this->load->helper('api');
        if(HTTP_METHOD != 'POST') {
            http_404();
        }
        http_output(CODE_API_INVALID_PARAM, '404 Not Found.');
    }
}