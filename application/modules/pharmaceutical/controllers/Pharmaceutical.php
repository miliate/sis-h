<?php

class Pharmaceutical extends FormController
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('m_pharmaceutical');
        $this->load_form_language();
    }

    public function create()
    {

        $data = array();
        $data['default_name'] = "";
        $data['default_active'] = '';
        $data['default_remarks'] = "";

        $this->set_common_validation();

        if (!$this->form_validation->run()) {
            $this->load_form($data);
        }

        if ($this->form_validation->run()) {
            $data = array(
                'Name' => $this->input->post('name'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks')

            );

            $id = $this->m_pharmaceutical->insert($data);
            $this->session->set_flashdata('msg', "Created");
            $this->redirect_if_no_continue('preference/load/pharmaceutical');
        }
    }





    private function set_common_validation()
    {

        $this->form_validation->set_rules('name', lang('Name'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim|xss_clean');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|xss_clean');
    }
}
