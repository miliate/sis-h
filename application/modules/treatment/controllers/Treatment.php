<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Treatment extends FormController
{
    var $FORM_NAME = 'form_treatment';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_treatment');
        $this->form_validation->set_error_delimiters('<span class="field_error">', '</span>');
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
        } else {
            $data = array(
                'Treatment' => $this->input->post('name'),
                'Type' => $this->input->post('type'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
            );
            $id = $this->m_treatment->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('preference/load/treatment');
        }
    }

    public function edit($id)
    {
        $treatment = $this->m_treatment->get($id);
        if (empty($treatment))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_name'] = $treatment->Treatment;
        $data['default_type'] = $treatment->Type;
        $data['default_active'] = $treatment->Active;
        $data['default_remarks'] = $treatment->Remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Treatment' => $this->input->post('name'),
                'Type' => $this->input->post('type'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_treatment->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/treatment');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('name', 'Treatment Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }
}