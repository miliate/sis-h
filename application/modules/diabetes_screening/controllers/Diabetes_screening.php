<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Diabetes_Screening extends FormController
{
    public function __construct() {
        parent::__construct();
        $this->load->model('m_diabetes_screening');
    }

    public function add($PID,$ref_id=null)
    {
        $data['PID'] = $PID;
        $data['ref_id'] = $ref_id;
        $data['screenings'] = $this->m_diabetes_screening->get_screenings_by_patient($PID);
        $this->render('form_diabetes_screening', $data);
    }

    public function create() {
        $pid = $this->input->post('PID');
        $this->set_validation_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->add($this->input->post('PID'));
        } else {

        $data = array(
            'patient_id' => $pid,
            'date' => $this->input->post('screening_date'),
            'fasting_glucose' => $this->input->post('fasting_glucose'),
            'diagnosis' => $this->input->post('diagnosis'),
        );

        $this->m_diabetes_screening->insert_screening($data);
        redirect('diabetes_screening/add/' . $pid);
        }
    }

    public function invalidate($id) {
        $this->m_diabetes_screening->invalidate_screening($id);
        $screening = $this->m_diabetes_screening->get_screening($id);
        redirect('diabetes_screening/add/' . $screening['patient_id']);
    }
    private function set_validation_rules() {
        $this->form_validation->set_rules('screening_date', 'Screening Date', 'required|xss_clean');
        $this->form_validation->set_rules('fasting_glucose', 'Fasting Glucose', 'required|numeric|regex_match[/^\d+(\.\d{1,2})?$/]|xss_clean');
        $this->form_validation->set_rules('diagnosis', 'Diagnosis', 'required|xss_clean');
    }    
}
