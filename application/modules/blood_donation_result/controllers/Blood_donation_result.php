<?php

/**
 * Created by COLOLO.
 * User: qch
 * Date: 11/21/15
 * Time: 6:40 AM
 */
class Blood_donation_result extends FormController
{
    var $FORM_NAME = 'form_blood_donation_result';

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_blood_donation');
        $this->load->model('m_patient_blood_donation_result');
        $this->load->model('m_user');
        $this->load->model('m_patient');
        $this->load_form_language();
    }

    public function add($donation_id)
    {
        $blood_donation = $this->m_patient_blood_donation->get($donation_id);
        $data = array();
        $data['pid'] = $blood_donation->pid;
        $data['donation_id'] = $donation_id;
        $data['donation_number'] = $blood_donation->donation_number;
        $data['default_sample_id'] = '';
        $data['default_donation_date'] = date("Y-m-d H:i:s");
        $data['default_hgb'] = '';
        $data['default_peso'] = '';
        $data['default_ta'] = '';
        $data['default_hiv'] = '';
        $data['default_hbv'] = '';
        $data['default_hcv'] = '';
        $data['default_rpr'] = '';
        $data['default_next_donation_date'] = '';
        $data['default_active'] = '';
        $data['default_remarks'] = '';
        

        


        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'donation_id' => $donation_id,
                'sample_id' => $this->input->post('sample_id'),
                'donation_date' => $this->input->post('donation_date'),
                'hgb' => $this->input->post('hgb'),
                'peso'=>$this->input->post('peso'),
                'ta' => $this->input->post('ta'),
                'hiv' => $this->input->post('hiv'),
                'hbv' => $this->input->post('hbv'),
                'hcv' => $this->input->post('hcv'),
                'rpr' => $this->input->post('rpr'),
                'next_donation_date' => $this->input->post('next_donation_date'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
            $this->m_patient_blood_donation_result->insert($data);
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            $this->redirect_if_no_continue('/patient/view/' . $blood_donation->pid);
//            $this->redirect_if_no_continue('/blood_donation_result/add/'.$donation_number);
        }
    }

    public function edit($id)
    {
        $patient_blood_donation_result = $this->m_patient_blood_donation_result->get($id);
        $blood_donation = $this->m_patient_blood_donation->get($patient_blood_donation_result->donation_id);
        if (empty($patient_blood_donation_result))
            die('Id not exist');
        $data = array();
        $data['pid'] = $blood_donation->pid;
        $data['donation_id'] = $patient_blood_donation_result->donation_id;
        $data['donation_number'] = $blood_donation->donation_number;
        $data['default_sample_id'] = $patient_blood_donation_result->sample_id;
        $data['default_donation_date'] = $patient_blood_donation_result->donation_date;
        $data['default_hgb'] = $patient_blood_donation_result->hgb;
        $data['default_peso'] = $patient_blood_donation_result->peso;
        $data['default_ta'] = $patient_blood_donation_result->ta;
        $data['default_hiv'] = $patient_blood_donation_result->hiv;
        $data['default_hbv'] = $patient_blood_donation_result->hbv;
        $data['default_hcv'] = $patient_blood_donation_result->hcv;
        $data['default_rpr'] = $patient_blood_donation_result->rpr;
        $data['default_next_donation_date'] = $patient_blood_donation_result->next_donation_date;
        $data['default_active'] = $patient_blood_donation_result->active;
        $data['default_remarks'] = $patient_blood_donation_result->remarks;

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'sample_id' => $this->input->post('sample_id'),
                'donation_date' => $this->input->post('donation_date'),
                'hgb' => $this->input->post('hgb'),
                'peso'=>$this->input->post('peso'),
                'ta' => $this->input->post('ta'),
                'hiv' => $this->input->post('hiv'),
                'hbv' => $this->input->post('hbv'),
                'hcv' => $this->input->post('hcv'),
                'rpr' => $this->input->post('rpr'),
                'next_donation_date' => $this->input->post('next_donation_date'),
                'remarks' => $this->input->post('remarks'),
                'active' => $this->input->post('active'),
            );
            $this->m_patient_blood_donation_result->update($id, $data);
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('/patient/view/' . $blood_donation->pid);
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('donation_number', 'Numero do Dador', 'trim|xss_clean|required');
        $this->form_validation->set_rules('sample_id', 'Numero do Frasco', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }
}

