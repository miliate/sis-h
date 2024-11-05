<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 * Based on: http://code.tutsplus.com/tutorials/an-introduction-to-views-templating-in-CodeIgniter--net-25648

 */
class Template
{
    var $ci;
    protected $_module_name;

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        $this->ci =& get_instance();
    }

    /** Set path to load view for all modules  */
    function load($tpl_view, $body_view = null, $data = null)
    {
        if (!is_null($body_view)) {
            if (file_exists(APPPATH . 'views/' . $tpl_view . '/' . $body_view)) {
                $body_view_path = $tpl_view . '/' . $body_view;
            } else if (file_exists(APPPATH . 'views/' . $tpl_view . '/' . $body_view . '.php')) {
                $body_view_path = $tpl_view . '/' . $body_view . '.php';
            } else if (file_exists(APPPATH . 'views/' . $body_view)) {
                $body_view_path = $body_view;
            } else if (file_exists(APPPATH . 'views/' . $body_view . '.php')) {
                $body_view_path = $body_view . '.php';
            } else {
                show_error('Unable to load the requested file: ' . $tpl_view . '/' . $body_view . '.php');
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

}