<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tb_Treatment_History extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_tb_treatment_history');
    }

    public function add($PID,$ref_id=null)
    {
        $data['PID'] = $PID;
        $data['ref_id'] = $ref_id;
        $data['treatments'] = $this->m_tb_treatment_history->get_treatments_by_patient_id($PID);
        $this->render('form_tb_treatment_history', $data);
    }

    public function create()
    {
        $this->set_treatment_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->add($this->input->post('PID'));
        } else {
            $pid = $this->input->post('PID');

            $data = array(
                'patient_id' => $pid,
                'start_date' => $this->input->post('start_date'),
                'end_date' => $this->input->post('end_date'),
                'regimen' => $this->input->post('regimen'),
                'duration_months' => $this->input->post('duration_months'),
                'outcome' => $this->input->post('outcome'),
            );

            $this->m_tb_treatment_history->create_treatment($data);
            $this->session->set_flashdata('success_message', lang('Treatment history added successfully.'));

            redirect('tb_treatment_history/add/' . $pid);
        }
    }

    public function view($PID)
    {
        $data['treatments'] = $this->m_tb_treatment_history->get_treatments_by_patient($PID);
        $data['PID'] = $PID;
        $this->load->view('view_treatment_history', $data);
    }

    // Processa o formulário para atualizar um tratamento existente
    public function update($id)
    {
        $data = array(
            'start_date' => $this->input->post('start_date'),
            'end_date' => $this->input->post('end_date'),
            'regimen' => $this->input->post('regimen'),
            'duration_months' => $this->input->post('duration_months'),
            'outcome' => $this->input->post('outcome')
        );

        $this->m_tb_treatment_history->update_treatment($id, $data);
        redirect('tb_treatment_history/add/' . $this->input->post('PID'));
    }

    public function invalidate($id)
    {
        $this->m_tb_treatment_history->deactivate_treatment($id);
        $treatment = $this->m_tb_treatment_history->get_treatment_by_id($id);
        redirect('tb_treatment_history/add/' . $treatment['patient_id']);
    }

    private function set_treatment_validation()
    {
        $this->form_validation->set_rules('start_date', lang('Start Date'), 'trim|required');
        $this->form_validation->set_rules('end_date', lang('End Date'), 'trim|required|callback_end_date_check');
        $this->form_validation->set_rules('regimen', lang('Regimen'), 'trim|required|min_length[3]');
        $this->form_validation->set_rules('duration_months', lang('Duration (Months)'), 'trim|required|numeric');
        $this->form_validation->set_rules('outcome', lang('Outcome'), 'trim|required|in_list[Curado,Tratamento Completo,Perda de Seguimento,Falência ao Tratamento]');
    }

    public function end_date_check($end_date)
    {
        $start_date = $this->input->post('start_date');

        // Verifica se start_date é posterior a end_date
        if (strtotime($start_date) > strtotime($end_date)) {
            $this->form_validation->set_message('end_date_check', lang('The {field} field must be a date after {param}.'));
            return FALSE;
        }

        return TRUE;
    }
}
