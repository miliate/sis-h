<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Hiv_Screening extends FormController
{
    public function __construct() {
        parent::__construct();
        $this->load->model('m_hiv_screening');
    }

    public function add($PID,$ref_id=null)
    {
        $data['PID'] = $PID;
        $data['ref_id'] = $ref_id;
        $data['screenings'] = $this->m_hiv_screening->get_screenings_by_patient($PID);
        $this->render('hiv_screening_form', $data);
    }

    public function create() {
        $pid = $this->input->post('PID'); 
        
        $this->form_validation->set_rules('test_result', 'Test Result', 'required');
        $this->form_validation->set_rules('test_date', 'Test Date', 'required');

        if ($this->input->post('is_tarv_started') == '1') {
            $this->form_validation->set_rules('NIDTARV', 'NID TARV', 'trim|required');
            $this->form_validation->set_rules('tarv_start_date', 'TARV Start Date', 'required');
            $this->form_validation->set_rules('current_tarv', 'Current TARV', 'required');
            $this->form_validation->set_rules('tpc', 'TPC', 'required');
            
            if ($this->input->post('tpc') == 'Sim') {
                $this->form_validation->set_rules('type_tpc', 'Type of TPC', 'required', array(
                    'required' => 'O campo tratamento é obrigatório'
                ));
            }
        }
    
        if ($this->form_validation->run() == FALSE) {
            $this->add($pid);
        } else {
            
            $data = array(
                'patient_id' => $pid,
                'test_result' => $this->input->post('test_result'),
                'test_date' => $this->input->post('test_date'),
                'is_tarv_started' => $this->input->post('is_tarv_started') == '1' ? 1 : 0, 
                'Active' => 1,
            );
            
            if ($data['is_tarv_started']) {
                $data['NIDTARV'] = $this->input->post('NIDTARV');
                $data['tarv_start_date'] = $this->input->post('tarv_start_date');
                $data['current_tarv'] = $this->input->post('current_tarv');
                $data['tpc'] = $this->input->post('tpc');
                
                if ($data['tpc'] == 'Sim') {
                    $data['type_tpc'] = $this->input->post('type_tpc');
                } else {
                    $data['type_tpc'] = NULL; 
                }
            } else {
                $data['NIDTARV'] = NULL;
                $data['tarv_start_date'] = NULL;
                $data['current_tarv'] = NULL;
                $data['tpc'] = NULL;
                $data['type_tpc'] = NULL; 
            }
            
            $this->m_hiv_screening->insert_screening($data);
            
            $this->session->set_flashdata('success_message', 'HIV screening added successfully.');
            redirect('hiv_screening/add/' . $pid);
        }
    }
    

    public function invalidate($id) {
        $this->m_hiv_screening->invalidate_screening($id);
        $screening = $this->m_hiv_screening->get_screening($id);
        redirect('hiv_screening/add/' . $screening['patient_id']);
    }
}
