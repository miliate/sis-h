<?php

class Tb extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        // $this->load_form_language();
    }
    public function create($pid)
    {
        $data = array();
        $data['id'] = 0;
        $data['pid'] = $pid;
        if($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        }
    }
}