<?php

/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */
class welcome extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('my_crud');
        $this->load->model('m_patient');
        $this->load->model('m_patient_allergy');
    }

    public function index()
    {
        var_dump('aaa');
    }

    public function edit()
    {
        var_dump($_POST);
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('security');
        $this->load->library('my_form');
        $this->load->library('form_validation');
//        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('user_groups[]', 'user_groups', 'xss_clean|required');
        if ($this->form_validation->run() == False) {
            $this->load->view('welcome');
            var_dump($this->input->post('user_groups'));
        } else {
            var_dump($this->input->post('user_groups'));
        }
    }

    public function edit1()
    {
        var_dump(get_instance()->form_validation);
        if (!Modules::run('permission/check_permission', 'patient', 'edit')) {
            die('You do not have permission');
        }
        var_dump(get_instance()->form_validation);
//        var_dump($this->form_validation);
//        var_dump($_POST);
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->library('my_form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_groups[]', 'user_groups', 'required');
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback_check_name[17]');
        if ($this->form_validation->run() == False) {
            $this->load->view('welcome');
            var_dump($this->input->post('name'));
        } else {
            var_dump($this->input->post('name'));
        }
    }

    public function check_name($abc, $id)
    {

        var_dump($id);
        var_dump($abc);
        if (strlen($abc) < 3) {
            $this->form_validation->set_message('check_name', 'BI ID is duplicated');
            return false;
        }
        return TRUE;
    }
}