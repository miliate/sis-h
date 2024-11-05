<?php

/**
 * Created by Trunghoang.
 */
class Pathological_anatomy_order extends FormController
{

    public function __construct()
    {
        parent::__construct();
        $this->load_form_language();
        $this->load->model('m_user');
        $this->load->model('m_doctor');
        $this->load->model('m_pathological_anatomy_order');
        $this->load->model('m_pathological_anatomy_tests');
        $this->load->model('m_pa_biopsy_order');
        $this->load->model('m_pa_cytology_order');
        $this->load->model('m_pa_cv_cytology_order');
    }

    public function create($pid, $pa_id)
    {
        $data['sample_type'] = [];
        foreach ($this->m_pathological_anatomy_tests->get_all() as $sample_type) {
            $data['sample_type'][$sample_type->PA_test_ID] = $sample_type->Name;
        }
        $data['pa_id'] = $pa_id;
        $data['pid'] = $pid;

        $data['default_doctor'] = '';
        $data['default_sample_type'] = '';
        $data['default_remarks'] = '';
        $data['default_active'] = '';

        $data['dropdown_doctor'] = $this->get_dropdown_doctor();
        $data['dropdown_sample_type'] = $this->get_dropdown_sample_type();

        $this->form_validation->set_rules('priority', 'Priority', 'trim|required');
        $this->form_validation->set_rules('doctor_in_charge', 'Doctor in Charge', 'trim|required');
        $this->form_validation->set_rules('sample_type', 'Sample Type', 'trim|required');
        $this->form_validation->set_rules('doctor_who_requested', 'Doctor who Requested', 'trim|required');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $pa_test_id = $this->input->post('sample_type');

            $pa_order_data = array(
                'PA_ID' => $pa_id,
                'PID' => $pid,
                'PA_test_ID' => $this->input->post('sample_type'),
                'Priority' => $this->input->post('priority'),
                'SampleRequestBy' => $this->get_session('uid'),
                'SampleRequestDate' => Date('Y-m-d@H:m:s'),
                'Doctor_in_Charge' => $this->input->post('doctor_in_charge'),
                'Doctor_who_Requested' => $this->input->post('doctor_who_requested'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
                'Result_Status' => 'Pending'
            );
            if ($pa_order_id = $this->m_pathological_anatomy_order->insert($pa_order_data)) {

                $pa_type_data = array(
                    'PA_order_ID' => $pa_order_id,
                    'Priority' => $this->input->post('priority'),
                    'Result_Status' => 'Pending',
                    'Active' => $this->input->post('active'),
                );

                if ($pa_test_id == 1) {
                    $biopsy_cytology_id = $this->m_pa_biopsy_order->insert($pa_type_data);
                    $update_pa = array('Biopsy_Cytology_ID' => $biopsy_cytology_id);
                    $this->m_pathological_anatomy_order->update($pa_order_id, $update_pa);
                } elseif ($pa_test_id == 3) {
                    $biopsy_cytology_id = $this->m_pa_cytology_order->insert($pa_type_data);
                    $update_pa = array('Biopsy_Cytology_ID' => $biopsy_cytology_id);
                    $this->m_pathological_anatomy_order->update($pa_order_id, $update_pa);
                } elseif ($pa_test_id == 5) {
                    $biopsy_cytology_id = $this->m_pa_cv_cytology_order->insert($pa_type_data);
                    $update_pa = array('Biopsy_Cytology_ID' => $biopsy_cytology_id);
                    $this->m_pathological_anatomy_order->update($pa_order_id, $update_pa);
                }

                $this->session->set_flashdata(
                    'msg', 'Created'
                );
            }
            $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $pa_id);
        }
    }

    public function search()
    {
        $this->set_top_selected_menu('patient_lab_order/search');
        $qry = "SELECT 
                pathological_anatomy_order.Biopsy_Cytology_ID,
                pathological_anatomy_order.SampleRequestDate,
                pathological_anatomy_order.PA_order_ID,
                pathological_anatomy_tests.Name as Sample_Type,
                patient.PID,
                CONCAT(patient.Name,' ',patient.OtherName) AS Patient,
                CONCAT(user.Title, ' ', user.Name,' ',user.OtherName) AS Doctor_Requested,
                pathological_anatomy_order.Priority,
                pathological_anatomy_order.Result_Status
                
                FROM pathological_anatomy_order
                LEFT JOIN pathological_anatomy_tests ON pathological_anatomy_order.PA_test_ID = pathological_anatomy_tests.PA_test_ID
                LEFT JOIN patient ON patient.PID = pathological_anatomy_order.PID
                LEFT JOIN user ON user.UID = pathological_anatomy_order.SampleRequestBy
                
                WHERE pathological_anatomy_order.Active = 1
                ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('PA_order_ID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("Biopsy_Cytology_ID", lang("Requested Time"), lang("Order ID"), lang("Sample Type"), lang("Patient ID"),
            lang("Patient Name"), lang("Requested Doctor"), lang("Priority"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("Biopsy_Cytology_ID", array("hidden" => true));
        $page->setColOption("SampleRequestDate", $page->getDateSelector(date('Y-m-d')));
        $page->setColOption("Patient", array("search" => false, "hidden" => false));
        $page->setColOption("PID", array('width' => '100', 'width' => '60'));
        $page->setColOption("PA_order_ID", array('width' => '100', 'width' => '50'));
        $page->setColOption('Sample_Type', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;'.lang('Biopsy').':'.lang('Biopsy').';'.lang('Cytology').':'.lang('Cytology').';'.lang('Cervico-Vaginal Cytology').':'.lang('Cervico-Vaginal Cytology')
            ), 'width' => '120'));
        $page->setColOption('Result_Status', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;'.lang('Pending').':'.lang('Pending').';'.lang('Done').':'.lang('Done')
            ), 'width' => '50'));
        $page->setColOption('Priority', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':All;'.lang('Urgent').':'.lang('Urgent').';'.lang('Normal').':'.lang('Normal')
            ), 'width' => '50'));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/) || alertText.match(/^.*Pendente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Done/) || alertText.match(/^.*Concluido/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
       }');
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                var status = $(this).find('td:nth-child(9)').text();
                var sample_type = $(this).find('td:nth-child(4)').text();
                var biopsy_cytology_ID = $(this).find('td:nth-child(1)').text();
                if (status == 'Pending' || status == 'Pendente') {
                    if (sample_type == 'Biopsy') {
                        window.location='" . site_url("pathological_anatomy_order/update_biopsy") . "/'+biopsy_cytology_ID+'';
                    } else if (sample_type == 'Cytology' || sample_type == 'Citologia') {
                        window.location='" . site_url("pathological_anatomy_order/update_cytology") . "/'+biopsy_cytology_ID+'';
                    } else if (sample_type == 'Cervico-Vaginal Cytology' || sample_type == 'Citologia Cérvico-Vaginal') {
                        window.location='" . site_url("pathological_anatomy_order/update_cv_cytology") . "/'+biopsy_cytology_ID+'';
                    }                    
                } else {                    
                    if (sample_type == 'Biopsy') {
                        window.location='" . site_url("pathological_anatomy_order/update_biopsy") . "/'+biopsy_cytology_ID+'';
                    } else if (sample_type == 'Cytology' || sample_type == 'Citologia') {
                        window.location='" . site_url("pathological_anatomy_order/update_cytology") . "/'+biopsy_cytology_ID+'';
                    } else if (sample_type == 'Cervico-Vaginal Cytology' || sample_type == 'Citologia Cérvico-Vaginal') {
                        window.location='" . site_url("pathological_anatomy_order/update_cv_cytology") . "/'+biopsy_cytology_ID+'';
                    } 
                }
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
//        $this->qch_template->load_form_layout('search', $data);
        $this->render_search($data);
    }

    public function get_dropdown_doctor()
    {
        $res = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $res[''] = '';
        return $res;
    }

    public function get_dropdown_sample_type()
    {
        $res = $this->m_pathological_anatomy_tests->order_by('Name', 'asc')->dropdown('PA_test_ID', 'Name');
        $res[''] = '';
        return $res;
    }

    public function update_biopsy($biopsy_cytology_ID)
    {
        $biopsy_order = $this->m_pa_biopsy_order->get($biopsy_cytology_ID);
        $PA_order = $this->m_pathological_anatomy_order->get($biopsy_order->PA_order_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['default_status'] = $biopsy_order->Result_Status;
        $data['default_request_date'] = $PA_order->SampleRequestDate;
        $data['default_priority'] = $biopsy_order->Priority;
        $data['default_kind_of_product'] = $biopsy_order->Kind_of_Product;
        $data['default_collection_method'] = $biopsy_order->CollectionMethod;
        $data['default_fixed_on'] = $biopsy_order->Fixed_On;
        $data['default_wound_centre'] = $biopsy_order->Wound_Centre;
        $data['default_extracted'] = $biopsy_order->Extracted;
        $data['default_previous_pa'] = $biopsy_order->Previous_PA;
        $data['default_old_result'] = $biopsy_order->Old_Result;
        $data['default_macroscopic'] = $biopsy_order->Macroscopic;
        $data['default_microscopic'] = $biopsy_order->Microscopic;
        $data['default_pa_diagnosis'] = $biopsy_order->PA_Diagnosis;
        $data['default_topography'] = $biopsy_order->Topography;
        $data['default_morphology'] = $biopsy_order->Morphology;
        $data['default_remarks'] = $biopsy_order->Remarks;
        $data['default_active'] = $biopsy_order->Active;


        $this->form_validation->set_rules('kind_of_product', lang('Kind of Product to Analyze'), 'trim');
        $this->form_validation->set_rules('collection_method', lang('Collection Method'), 'trim');
        $this->form_validation->set_rules('fixed_on', lang('Fixed On'), 'trim');
        $this->form_validation->set_rules('wound_centre', lang('Wound Centre'), 'trim');
        $this->form_validation->set_rules('extracted', lang('Exact place on where the fragment was removed'), 'trim');
        $this->form_validation->set_rules('previous_pa', lang('Do you have previous PA test?'), 'trim|required');
        $this->form_validation->set_rules('old_result', lang('If the answer is YES, indicate its previous Sample ID and the Result of Exam'), 'trim');
        $this->form_validation->set_rules('macroscopic', lang('Result for Macroscopic Exam'), 'trim');
        $this->form_validation->set_rules('microscopic', lang('Result for Microscopic Exam'), 'trim');
        $this->form_validation->set_rules('pa_diagnosis', lang('Result for Pathological Anatomy Diagnosis'), 'trim');
        $this->form_validation->set_rules('topography', lang('Topography'), 'trim');
        $this->form_validation->set_rules('morphology', lang('Morphology'), 'trim');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');


        if ($this->form_validation->run($this) == FALSE) {
            $this->render('update_biopsy', $data);
        } else {
            $update_PA_order = array(
                'Result_Status' => 'Done',
                'SampleCollectedBy' => $this->get_session('uid'),
                'CollectionDateTime' => Date('Y-m-d@H:m:s')
            );
            $this->m_pathological_anatomy_order->update($biopsy_order->PA_order_ID, $update_PA_order);

            $update_biopsy_order = array(
                'Result_Status' => 'Done',
                'Kind_of_Product' => $this->input->post('kind_of_product'),
                'CollectionMethod' => $this->input->post('collection_method'),
                'Fixed_On' => $this->input->post('fixed_on'),
                'Wound_Centre' => $this->input->post('wound_centre'),
                'Extracted' => $this->input->post('extracted'),
                'Previous_PA' => $this->input->post('previous_pa'),
                'Old_Result' => $this->input->post('old_result'),
                'Macroscopic' => $this->input->post('macroscopic'),
                'Microscopic' => $this->input->post('microscopic'),
                'PA_Diagnosis' => $this->input->post('pa_diagnosis'),
                'Topography' => $this->input->post('topography'),
                'Morphology' => $this->input->post('morphology'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
            );
            $this->m_pa_biopsy_order->update($biopsy_cytology_ID, $update_biopsy_order);

            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('pathological_anatomy_order/search');
        }
    }

    public function update_cytology($biopsy_cytology_ID)
    {
        $cytology_order = $this->m_pa_cytology_order->get($biopsy_cytology_ID);
        $PA_order = $this->m_pathological_anatomy_order->get($cytology_order->PA_order_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['default_request_date'] = $PA_order->SampleRequestDate;

        $data['default_ascitic_liquid'] = $cytology_order->Ascitic_Liquid;
        $data['default_pleural_fluid'] = $cytology_order->Pleural_Fluid;
        $data['default_washes'] = $cytology_order->Washes;
        $data['default_washes_info'] = $cytology_order->Washes_Info;
        $data['default_pericardial_fluid'] = $cytology_order->Pericardial_Fluid;
        $data['default_urine'] = $cytology_order->Urine;
        $data['default_expectoration'] = $cytology_order->Expectoration;
        $data['default_LCR'] = $cytology_order->LCR;
        $data['default_others_liquid'] = $cytology_order->Others_Liquid;
        $data['default_others_liquid_info'] = $cytology_order->Others_Liquid_Info;
        $data['default_clinical_diagnosis_liquid'] = $cytology_order->Clinical_Diagnosis_Liquid;

        $data['default_breast'] = $cytology_order->Breast;
        $data['default_nipple_discharge'] = $cytology_order->Nipple_Discharge;
        $data['default_thyroid'] = $cytology_order->Thyroid;
        $data['default_salivary_gland'] = $cytology_order->Salivary_Gland;
        $data['default_ganglion'] = $cytology_order->Ganglion;
        $data['default_ganglion_info'] = $cytology_order->Ganglion_Info;
        $data['default_soft_tissues'] = $cytology_order->Soft_Tissues;
        $data['default_soft_tissues_info'] = $cytology_order->Soft_Tissues_Info;
        $data['default_others_PAAF'] = $cytology_order->Others_PAAF;
        $data['default_others_PAAF_info'] = $cytology_order->Others_PAAF_Info;
        $data['default_clinical_diagnosis_PAAF'] = $cytology_order->Clinical_Diagnosis_PAAF;
        $data['default_previous_PA'] = $cytology_order->Previous_PA;
        $data['default_result'] = $cytology_order->Result;

        $data['default_remarks'] = $cytology_order->Remarks;
        $data['default_active'] = $cytology_order->Active;

        $data['default_value'] = array($cytology_order->Washes_Info, $cytology_order->Others_Liquid_Info,
            $cytology_order->Ganglion_Info, $cytology_order->Soft_Tissues_Info, $cytology_order->Others_PAAF_Info,
            $cytology_order->Clinical_Diagnosis_Liquid, $cytology_order->Clinical_Diagnosis_PAAF);

        $data['group1_options'] = array();
        $data['group2_options'] = array();
        $group = 1;
        $fields = $this->db->field_data('pa_cytology_order');
        foreach($fields as $col) {
            if ($group == 1) {
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    $data['group1_options'][$col->name] = lang($col->name);
//                    array_push($data['group1_options'], $col->name);
                }
                if($col->name == 'Others_Liquid'){
                    $group = 2;
                }
            } else{
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    $data['group2_options'][$col->name] = lang($col->name);
//                    array_push($data['group2_options'], $col->name);
                }
            }
        }

        $group = 1;
        $data['checked1'] = array();
        $data['checked2'] = array();
        foreach($fields as $col) {
            $field_name = $col->name;
            $col_name = $cytology_order->$field_name;
            if ($group == 1) {
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    if ($col_name == 1) {
                        array_push($data['checked1'], $col->name);
                    }
                }
                if($col->name == 'Others_Liquid'){
                    $group = 2;
                }
            } else{
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    if ($col_name == 1) {
                        array_push($data['checked2'], $col->name);
                    }
                }
            }
        }

