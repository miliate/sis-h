<?php

class Drug_Route_Administration extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_drug_route_administration');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_Name'] = '';
        $data['default_Active'] = '';


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
               'Active' => $this->input->post('Active'),
            );
            $this->m_drug_route_administration->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/drug_route_administration');
        }   
    }

    public function edit($id)
    {
        $drug_route_administration = $this->m_drug_route_administration->get($id);
        if (empty($drug_route_administration))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_Name'] = $drug_route_administration->Name;
        $data['default_Active'] = $drug_route_administration->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('Name'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_drug_route_administration->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/drug_route_administration');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('Active', 'Active', 'trim|xss_clean');
    }

}