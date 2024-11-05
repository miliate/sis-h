<?php

/**
 * Created by Jordão Cololo.
 */
class Patient_pnct extends FormController
{
    var $_department;

    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_doctor');
        $this->load->model('m_patient_costs');
        $this->load->model('m_department');
        $this->load->model('m_patient_active_nopay');
        $this->load->model('m_admission_type');
        $this->load->model('m_pa_visit');
        $this->load->model('m_operation_order'); // New from Dec 2022 by Jcololo
        $this->load->model('m_tb_treatment_history');
        $this->load->model('m_comorbidities');
        $this->load->model('m_diabetes_screening');
        $this->load->model('m_hiv_screening');
        $this->load->model('m_characterization_tuberculosis');

        $this->_department = $this->session->userdata('department');
        $this->load_form_language();
    }

    public function index($PID = null, $ref_id = null)
    {
        if ($PID === null) {
            $PID = $this->uri->segment(3); 
        }

        $data['PID'] = $PID;

        $data['ref_id'] = $ref_id;

        $data['tb_treatment_history'] = $this->m_tb_treatment_history->get_treatments_by_patient_id($PID);

        $data['comorbidities'] = $this->m_comorbidities->get_comorbidities_with_patology($PID);

        $data['diabetes_screening'] = $this->m_diabetes_screening->get_screenings_by_patient($PID);

        $data['screenings'] = $this->m_hiv_screening->get_screenings_by_patient($PID);

        $data['tb_characterization'] = $this->m_characterization_tuberculosis->get_characterization_tuberculosis($PID);
        
        // echo '<pre>';
        // print_r($data['tb_characterization']);
        // echo '</pre>';
        
        $this->render('search', $data);
    }
}
