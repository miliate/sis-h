<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 30-Oct-15
 * Time: 4:12 PM
 */
class Drug extends FormController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_who_drug');
        $this->load->model('m_drug_dosage');
        $this->load->model('m_drug_frequency');
        $this->load->model('m_drug_pharmaceutical_form');
        $this->load->model('m_route_administration');
        $this->load->model('m_patient_discounts');
        $this->load_form_language();
    }

    public function view_patient_type($id = 'patient_type_select')
    {
        $all_drug = $this->m_patient_discounts ->order_by('name', 'asc')->get_all();
        $drug_option[0] = '';
        foreach ($all_drug as $drug) {
            $drug_info =  $drug->name;
            $drug_option[$drug->name] = $drug->name;;
        }
        $extra = 'class="form-control" id="' . $id . '" size="10"';
        echo form_dropdown('patient_type_select', $drug_option, array(), $extra);
    }

    public function view_select_drug($id = 'drug_select', $selected_drug = '')
    {
        // Fetch all drugs ordered by fnm (assuming fnm is the field for medication name)
        $all_drug = $this->m_who_drug->order_by('fnm', 'asc')->get_all();
        
        // Initialize an array for dropdown options
        $drug_option[0] = ''; // Default empty option
        
        // Loop through each drug and create dropdown option text
        foreach ($all_drug as $drug) {
            $drug_info = $drug->fnm . ' ' . $drug->name . ' ' . $drug->pharmaceutical_form . ' ' . $drug->dosage . ' ' . $drug->presentation . ' (Estoque: ' . $drug->count . ')';
            $drug_option[$drug->wd_id] = $drug_info;
        }
        
        // Additional attributes for the dropdown element
        $extra = 'class="form-control" id="' . $id . '" size="10"';
        
        // Generate the dropdown list using CodeIgniter's form_dropdown function
        echo form_dropdown('drug_select', $drug_option, $selected_drug, $extra);
    }
    
    

    // public function view_pharmaceutical_form($id = 'pharmaceutical_form_select')
    // {
    //     $all_pharmaceutical_form = $this->m_drug_pharmaceutical_form->get_all();
    //     $pharmaceutical_form_option[0] = '';
    //     foreach ($all_pharmaceutical_form as $pharmaceutical_form) {
    //         $pharmaceutical_form_info = $pharmaceutical_form->Name;
    //         $pharmaceutical_form_option[$pharmaceutical_form->PFID] = $pharmaceutical_form_info;
    //     }
    //     $extra = 'class="form-control" id="'. $id .'" size="10"';
    //     echo form_dropdown('pharmaceutical_form_select', $pharmaceutical_form_option, array(), $extra);
    // }

    public function view_route_administration($id = 'route_administration_select')
    {
        $all_route_administration = $this->m_route_administration->order_by('Name', 'asc')->get_all();
        $route_administration_option[0] = '';
        foreach ($all_route_administration as $route_administration) {
            $route_administration_info = $route_administration->Name;
            $route_administration_option[$route_administration->Name] = $route_administration_info;
        }
        $extra = 'class="form-control" id="'. $id .'" size="10"';
        echo form_dropdown('route_administration_select', $route_administration_option, array(), $extra);
    }

    public function view_select_dose($id = 'dose_select')
    {
        $all_dosage = $this->m_drug_dosage->order_by('Factor', 'asc')->get_all();
        $dosage_option[0] = '';
        foreach ($all_dosage as $dosage) {
            $drug_info = $dosage->Dosage;
            $dosage_option[$dosage->DDSGID] = $drug_info;
        }
        $extra = 'class="form-control" id="' . $id . '" size="10"';
        echo form_dropdown('dose_select', $dosage_option, array(), $extra);
    }

    public function view_select_frequency($id = 'frequency_select')
    {
        $all_frequency = $this->m_drug_frequency->get_all();
        $option[0] = "";
        foreach ($all_frequency as $frequency) {
            $drug_info = $frequency->Frequency;
            $option[$frequency->DFQYID] = $drug_info;
        }
        $extra = 'class="form-control" id="' . $id . '" size="10"';
        echo form_dropdown('frequency_select', $option, array(), $extra);
    }

    public function view_select_period($id = 'period_select')
    {
        $option[''] = '';
        $option['Por 1 dia'] = 'Por 1 dia';
        $option['Por 2 dias'] = 'Por 2 dias';
        $option['Por 3 dias'] = 'Por 3 dias';
        $option['Por 4 dias'] = 'Por 4 dias';
        $option['Por 5 dias'] = 'Por 5 dias';
        $option['Por 1 semanas'] = 'Por 1 semana';
        $option['Por 2 semanas'] = 'Por 2 semanas';
        $option['Por 3 semanas'] = 'Por 3 semanas';
        $option['Por 1 meses'] = 'Por 1 mes';
        $option['Por 2 meses'] = 'Por 2 meses';
        $option['Por 3 meses'] = 'Por 3 meses';
        $extra = 'class="form-control" id="' . $id . '" size="10"';
        echo form_dropdown('period_select', $option, array(), $extra);
    }

    /*public function check_dob()
    {
        if ($this->input->post('expiration_date') == '') {
            if ($this->input->post('age_year') == '' and $this->input->post('age_month') == '' and $this->input->post('age_day') == '') {
                $this->form_validation->set_message('check_dob', lang('Patient age or date of birth is missing'));
                return false;
            }
        }
        return TRUE;
    }*/

    public function create()
    {

        if (!has_permission('drug_management', 'view')) {
            $this->show_no_permission();
            return;
        }
        else{

            $data = array();
            $data['id'] = 0;
            $data['default_national_code'] = '';
            $data['default_group'] = '';
            $data['default_sub_group'] = '';
            $data['default_name'] = '';
            $data['default_formulation'] = '';
            $data['default_dose'] = '';
            $data['default_default_num'] = '';
            $data['default_default_timing'] = '';
            $data['default_remarks'] = '';
            $data['default_count'] = '';
            $data['default_active'] = '';
            $data['default_pharmaceutical_form'] = $this->get_dropdown_pharmaceutical();
            $data['default_dosage'] = '';
            $data['default_presentation'] = '';
            $data['default_lot_number'] = '';
            $data['default_expiration_date'] = '';

            $this->set_common_validation();
        }
        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'group' => $this->input->post('group'),
                'sub_group' => $this->input->post('sub_group'),
                'name' => $this->input->post('name'),
                'formulation' => $this->input->post('formulation'),
                'dose' => $this->input->post('dose'),
                'default_num' => $this->input->post('default_num'),
                'default_timing' => $this->input->post('default_timing'),
                'remarks' => $this->input->post('remarks'),
                'count' => $this->input->post('count'),
                'active' => $this->input->post('active'),
                'dosage' => $this->input->post('dosage'),
                'pharmaceutical_form' => $this->input->post('pharmaceutical_form'),
                'presentation' => $this->input->post('presentation'),
                'lot_number' => $this->input->post('lot_number'),
                'expiration_date' => $this->input->post('entry_time'),
                'fnm' => $this->input->post('national_code')

            );
            $this->m_who_drug->insert($data);
            $this->session->set_flashdata(
                'msg',
                'Created'
            );
            $this->redirect_if_no_continue('/preference/load/who_drug');
        }
    }

    public function edit($id)
    {
        $drug = $this->m_who_drug->get($id);
        if (empty($drug))
            die('Id not exist');
        $data['id'] = $id;
        $data['default_group'] = $drug->group;
        $data['default_national_code'] = $drug->fnm;
        $data['default_sub_group'] = $drug->sub_group;
        $data['default_name'] = $drug->name;
        $data['default_formulation'] = $drug->formulation;
        $data['default_dose'] = $drug->dose;
        $data['default_default_num'] = $drug->default_num;
        $data['default_default_timing'] = $drug->default_timing;
        $data['default_remarks'] = $drug->remarks;
        $data['default_count'] = $drug->count;
        $data['default_active'] = $drug->active;
        $data['default_dosage'] = $drug->dosage;
        $data['default_pharmaceutical_form'] = $this->get_dropdown_pharmaceutical("result");
        $data['default_presentation'] = $drug->presentation;
        $data['default_lot_number'] = $drug->lot_number;
        //        s$data['default_expiration_date'] = $drug->expiration_date;

        //        $data['dropdown_dosage'] = $this->get_dropdown_dosage();

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data = array(
                'group' => $this->input->post('group'),
                'sub_group' => $this->input->post('sub_group'),
                'name' => $this->input->post('name'),
                'formulation' => $this->input->post('formulation'),
                'dose' => $this->input->post('dose'),
                'default_num' => $this->input->post('default_num'),
                'default_timing' => $this->input->post('default_timing'),
                'remarks' => $this->input->post('remarks'),
                'count' => $this->input->post('count'),
                'active' => $this->input->post('active'),
                'dosage' => $this->input->post('dosage'),
                'pharmaceutical_form' => $this->input->post('pharmaceutical_form'),
                'presentation' => $this->input->post('presentation'),
                'lot_number' => $this->input->post('lot_number'),
                'expiration_date' => $this->input->post('entry_time'),
                'fnm' => $this->input->post('national_code')

            );
            $this->m_who_drug->update($id, $data);
            $this->session->set_flashdata(
                'msg',
                'Updated'
            );
            $this->redirect_if_no_continue('/preference/load/who_drug');
        }
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('name', 'Treatment Name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('group', 'Group', 'trim|xss_clean');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean');
    }




    public function get_dropdown_pharmaceutical($type = 'json')
    {
        $this->load->model('m_pharmaceutical_form');
        $result = $this->m_pharmaceutical_form->order_by('name')->dropdown('PFID', 'name');

        return $result;
    }
}