//        $this->form_validation->set_rules('ascitic_liquid', lang('Ascitic Liquid'), 'trim');
//        $this->form_validation->set_rules('pleural_fluid', lang('Pleural Fluid'), 'trim');
//        $this->form_validation->set_rules('washes', lang('Washes'), 'trim');
//        $this->form_validation->set_rules('pericardial_fluid', lang('Pericardial Fluid'), 'trim');
//        $this->form_validation->set_rules('urine', lang('Urine'), 'trim|required');
//        $this->form_validation->set_rules('expectoration', lang('Expectoration'), 'trim');
//        $this->form_validation->set_rules('LCR', lang('LCR'), 'trim');
//        $this->form_validation->set_rules('others_liquid', lang('Others'), 'trim');
//        $this->form_validation->set_rules('breast', lang('Breast (Lump)'), 'trim');
//        $this->form_validation->set_rules('nipple_discharge', lang('Nipple Discharge'), 'trim');
//        $this->form_validation->set_rules('thyroid', lang('Thyroid'), 'trim');
//        $this->form_validation->set_rules('salivary_gland', lang('Salivary Gland'), 'trim');
//        $this->form_validation->set_rules('ganglion', lang('Ganglion'), 'trim');
//        $this->form_validation->set_rules('soft_tissues', lang('Soft Tissues'), 'trim');
//        $this->form_validation->set_rules('others_PAAF', lang('Others'), 'trim');
        $this->form_validation->set_rules('cytology_liquids[]', lang('CYTOLOGY OF LIQUIDS'), 'xss_clean');
        $this->form_validation->set_rules('paaf[]', lang('PAAF'), 'xss_clean');

        $this->form_validation->set_rules('washes_info', lang('Washes Info'), 'trim');
        $this->form_validation->set_rules('others_liquid_info', lang('Others (specify)'), 'trim');
        $this->form_validation->set_rules('clinical_diagnosis_liquid', lang('Clinical Diagnosis'), 'trim');
        $this->form_validation->set_rules('ganglion_info', lang('Ganglion (specify location)'), 'trim');
        $this->form_validation->set_rules('soft_tissues_info', lang('Soft tissues (specify location)'), 'trim');
        $this->form_validation->set_rules('others_PAAF_info', lang('Others (specify)'), 'trim');
        $this->form_validation->set_rules('clinical_diagnosis_PAAF', lang('Clinical Information / Diagnosis'), 'trim');
        $this->form_validation->set_rules('previous_PA', lang('Have Previous Analysis?'), 'trim');
        $this->form_validation->set_rules('result', lang('Result'), 'trim');

        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');


        if ($this->form_validation->run($this) == FALSE) {
            $this->render('update_cytology', $data);
        } else {
            $update_PA_order = array(
                'Result_Status' => 'Done',
                'SampleCollectedBy' => $this->get_session('uid'),
                'CollectionDateTime' => Date('Y-m-d@H:m:s')
            );
            $this->m_pathological_anatomy_order->update($cytology_order->PA_order_ID, $update_PA_order);

            $group1 = $this->input->post('cytology_liquids') === FALSE ? '' : implode(',', $this->input->post('cytology_liquids'));
            $group2 = $this->input->post('paaf') === FALSE ? '' : implode(',', $this->input->post('paaf'));

            $update_cytology_order = array(
                'Result_Status' => 'Done',
                'Clinical_Diagnosis_Liquid' => $this->input->post('clinical_diagnosis_liquid'),
                'Clinical_Diagnosis_PAAF' => $this->input->post('clinical_diagnosis_PAAF'),
                'Previous_PA' => $this->input->post('previous_PA'),

                'Result' => $this->input->post('result'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
            );

            $group = 1;
            foreach($fields as $col) {
                if ($group == 1) {
                    if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                        if(in_array($col->name, explode(",", $group1))){
                            $update_cytology_order[$col->name] = 1;
                        } else {
                            $update_cytology_order[$col->name] = 0;
                        }
                    }
                    if($col->name == 'Others_Liquid'){
                        $group = 2;
                    }

                    if($col->name == 'Washes' and !in_array($col->name, explode(",", $group1))){
                        $update_cytology_order['Washes_Info'] = '';
                    } elseif ($col->name == 'Washes' and in_array($col->name, explode(",", $group1))){
                        $update_cytology_order['Washes_Info'] = $this->input->post('washes_info');
                    }

                    if($col->name == 'Others_Liquid' and !in_array($col->name, explode(",", $group1))){
                        $update_cytology_order['Others_Liquid_Info'] = '';
                    } elseif ($col->name == 'Others_Liquid' and in_array($col->name, explode(",", $group1))){
                        $update_cytology_order['Others_Liquid_Info'] = $this->input->post('others_liquid_info');
                    }
                } else{
                    if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                        if(in_array($col->name, explode(",", $group2))){
                            $update_cytology_order[$col->name] = 1;
                        } else {
                            $update_cytology_order[$col->name] = 0;
                        }
                    }
                    if($col->name == 'Ganglion' and !in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Ganglion_Info'] = '';
                    } elseif ($col->name == 'Ganglion' and in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Ganglion_Info'] = $this->input->post('ganglion_info');
                    }

                    if($col->name == 'Soft_Tissues' and !in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Soft_Tissues_Info'] = '';
                    } elseif ($col->name == 'Soft_Tissues' and in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Soft_Tissues_Info'] = $this->input->post('soft_tissues_info');
                    }

                    if($col->name == 'Others_PAAF' and !in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Others_PAAF_Info'] = '';
                    } elseif ($col->name == 'Others_PAAF' and in_array($col->name, explode(",", $group2))){
                        $update_cytology_order['Others_PAAF_Info'] = $this->input->post('others_paaf_info');
                    }
                }
            }

            $this->m_pa_cytology_order->update($biopsy_cytology_ID, $update_cytology_order);

            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('pathological_anatomy_order/search');
        }
    }

    public function update_cv_cytology($biopsy_cytology_ID)
    {
        $cv_cytology_order = $this->m_pa_cv_cytology_order->get($biopsy_cytology_ID);
        $PA_order = $this->m_pathological_anatomy_order->get($cv_cytology_order->PA_order_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['default_request_date'] = $PA_order->SampleRequestDate;

        $data['default_analysis_description'] = $cv_cytology_order->Analysis_Description;
        $data['default_scrubbed_from'] = $cv_cytology_order->Scrubbed_From;
        $data['default_sample_taken_by'] = $cv_cytology_order->Sample_Taken_By;
        $data['default_sample_taken_by_info'] = $cv_cytology_order->Sample_Taken_By_Info;
        $data['default_research_required'] = $cv_cytology_order->Research_Required;

        $data['default_pregnant'] = $cv_cytology_order->Pregnant;
        $data['default_pregnancy'] = $cv_cytology_order->Pregnancy;
        $data['default_parity'] = $cv_cytology_order->Parity;
        $data['default_menopause_phase'] = $cv_cytology_order->Menopause_Phase;
        $data['default_menopause_phase_info'] = $cv_cytology_order->Menopause_Phase_Info;
        $data['default_menstrual_period'] = $cv_cytology_order->Menstrual_Period;
        $data['default_smoker'] = $cv_cytology_order->Smoker;
        $data['default_hormone_replacement_therapy'] = $cv_cytology_order->Hormone_Replacement_Therapy;
        $data['default_clinical_diagnosis'] = $cv_cytology_order->Clinical_Diagnosis;

        $data['default_previous_PA'] = $cv_cytology_order->Previous_PA;
        $data['default_result'] = $cv_cytology_order->Result;

        $data['default_remarks'] = $cv_cytology_order->Remarks;
        $data['default_active'] = $cv_cytology_order->Active;

        $data['default_value'] = array($cv_cytology_order->Contraception_Other_Info, $cv_cytology_order->Tratamento_Anterior_Other_Info);

        $data['group1_options'] = array();
        $data['group2_options'] = array();
        $data['group3_options'] = array();
        $data['group4_options'] = array();
        $group = 1;
        $fields = $this->db->field_data('pa_cv_cytology_order');
        foreach($fields as $col) {
            if ($group == 1) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group1_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Polyp'){
                    $group = 2;
                }
            } elseif ($group == 2){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group2_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Chlamydia'){
                    $group = 3;
                }
            } elseif ($group == 3){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group3_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Contraception_Other'){
                    $group = 4;
                }
            } elseif ($group == 4){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group4_options'][$col->name] = lang($col->name);
                }
            }
        }

        $group = 1;
        $data['checked1'] = array();
        $data['checked2'] = array();
        $data['checked3'] = array();
        $data['checked4'] = array();
        foreach($fields as $col) {
            $field_name = $col->name;
            $col_name = $cv_cytology_order->$field_name;
            if ($group == 1) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked1'], $col->name);
                    }
                }
                if($col->name == 'Polyp'){
                    $group = 2;
                }
            } elseif ($group == 2) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked2'], $col->name);
                    }
                }
                if($col->name == 'Chlamydia'){
                    $group = 3;
                }
            } elseif ($group == 3) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked3'], $col->name);
                    }
                }
                if($col->name == 'Contraception_Other'){
                    $group = 4;
                }
            } elseif ($group == 4) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked4'], $col->name);
                    }
                }
            }
        }

        $this->form_validation->set_rules('analysis_description', lang('Washes Info'), 'trim|xss_clean');
        $this->form_validation->set_rules('scrubbed_from', lang('Others (specify)'), 'trim|xss_clean');
        $this->form_validation->set_rules('sample_taken_by', lang('Clinical Diagnosis'), 'trim|xss_clean');
        $this->form_validation->set_rules('research_required', lang('Ganglion (specify location)'), 'trim|xss_clean');

        $this->form_validation->set_rules('pregnancy', lang('Pregnancy'), 'trim|xss_clean');
        $this->form_validation->set_rules('parity', lang('Parity'), 'trim|xss_clean');
        $this->form_validation->set_rules('pregnant', lang('Are You actually Pregnant?'), 'trim|xss_clean');
        $this->form_validation->set_rules('menopause_phase', lang('Menopause Phase'), 'trim|xss_clean');
        $this->form_validation->set_rules('menopause_phase_info', lang('Menopause Phase Info'), 'trim|xss_clean');
        $this->form_validation->set_rules('menstrual_period', lang('Menstrual Period'), 'trim|xss_clean');
        $this->form_validation->set_rules('smoker', lang('Smoker'), 'trim|xss_clean');
        $this->form_validation->set_rules('default_hormone_replacement_therapy', lang('Hormone Replacement Therapy'), 'trim|xss_clean');
        $this->form_validation->set_rules('clinical_diagnosis', lang('Clinical Diagnosis'), 'trim|xss_clean');

        $this->form_validation->set_rules('cervix_appearance[]', lang('Cervix Appearance'), 'xss_clean');
        $this->form_validation->set_rules('infections_with[]', lang('Infections With'), 'xss_clean');
        $this->form_validation->set_rules('contraception[]', lang('Contraception'), 'xss_clean');
        $this->form_validation->set_rules('previous_treatment[]', lang('Previous Treatment'), 'xss_clean');

        $this->form_validation->set_rules('previous_PA', lang('Have Previous Analysis?'), 'trim|xss_clean');
        $this->form_validation->set_rules('result', lang('Result'), 'trim|xss_clean');

        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim|xss_clean');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required|xss_clean');


        if ($this->form_validation->run($this) == FALSE) {
            $this->render('update_cv_cytology', $data);
        } else {
            $update_PA_order = array(
                'Result_Status' => 'Done',
                'SampleCollectedBy' => $this->get_session('uid'),
                'CollectionDateTime' => Date('Y-m-d@H:m:s')
            );
            $this->m_pathological_anatomy_order->update($cv_cytology_order->PA_order_ID, $update_PA_order);

            $update_cv_cytology_order = array(
                'Result_Status' => 'Done',
                'Analysis_Description' => $this->input->post('analysis_description'),
                'Scrubbed_From' => $this->input->post('scrubbed_from'),
                'Sample_Taken_By' => $this->input->post('sample_taken_by'),
                'Research_Required' => $this->input->post('research_required'),

                'Pregnancy' => $this->input->post('pregnancy'),
                'Parity' => $this->input->post('parity'),
                'Pregnant' => $this->input->post('pregnant'),
                'Menopause_Phase' => $this->input->post('menopause_phase'),
                'Smoker' => $this->input->post('smoker'),
                'Hormone_Replacement_Therapy' => $this->input->post('hormone_replacement_therapy'),
                'Menopause_Phase_Info' => $this->input->post('menopause_phase_info'),
                'Menstrual_Period' => $this->input->post('menstrual_period'),
                'Clinical_Diagnosis' => $this->input->post('clinical_diagnosis'),

                'Previous_PA' => $this->input->post('previous_PA'),
                'Result' => $this->input->post('result'),
                'Remarks' => $this->input->post('remarks'),
                'Active' => $this->input->post('active'),
            );

            $group1 = $this->input->post('cervix_appearance') === FALSE ? '' : implode(',', $this->input->post('cervix_appearance'));
            $group2 = $this->input->post('infections_with') === FALSE ? '' : implode(',', $this->input->post('infections_with'));
            $group3 = $this->input->post('contraception') === FALSE ? '' : implode(',', $this->input->post('contraception'));
            $group4 = $this->input->post('previous_treatment') === FALSE ? '' : implode(',', $this->input->post('previous_treatment'));

            $group = 1;
            foreach($fields as $col) {
                if($col->name == 'Sample_Taken_By' and $this->input->post('sample_taken_by') !== '2'){
                    $update_cv_cytology_order['Sample_Taken_By_Info'] = '';
                } elseif ($col->name == 'Sample_Taken_By' and $this->input->post('sample_taken_by') == '2'){
                    $update_cv_cytology_order['Sample_Taken_By_Info'] = $this->input->post('sample_taken_by_info');
                }

                if ($group == 1) {
                    if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                        if(in_array($col->name, explode(",", $group1))){
                            $update_cv_cytology_order[$col->name] = 1;
                        } else {
                            $update_cv_cytology_order[$col->name] = 0;
                        }
                    }
                    if($col->name == 'Polyp'){
                        $group = 2;
                    }
                } elseif ($group == 2) {
                    if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                        if(in_array($col->name, explode(",", $group2))){
                            $update_cv_cytology_order[$col->name] = 1;
                        } else {
                            $update_cv_cytology_order[$col->name] = 0;
                        }
                    }
                    if($col->name == 'Chlamydia'){
                        $group = 3;
                    }
                } elseif ($group == 3) {
                    if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                        if(in_array($col->name, explode(",", $group3))){
                            $update_cv_cytology_order[$col->name] = 1;
                        } else {
                            $update_cv_cytology_order[$col->name] = 0;
                        }
                    }
                    if($col->name == 'Contraception_Other'){
                        $group = 4;
                    }

                    if($col->name == 'Contraception_Other' and !in_array($col->name, explode(",", $group3))){
                        $update_cv_cytology_order['Contraception_Other_Info'] = '';
                    } elseif ($col->name == 'Contraception_Other' and in_array($col->name, explode(",", $group3))){
                        $update_cv_cytology_order['Contraception_Other_Info'] = $this->input->post('contraception_other_info');
                    }

                } elseif ($group == 4) {
                    if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                        if(in_array($col->name, explode(",", $group4))){
                            $update_cv_cytology_order[$col->name] = 1;
                        } else {
                            $update_cv_cytology_order[$col->name] = 0;
                        }
                    }

                    if($col->name == 'Tratamento_Anterior_Other' and !in_array($col->name, explode(",", $group4))){
                        $update_cv_cytology_order['Tratamento_Anterior_Other_Info'] = '';
                    } elseif ($col->name == 'Tratamento_Anterior_Other' and in_array($col->name, explode(",", $group4))){
                        $update_cv_cytology_order['Tratamento_Anterior_Other_Info'] = $this->input->post('tratamento_anterior_other_info');
                    }

                }
            }

            $this->m_pa_cv_cytology_order->update($biopsy_cytology_ID, $update_cv_cytology_order);

            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('pathological_anatomy_order/search');
        }
    }

    public function update($order_id)
    {
        $lab_order = $this->m_lab_order->with('group')->get($order_id);
        $data['lab_order'] = $lab_order;
        $data['pid'] = $lab_order->PID;
        $data['default_priority'] = $lab_order->Priority;
        $data['default_test_group'] = $lab_order->group->Name;
        $data['default_create_time'] = $lab_order->CreateDate;
        $data['lab_order_items'] = array();
        foreach ($this->m_lab_order_items->with('lab_test')->get_many_by(array('LAB_ORDER_ID' => $order_id)) as $row) {
            $tmp['ID'] = $row->LAB_ORDER_ITEM_ID;
            $tmp['Name'] = $row->lab_test->Name;
            $tmp['TestResult'] = $row->TestValue;
            $tmp['RefValue'] = $row->lab_test->RefValue;
            array_push($data['lab_order_items'], $tmp);
        }
        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $this->m_lab_order->update($order_id, array('Status' => 'Done'));
            foreach ($this->input->post('result') as $key => $result) {
                $item = array(
                    'Status' => 'Done',
                    'TestValue' => $result,
                );
                $this->m_lab_order_items->update($key, $item);
            }
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('patient_lab_order/search');
        }
    }

    public function view_biopsy_result($PA_order_ID)
    {
        $PA_order = $this->m_pathological_anatomy_order->get($PA_order_ID);
        $biopsy_order = $this->m_pa_biopsy_order->get($PA_order->Biopsy_Cytology_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['biopsy_id'] = $PA_order->Biopsy_Cytology_ID;

        $data['default_status'] = $biopsy_order->Result_Status;
        $data['default_priority'] = $biopsy_order->Priority;

        $request_by = $this->m_user->get($PA_order->SampleRequestBy);
        $data['default_request_by'] = $request_by->Title . ' ' . $request_by->Name . ' ' . $request_by->OtherName;
        $data['default_request_date'] = $PA_order->SampleRequestDate;
        $request_doctor = $this->m_doctor->get($PA_order->Doctor_who_Requested);
        $data['default_request_doctor'] = $request_doctor->Name;

        $collect_by = $this->m_user->get($PA_order->SampleCollectedBy);
        $data['default_collected_by'] = $collect_by->Title . ' ' . $collect_by->Name . ' ' . $collect_by->OtherName;
        $data['default_collected_date'] = $PA_order->CollectionDateTime;
        $doctor_in_charge = $this->m_doctor->get($PA_order->Doctor_in_Charge);
        $data['default_doctor_in_charge'] = $doctor_in_charge->Name;

        $data['default_kind_of_product'] = $biopsy_order->Kind_of_Product;
        $data['default_collection_method'] = $biopsy_order->CollectionMethod;
        $data['default_fixed_on'] = $biopsy_order->Fixed_On;
        $data['default_wound_centre'] = $biopsy_order->Wound_Centre;
        $data['default_extracted'] = $biopsy_order->Extracted;
        $data['default_previous_pa'] = $biopsy_order->Previous_PA;
        $data['default_old_result'] = $biopsy_order->Old_Result;
        $data['default_macroscopic'] = $biopsy_order->Macroscopic;
        $data['default_microscopic'] = $biopsy_order->Microscopic;
        $data['default_pa_diagnosis'] = $biopsy_order->PA_Diagnosis;
        $data['default_topography'] = $biopsy_order->Topography;
        $data['default_morphology'] = $biopsy_order->Morphology;
        $data['default_remarks'] = $biopsy_order->Remarks;
        $data['default_active'] = $biopsy_order->Active;

        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('view_biopsy_result', $data);
        } else {
            $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $PA_order->PA_ID);
        }
    }

    public function view_cytology_result($PA_order_ID)
    {
        $PA_order = $this->m_pathological_anatomy_order->get($PA_order_ID);
        $cytology_order = $this->m_pa_cytology_order->get($PA_order->Biopsy_Cytology_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['cytology_id'] = $PA_order->Biopsy_Cytology_ID;

        $data['default_request_date'] = $PA_order->SampleRequestDate;

        $data['default_previous_PA'] = $cytology_order->Previous_PA;
        $data['default_result'] = $cytology_order->Result;

        $data['default_remarks'] = $cytology_order->Remarks;
        $data['default_active'] = $cytology_order->Active;

        $data['default_value'] = array($cytology_order->Washes_Info, $cytology_order->Others_Liquid_Info,
            $cytology_order->Ganglion_Info, $cytology_order->Soft_Tissues_Info, $cytology_order->Others_PAAF_Info,
            $cytology_order->Clinical_Diagnosis_Liquid, $cytology_order->Clinical_Diagnosis_PAAF);

        $data['group1_options'] = array();
        $data['group2_options'] = array();
        $group = 1;
        $fields = $this->db->field_data('pa_cytology_order');
        foreach($fields as $col) {
            if ($group == 1) {
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    $data['group1_options'][$col->name] = lang($col->name);
//                    array_push($data['group1_options'], $col->name);
                }
                if($col->name == 'Others_Liquid'){
                    $group = 2;
                }
            } else{
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    $data['group2_options'][$col->name] = lang($col->name);
//                    array_push($data['group2_options'], $col->name);
                }
            }
        }

        $group = 1;
        $data['checked1'] = array();
        $data['checked2'] = array();
        foreach($fields as $col) {
            $field_name = $col->name;
            $col_name = $cytology_order->$field_name;
            if ($group == 1) {
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    if ($col_name == 1) {
                        array_push($data['checked1'], $col->name);
                    }
                }
                if($col->name == 'Others_Liquid'){
                    $group = 2;
                }
            } else{
                if($col->name != 'Active' and $col->type == 'tinyint' and $col->name != 'Previous_PA'){
                    if ($col_name == 1) {
                        array_push($data['checked2'], $col->name);
                    }
                }
            }
        }

        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('view_cytology_result', $data);
        } else {
            $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $PA_order->PA_ID);
        }
    }

    public function view_cv_cytology_result($PA_order_ID)
    {
        $PA_order = $this->m_pathological_anatomy_order->get($PA_order_ID);
        $cv_cytology_order = $this->m_pa_cv_cytology_order->get($PA_order->Biopsy_Cytology_ID);
        $data['pid'] = $PA_order->PID;
        $data['pa_id'] = $PA_order->PA_ID;
        $data['cv_cytology_id'] = $PA_order->Biopsy_Cytology_ID;

        $data['default_status'] = $cv_cytology_order->Result_Status;
        $data['default_priority'] = $cv_cytology_order->Priority;

        $request_by = $this->m_user->get($PA_order->SampleRequestBy);
        $data['default_request_by'] = $request_by->Title . ' ' . $request_by->Name . ' ' . $request_by->OtherName;
        $data['default_request_date'] = $PA_order->SampleRequestDate;
        $request_doctor = $this->m_doctor->get($PA_order->Doctor_who_Requested);
        $data['default_request_doctor'] = $request_doctor->Name;

        $collect_by = $this->m_user->get($PA_order->SampleCollectedBy);
        $data['default_collected_by'] = $collect_by->Title . ' ' . $collect_by->Name . ' ' . $collect_by->OtherName;
        $data['default_collected_date'] = $PA_order->CollectionDateTime;
        $doctor_in_charge = $this->m_doctor->get($PA_order->Doctor_in_Charge);
        $data['default_doctor_in_charge'] = $doctor_in_charge->Name;

        $data['default_analysis_description'] = $cv_cytology_order->Analysis_Description;
        $data['default_scrubbed_from'] = $cv_cytology_order->Scrubbed_From;
        $data['default_sample_taken_by'] = $cv_cytology_order->Sample_Taken_By;
        $data['default_sample_taken_by_info'] = $cv_cytology_order->Sample_Taken_By_Info;
        $data['default_research_required'] = $cv_cytology_order->Research_Required;

        $data['default_pregnant'] = $cv_cytology_order->Pregnant;
        $data['default_pregnancy'] = $cv_cytology_order->Pregnancy;
        $data['default_parity'] = $cv_cytology_order->Parity;
        $data['default_menopause_phase'] = $cv_cytology_order->Menopause_Phase;
        $data['default_menopause_phase_info'] = $cv_cytology_order->Menopause_Phase_Info;
        $data['default_menstrual_period'] = $cv_cytology_order->Menstrual_Period;
        $data['default_smoker'] = $cv_cytology_order->Smoker;
        $data['default_hormone_replacement_therapy'] = $cv_cytology_order->Hormone_Replacement_Therapy;
        $data['default_clinical_diagnosis'] = $cv_cytology_order->Clinical_Diagnosis;

        $data['default_previous_PA'] = $cv_cytology_order->Previous_PA;
        $data['default_result'] = $cv_cytology_order->Result;

        $data['default_remarks'] = $cv_cytology_order->Remarks;
        $data['default_active'] = $cv_cytology_order->Active;

        $data['default_value'] = array($cv_cytology_order->Contraception_Other_Info, $cv_cytology_order->Tratamento_Anterior_Other_Info);

        $data['group1_options'] = array();
        $data['group2_options'] = array();
        $data['group3_options'] = array();
        $data['group4_options'] = array();
        $group = 1;
        $fields = $this->db->field_data('pa_cv_cytology_order');
        foreach($fields as $col) {
            if ($group == 1) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group1_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Polyp'){
                    $group = 2;
                }
            } elseif ($group == 2){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group2_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Chlamydia'){
                    $group = 3;
                }
            } elseif ($group == 3){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group3_options'][$col->name] = lang($col->name);
                }
                if($col->name == 'Contraception_Other'){
                    $group = 4;
                }
            } elseif ($group == 4){
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    $data['group4_options'][$col->name] = lang($col->name);
                }
            }
        }

        $group = 1;
        $data['checked1'] = array();
        $data['checked2'] = array();
        $data['checked3'] = array();
        $data['checked4'] = array();
        foreach($fields as $col) {
            $field_name = $col->name;
            $col_name = $cv_cytology_order->$field_name;
            if($col->name == 'Sample_Taken_By' and $this->input->post('sample_taken_by') !== '2'){
                $update_cv_cytology_order['Sample_Taken_By_Info'] = '';
            } elseif ($col->name == 'Sample_Taken_By' and $this->input->post('sample_taken_by') == '2'){
                $update_cv_cytology_order['Sample_Taken_By_Info'] = $this->input->post('sample_taken_by_info');
            }
            if ($group == 1) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked1'], $col->name);
                    }
                }
                if($col->name == 'Polyp'){
                    $group = 2;
                }
            } elseif ($group == 2) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked2'], $col->name);
                    }
                }
                if($col->name == 'Chlamydia'){
                    $group = 3;
                }
            } elseif ($group == 3) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked3'], $col->name);
                    }
                }
                if($col->name == 'Contraception_Other'){
                    $group = 4;
                }
            } elseif ($group == 4) {
                if(!in_array($col->name, array('Active', 'Previous_PA', 'Pregnant', 'Smoker', 'Hormone_Replacement_Therapy', 'Menopause_Phase')) and $col->type == 'tinyint'){
                    if ($col_name == 1) {
                        array_push($data['checked4'], $col->name);
                    }
                }
            }
        }

        $this->form_validation->set_rules('example', 'Test Result', 'callback_check_result');

        if ($this->form_validation->run($this) == FALSE) {
            $this->qch_template->load_form_layout('view_cv_cytology_result', $data);
        } else {
            $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $PA_order->PA_ID);
        }
    }

    public function check_result($str)
    {
        if (!is_array($this->input->post('result'))) {
            $this->form_validation->set_message('check_result', "Result can't empty");
            return FALSE;
        }
        foreach ($this->call_back_items as $item) {
            if (!array_key_exists($item['ID'], $this->input->post('result'))) {
                $this->form_validation->set_message('check_result', 'Not enough result');
                return FALSE;
            }
        }
        foreach ($this->input->post('result') as $id => $value) {
            if (empty($value)) {
                $this->form_validation->set_message('check_result', 'Result can not empty');
                return FALSE;
            }
        }
        return TRUE;
    }

    public function get_previous_pa($pid, $continue, $mode = 'HTML')
    {
        $this->load->model("mpatient");
        $data = array();
        $data["patient_pa_order_list"] = $this->m_pathological_anatomy_order->order_by('CreateDate', 'DESC')->get_many_by(array('PID' => $pid));
        $data["continue"] = $continue;
        for ($i = 0; $i < count($data["patient_pa_order_list"]); ++$i) {
            $pa_test_id = $data["patient_pa_order_list"][$i]->PA_test_ID;
            $pa_test = $this->m_pathological_anatomy_tests->get($pa_test_id);
            $data["sample_type"][$i] = $pa_test;
        }
        if ($mode == "HTML") {
            $this->load->vars($data);
            $this->load->view('patient_previous_pa');
        } else {
            return $data["patient_pa_order_list"];
        }
    }

}

