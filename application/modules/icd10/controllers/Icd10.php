<?php

/**
 * Created by PhpStorm.
 * User: manhdx
 * Date: 11/20/15
 * Time: 10:29 AM
 */
class Icd10 extends FormController
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_icd10');
        $this->load_form_language();
    }


    public function create()
    {
        if (!Modules::run('permission/check_permission', 'emr_observe', 'create'))
            die('No permission');
        $data = array();
        $data['id'] = 0;
        $data['default_code'] = '';
        $data['default_name'] = '';
        $data['default_isnotify'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Code' => $this->input->post('Code'),
                'Name' => $this->input->post('Name'),
                'isNotify' => $this->input->post('isNotify'),
                'Remarks' => $this->input->post('Remarks'),
            );
            $id = $this->m_icd10->insert($data);
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('preference/load/icd10');
        }
    }


    public function edit($id)
    {
        $icd10 = $this->m_icd10->get($id);
        if (empty($icd10))
            die('Id not exist');
        $data['ICDID'] = $id;
        $data['default_code'] = $icd10->Code;
        $data['default_name'] = $icd10->Name;
        $data['default_isnotify'] = $icd10->isNotify;
        $data['default_active'] = $icd10->Active;
        $data['default_remarks'] = $icd10->Remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Code' => $this->input->post('Code'),
                'Name' => $this->input->post('Name'),
                'isNotify' => $this->input->post('isNotify'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('Remarks'),
            );
            $this->m_icd10->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('preference/load/icd10');
        }
    }


    private function set_common_validation()
    {
        $this->form_validation->set_rules('Code', lang('ICD10 Code'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('Name', lang('ICD10 Name'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('isNotify', 'ICD10 isNotify', 'trim|xss_clean');
        $this->form_validation->set_rules('Remarks', 'Remarks', 'trim|xss_clean');
    }
}
