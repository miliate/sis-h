<?php

class Diagnostic extends FormController
{

    var $FORM_NAME = 'form_treatment';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_diagnostic');
        $this->form_validation->set_error_delimiters('<span class = field_error>', '</span>');
        $this->load_form_language();
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_name'] = '';
        $data['default_type'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        }

        if ($this->form_validation->run() == TRUE) {
            $data = array(
                'Diagnostic' => $this->input->post('name'),
                'Type' => $this->input->post('active'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks')
            );

            $id = $this->m_diagnostic->insert($data);
            $this->session->set_flashdata('msg', 'Created');
            $this->redirect_if_no_continue('preference/load/diagnostic');
        }
    }



    private function set_common_validation()
    {

        $this->form_validation->set_rules('name', 'Diagnostic Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('type', "Type", 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }
}
