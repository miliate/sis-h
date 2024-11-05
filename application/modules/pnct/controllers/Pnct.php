<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pnct extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_comorbidities');
        $this->load->model('m_characterization_tuberculosis');
    }

    public function add($PID, $ref_id=null)
    {
        $data['PID'] = $PID;
        $data['ref_id'] = $ref_id;
        $data['patologies'] = $this->db->get('patology')->result_array(); 
        $data['comorbidities'] = $this->m_comorbidities->get_comorbidities_with_patology($PID); 
        $this->render('form_comorbidities', $data);
    }

    public function create()
    {
        $pid = $this->input->post('PID');

        $data = array(
            'PID' => $pid,
            'patology_id' => $this->input->post('patology_id'),
            'date' => $this->input->post('date'),
            'treatment' => $this->input->post('treatment'),
            'Active' => 1 
        );

        $this->m_comorbidities->insert_comorbidity($data);

        redirect('pnct/add/' . $pid);
    }

    public function show_tb_characterization($PID, $ref_id=null)
    {
        $data['PID'] = $PID;
        $data['ref_id'] = $ref_id;
        $data['default_tb_location'] = $this->dropdown_locations();
        $data['default_bacteriological_confirmation'] = $this->dropdown_bacteriological_confirmations();
        $data['default_resistance_profile'] = $this->dropdown_resistance_profiles();
        $data['default_pretreatment_tb'] = $this->dropdown_prior_treatment();
        $data['default_local'] = '';
        $data['default_other_resistance_profile'] = '';
        $data['default_another_resistance_profile_text'] = '';
        $data['default_other_tb_pretreatment'] = '';
        $data['default_severity'] = '';
        $data['current_date'] = date('Y-m-d'); 
        $data['default_tests'] = $this->radio_tests();
        $data['default_selected_date'] = $this->input->post('default_selected_date');

        $data['tb_characterization'] = $this->m_characterization_tuberculosis->get_by_patient_id($PID);

        $this->render('form_tb_characterization', $data);
    }

    public function create_tb_characterization($PID)
    {
        $this->form_validation->set_rules('tb_location', lang('TB Location'), 'required|trim');
        $this->form_validation->set_rules('Bacteriological_Confirmation', lang('Bacteriological Confirmation'), 'required|trim');
        $this->form_validation->set_rules('default_selected_date', lang('Date'), 'required|trim');
        $this->form_validation->set_rules('Resistance_Profile', lang('Resistance Profile'), 'required|trim');
        $this->form_validation->set_rules('TB_prior_treatment', lang('TB Prior Treatment'), 'required|trim');

        if ($this->input->post('tb_location') == '1') {
            $this->form_validation->set_rules('location_description', 'Location Description', 'required|trim');
        }

        if ($this->input->post('Bacteriological_Confirmation') == '1') {
            $this->form_validation->set_rules('tests', 'Tests', 'required|trim');
        }

        if ($this->input->post('another_resistance_profile_checkbox') == '1') {
            $this->form_validation->set_rules('another_resistance_profile', 'Another Resistance Profile', 'required|trim');
        } else {
            $this->input->post('another_resistance_profile', '');
        }

        if ($this->input->post('default_other_tb_pretreatment_checkbox') == '1') {
            $this->form_validation->set_rules('default_other_tb_pretreatment', 'Other TB Pretreatment', 'required|trim');
        } else {
            $this->input->post('default_other_tb_pretreatment', '');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->show_tb_characterization($PID);
        } else {
            $data = array(
                'Patient_id' => $PID,
                'TbLocation' => $this->input->post('tb_location'),
                'Location' => $this->input->post('tb_location') == 'Pulmonar' ? $this->input->post('location_description') : $this->input->post('local'),
                'Bacteriological' => $this->input->post('Bacteriological_Confirmation'),
                'Tests' => $this->input->post('tests'),
                'TestDate' => $this->input->post('default_selected_date'),
                'Resistance' => $this->input->post('Resistance_Profile'),
                'AnotherResistance' => $this->input->post('another_resistance_profile'),
                'PriorTreatment' => $this->input->post('TB_prior_treatment'),
                'OtherPriorTreatment' => $this->input->post('default_other_tb_pretreatment'),
            );

            $this->m_characterization_tuberculosis->insert_record($data);

            $this->session->set_flashdata('msg', 'Created');
            redirect('/pnct/show_tb_characterization/' . $PID);
        }
    }

    public function update($CID)
    {
        $data = array(
            'patology_id' => $this->input->post('patology_id'),
            'date' => $this->input->post('date'),
            'treatment' => $this->input->post('treatment')
        );

        $this->m_comorbidities->update_comorbidity($CID, $data);
        redirect('pnct/add/' . $this->input->post('PID'));
    }

    public function invalidate($CID)
    {
        $this->m_comorbidities->invalidate_comorbidity($CID);
        $comorbidity = $this->m_comorbidities->get_comorbidity($CID);
        redirect('pnct/add/' . $comorbidity['PID']); 
    }

    public function tb_char_invalidate($id)
    {
        $this->m_characterization_tuberculosis->invalidate_tb_characterization($id);

        $characterization = $this->m_characterization_tuberculosis->get_characterization($id);

        redirect('pnct/show_tb_characterization/' . $characterization['Patient_id']);
    }


    public function radio_tests()
    {
        $tests = $this->db->get('tb_type_test')->result_array();
        $options = array();
        foreach ($tests as $test) {
            $options[$test['Test']] = $test['Test'];
        }
        return $options;
    }

    // public function radio_location_description()
    // {
    // $location_description = $this->input->post('location_description');
    // $this->db->where('id', $location_description);
    // $location = $this->db->get('location_description')->row_array();
    // echo $location['location_description'];
    // }

    // Para o dropdown de localização da TB
    public function dropdown_locations()
    {
        $tb_locations = $this->db->get('tb_location')->result_array();
        $options = array('' => '');
        foreach ($tb_locations as $location) {
            $options[$location['location_description']] = $location['location_description'];
        }
        return $options;
    }

    // Para o dropdown de confirmação bacteriológica
    public function dropdown_bacteriological_confirmations()
    {
        $bacteriological_confirmations = $this->db->get('tb_bacteriological_confirmation')->result_array();
        $options = array('' => '');
        foreach ($bacteriological_confirmations as $confirmation) {
            $options[$confirmation['confirmation_description']] = $confirmation['confirmation_description'];
        }
        return $options;
    }

    // Para o dropdown de perfil de resistência
    public function dropdown_resistance_profiles()
    {
        $resistance_profiles = $this->db->get('tb_resistance_profile')->result_array();
        $options = array('' => '');
        foreach ($resistance_profiles as $profile) {
            $options[$profile['profile_description']] = $profile['profile_description'];
        }
        return $options;
    }

    // Para o dropdown de tratamento anterior da TB
    public function dropdown_prior_treatment()
    {
        $prior_treatments = $this->db->get('tb_prior_treatment')->result_array();
        $options = array('' => '');
        foreach ($prior_treatments as $treatment) {
            $options[$treatment['treatment_description']] = $treatment['treatment_description'];
        }
        return $options;
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('treatment', lang('treatment'), 'trim|xss_clean|required');
    }
}
