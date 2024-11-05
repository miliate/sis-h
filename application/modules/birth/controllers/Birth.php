<?php

class Birth extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load_form_language();
    }
    public function create($pid)
    {
        $data = array();
        $data['id'] = 0;
        $data['pid'] = $pid;
        $data['default_entrance'] = '';
        $data['default_discharge'] = '';
        $data['default_transfer'] = '';
        $data['default_reason'] = '';
        $data['default_obstetric_history'] = '';
        $data['default_prev_difficult_births'] = array();
        $data['default_hiv_result'] = '';
        $data['default_hiv_prophylaxis'] = '';
        $data['default_hiv_tarv'] = '';
        $data['default_lmp'] = '';
        $data['default_conception_date'] = '';
        $data['default_edd'] = '';
        $data['default_pregnancy_duration_months'] = '';
        $data['default_pregnancy_duration_weeks'] = '';
        $data['default_height_below_1_5m'] = '';
        $data['default_weight'] = '';
        $data['default_blood_pressure_max'] = '';
        $data['default_blood_pressure_min'] = '';
        $data['default_proteinuria'] = '';
        $data['default_edema'] = '';
        $data['default_mucous_membranes'] = '';
        $data['default_temp'] = '';
        $data['default_pulse'] = '';
        $data['default_obstetric_examination'] = '';
        $data['default_palpation'] = '';
        $data['default_uterine_tone'] = '';
        $data['default_fetal_back'] = '';
        $data['default_engaged_in_pelvis'] = '';
        $data['default_fetal_heart_rate'] = '';
        $data['default_uterine_height'] = '';
        $data['default_cervix'] = '';
        $data['default_cervix_absent'] = '';
        $data['default_cervix_formed'] = '';
        $data['default_cervix_dilation'] = '';
        $data['default_amniotic_fluid'] = '';
        $data['default_gcs'] = '';
        $data['default_gcs_time'] = '';
        $data['default_pelivis'] = '';
        $data['default_presentation'] = '';
        $data['default_diagnosis'] = '';
        $data['default_prognosis'] = '';
        $data['default_foco_fetal'] = array();
        $data['default_liquido'] = array();
        $data['default_dilatacao'] = array();
        $data['default_start_hora'] = '00:00';
        $data['default_contractions'] = array();
        
        if($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            if($this->input->post('prev_difficult_births') == FALSE) {
                $prev_difficult_births = '';
            } else {
                $prev_difficult_births = implode(',', $this->input->post('prev_difficult_births'));
            }
            $data = array(
                'pid' => $pid,
                'entrance' => $this->input->post('entrance'),
                'discharge' => $this->input->post('discharge'),
                'transfer' => $this->input->post('transfer'),
                'reason' => $this->input->post('reason'),
                'obstetric_history' => $this->input->post('obstetric_history'),
                'prev_difficult_births' => $prev_difficult_births,
                'hiv_result' => $this->input->post('hiv_result'),
                'hiv_prophylaxis' => $this->input->post('hiv_prophylaxis'),
                'hiv_tarv' => $this->input->post('hiv_tarv'),
                'lmp' => $this->input->post('lmp'),
                'conception_date' => $this->input->post('conception_date'),
                'edd' => $this->input->post('edd'),
                'pregnancy_duration_months' => $this->input->post('pregnancy_duration_months'),
                'pregnancy_duration_weeks' => $this->input->post('pregnancy_duration_weeks'),
                'height_below_1_5m' => $this->input->post('height_below_1_5m'),
                'weight' => $this->input->post('weight'),
                'blood_pressure_max' => $this->input->post('blood_pressure_max'),
                'blood_pressure_min' => $this->input->post('blood_pressure_min'),
                'proteinuria' => $this->input->post('proteinuria'),
                'edema' => $this->input->post('edema'),
                'mucous_membranes' => $this->input->post('mucous_membranes'),
                'temp' => $this->input->post('temp'),
                'pulse' => $this->input->post('pulse'),
                'obstetric_examination' => $this->input->post('obstetric_examination'),
                'palpation' => $this->input->post('palpation'),
                'uterine_tone' => $this->input->post('uterine_tone'),
                'fetal_back' => $this->input->post('fetal_back'),
                'engaged_in_pelvis' => $this->input->post('engaged_in_pelvis'),
                'fetal_heart_rate' => $this->input->post('fetal_heart_rate'),
                'uterine_height' => $this->input->post('uterine_height'),
                'cervix' => $this->input->post('cervix'),
                'cervix_absent' => $this->input->post('cervix_absent'),
                'cervix_formed' => $this->input->post('cervix_formed'),
                'cervix_dilation' => $this->input->post('cervix_dilation'),
                'amniotic_fluid' => $this->input->post('amniotic_fluid'),
                'gcs' => $this->input->post('gcs'),
                'gcs_time' => $this->input->post('gcs_time'),
                'pelivis' => $this->input->post('pelivis'),
                'presentation' => $this->input->post('presentation'),
                'diagnosis' => $this->input->post('diagnosis'),
                'prognosis' => $this->input->post('prognosis'),
                'focofetal' => $this->input->post('focofetal'),
                'liquido' => $this->input->post('liquido'),
                'dilatacao' => $this->input->post('dilatacao'),
                'start_hora' => $this->input->post('start_hora'),
                'contractions' => $this->input->post('contractions'),
                'ocitocina' => $this->input->post('ocitocina'),
                'tensaoarterial' => $this->input->post('tensaoarterial'),
                'temperature' => $this->input->post('temperature'),
            );
        }
    }
}