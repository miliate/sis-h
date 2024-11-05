<?php

/**
 * Created by @jordao.cololo.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Complaints extends FormController
{
    var $FORM_NAME = 'form_complaint';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_complaints');
    }

    public function create()
    {

        $data = array();
        $data['id'] = 0;
        $data['default_ICPCCode'] = '';
        $data['default_Name'] = '';
        $data['default_ICDCode'] = '';
        $data['default_isNotify'] = '';
        $data['default_Remarks'] = '';
        $data['default_Active'] = '';

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'ICPCCode' => $this->input->post('ICPCCode'),
                'Name' => $this->input->post('Name'),
                'ICDCode' => $this->input->post('ICDCode'),
                'isNotify' => $this->input->post('isNotify'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_complaints->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/preference/load/complaints');
        }
    }

    public function edit($id)
    {
        $complaints = $this->m_complaints->get($id);
        if (empty($complaints))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_ICPCCode'] = $complaints->ICPCCode;
        $data['default_Name'] = $complaints->Name;
        $data['default_ICDCode'] = $complaints->ICDCode;
        $data['default_isNotify'] = $complaints->isNotify;
        $data['default_Remarks'] = $complaints->Remarks;
        $data['default_Active'] = $complaints->Active;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'ICPCCode' => $this->input->post('ICPCCode'),
                'Name' => $this->input->post('Name'),
                'ICDCode' => $this->input->post('ICDCode'),
                'isNotify' => $this->input->post('isNotify'),
                'Remarks' => $this->input->post('Remarks'),
                'Active' => $this->input->post('Active'),
            );
            $this->m_complaints->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/complaints');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('Name', 'Complaints Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('ICPCCode', 'Group', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }

    public function search($term)
    {
        $sql = 'SELECT * FROM icd10 WHERE Name LIKE "%' . $term . '%"';
        $query = $this->db->query($sql);
        $inc = 0;
        foreach ($query->result_array() as $row) {                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
            $result[$inc]['id'] = $row['ICDID'];
            $result[$inc]['name'] =  $row['Name'];
            $result[$inc]['value'] = $row['Name'];
            $inc++;
        }
      
        echo json_encode($result,JSON_PRETTY_PRINT);    
      //  echo json_encode($result);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
    }
}