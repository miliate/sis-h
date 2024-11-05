<?php

/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 * Modified to load view from module
 */
class Qch_Template extends Template
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        $this->ci =& get_instance();
        $this->_module_name = CI::$APP->router->fetch_module();
    }

    /**
     * Load template
     * @param $tpl_view     (string)        Load $tpl_view template in 'application/templates/$tpl_view'
     * @param $body_view    (string)        Load template in views folder of module
     * @param $data         (array)         data are sent to template
     */
    function load($tpl_view, $body_view = null, $data = null)
    {
        if (!is_null($body_view)) {
            if (file_exists(APPPATH . 'modules/' . $this->_module_name . '/views/' . $body_view . '.php')) {
                $body_view_path = $this->_module_name . '/' . $body_view;
            } else {
                show_error('Unable to load the requested file: ' . APPPATH . 'modules/' . $this->_module_name . '/views/' . $body_view . '.php');
            }
            $body = $this->ci->load->view($body_view_path, $data, TRUE);

            if (is_null($data)) {
                $data = array('body' => $body);
            } else if (is_array($data)) {
                $data['body'] = $body;
            } else if (is_object($data)) {
                $data->body = $body;
            }
        }
        $this->ci->load->view('templates/' . $tpl_view, $data);
    }

    function load_form_layout($view, $data = null)
    {
        $this->load('qch/header', null, $data);
        $this->load('qch/default_form_layout', $view, $data);
    }

    function load_table_layout($data = null)
    {
        $this->load('qch/header', null, $data);
        $this->load('qch/default_table_layout', null, $data);
    }
}