<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_Allergy extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_allergy');
        $this->load_form_language();
    }

    public function add($pid, $ref_id)
    {
        $data = array();
        $data['id'] = '';
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        $data['default_name'] = '';
        $data['default_status'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';

        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'Name' => $this->input->post('name'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
                'PID' => $pid
            );
            $this->m_patient_allergy->insert($data);
            $this->session->set_flashdata(
                'msg', 'REC: Allergy created for ' . $pid
            );
            $this->redirect_if_no_continue('/patient/view/' . $pid);
        }
    }

    public function edit($allergy_id)
    {
        $allergy = $this->m_patient_allergy->get($allergy_id);
        if (empty($allergy)) {
            die('Id wrong');
        }
        $data = array();
        $data['id'] = $allergy_id;
        $data['pid'] = $allergy->PID;
        $data['default_name'] = $allergy->Name;
        $data['default_status'] = $allergy->Status;
        $data['default_active'] = $allergy->Active;
        $data['default_remarks'] = $allergy->Remarks;

        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $update_data = array(
                'Name' => $this->input->post('name'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'Remarks' => $this->input->post('remarks'),
            );
            $this->m_patient_allergy->update($allergy_id, $update_data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $allergy->PID);
        }
    }

    public function patient_view_allergy($allergy_id)
    {
        $examination = $this->m_patient_allergy->get($allergy_id);
    
        // Load the view with the examination details
        $this->load->view('patient_allergy/patient_view_allergy', array('examination' => $examination));
    }

    public function get_previous_allergy($pid, $continue, $mode = 'HTML')
    {
        $data = array();
        $data["patient_allergy_list"] = $this->m_patient_allergy->as_array()->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid, 'Active' => 1));
        $data["continue"] = $continue;
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_allergy');
        } else {
            return $data["patient_allergy_list"];
        }
    }
}