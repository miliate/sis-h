<?php

class Emergency_Visit extends FormController
{
    /** Class constructor: https://www.w3schools.com/php/php_oop_constructor.asp  */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_user');
        $this->load->model('m_patient');
        $this->load->model('m_department');
        $this->load->model('m_emergency_admission');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_covid19');
        $this->load->model('m_patient_examination');
        $this->load->model('m_medical_history');
        $this->load_form_language();
    }

    public function index()
    {
        $this->waiting_patient();
    }

    public function covid19($pid, $active_list_id)
    { 
        if (!Modules::run('permission/check_permission', 'emr_visit', 'create'))
        die('No permission');
    $data = array();
    $data['patient'] = $this->m_patient->get($pid);


        $this->session->set_flashdata(
            'msg', 'Created'
        );

        return $this->create(109902,7888);
    }


    public function create($pid, $active_list_id)
    {
        if (!Modules::run('permission/check_permission', 'emr_visit', 'create'))
            die('No permission');
        $data = array();
        $data['patient'] = $this->m_patient->get($pid);
        $data['id'] = 0;
        $data['default_time'] = date("Y-m-d H:i:s");
        $data['default_complaint'] = '';
        $data['default_weight'] = '';
        $data['default_height'] = '';
        $data['default_sys_bp'] = '';
        $data['default_diast_bp'] = '';
        $data['default_temperature'] = '';
        $data['default_pulse'] = '';
        $data['default_saturation'] = '';
        $data['default_respiratory'] = '';
        $data['default_alert'] = '';
        $data['default_voice'] = '';
        $data['default_pain'] = '0';
        $data['default_un_responsive'] = '0';
        $data['default_severity'] = 1;
        $data['default_remarks'] = '';

        $data['default_destination'] = 1;

        $data['dropdown_severity'] = $this->get_dropdown_severity('result');
        $data['dropdown_area'] = $this->get_dropdown_area('result');


        $this->form_validation->set_rules('pid', 'pid', 'trim|xss_clean|required');
        $this->form_validation->set_rules('date_time_visit', lang('Datetime Visit'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('severity', lang('Triage'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('complaint', lang('Complaint / Injury'), 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
//            if($this->input->post('destination') == 'Discharged') {
//                $destination = 'Discharged ('. $this->input->post('destination1') .')';
//            } else if($this->input->post('destination') == 'Appointment for') {
//                $destination = 'Appointment for '.$this->input->post('destination1');
//            } else if($this->input->post('destination') == 'Admission on ward') {
//                $destination = 'Admission on ward '.$this->input->post('destination1');
//            } else {
//                $destination = 'Died on: '.$this->input->post('destination1');
//            }
            $data = array(
                'PID' => $this->input->post('pid'),
                'DateTimeOfVisit' => $this->input->post('date_time_visit'),
                'Complaint' => $this->input->post('complaint'),
                'Weight' => $this->input->post('weight'),
                'Height' => $this->input->post('height'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'Temprature' => $this->input->post('temperature'),
                'Pulse' => $this->input->post('pulse'),
                'Saturation' => $this->input->post('saturation'),
                'Respiratory' => $this->input->post('respiratory'),
                'Alert' => $this->input->post('alert'),
                'Voice' => $this->input->post('voice'),
                'Pain' => $this->input->post('pain'),
                'UNR' => $this->input->post('un_responsive'),
                'Severity' => $this->input->post('severity'),
                'Status' => 'Observe',
                'Remarks' => $this->input->post('remarks'),
                'ActiveListID' => $active_list_id,
                'Destination' => $this->input->post('destination')
            );
            $emrid = $this->m_emergency_admission->insert($data);
            $update_patient_active = array(
                'VisitID' => $emrid,
                'Status' => lang('Observe')
            );

            $this->m_patient_active_list->update($active_list_id, $update_patient_active);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Created'
            );
            if (is_observe_doctor()) {
              //  $this->redirect_if_no_continue('emergency_visit/add_observe/' . $emrid);
                  $this->redirect_if_no_continue('patient_history/add/' . $pid);
            }   else {
                $this->redirect_if_no_continue('emergency_visit/view/' . $emrid);
            }
        }
    }

    public function edit($emr_id)
    {
        if (!Modules::run('permission/check_permission', 'emr_visit', 'edit'))
            die('No permission');
        $emr = $this->m_emergency_admission->get($emr_id);
        if (empty($emr)) {
            die('No exist');
        }
        $data = array();
        $data['patient'] = $this->m_patient->get($emr->PID);
        $data['id'] = $emr->EMRID;
        $data['default_time'] = $emr->DateTimeOfVisit;
        $data['default_complaint'] = $emr->Complaint;
        $data['default_weight'] = $emr->Weight;
        $data['default_height'] = $emr->Height;
        $data['default_sys_bp'] = $emr->sys_BP;
        $data['default_diast_bp'] = $emr->diast_BP;
        $data['default_temperature'] = $emr->Temprature;
        $data['default_pulse'] = $emr->Pulse;
        $data['default_saturation'] = $emr->Saturation;
        $data['default_respiratory'] = $emr->Respiratory;
        $data['default_alert'] = $emr->Alert;
        $data['default_voice'] = $emr->Voice;
        $data['default_pain'] = $emr->Pain;
        $data['default_un_responsive'] = $emr->UNR;
        $data['default_severity'] = $emr->Severity;
        $data['default_remarks'] = $emr->Remarks;

        $data['default_destination'] = $emr->Destination;

        $data['dropdown_severity'] = $this->get_dropdown_severity('result');
        $data['dropdown_area'] = $this->get_dropdown_area('result');

        $this->form_validation->set_rules('pid', 'pid', 'trim|xss_clean|required');
        $this->form_validation->set_rules('date_time_visit', lang('Datetime Visit'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('severity', lang('Triage'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('complaint', lang('Complaint / Injury'), 'trim|xss_clean|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
//            var_dump($_POST);
            $data = array(
                'PID' => $this->input->post('pid'),
                'DateTimeOfVisit' => $this->input->post('date_time_visit'),
                'Complaint' => $this->input->post('complaint'),
                'Weight' => $this->input->post('weight'),
                'Height' => $this->input->post('height'),
                'sys_BP' => $this->input->post('sys_bp'),
                'diast_BP' => $this->input->post('diast_bp'),
                'Temprature' => $this->input->post('temperature'),
                'Pulse' => $this->input->post('pulse'),
                'Saturation' => $this->input->post('saturation'),
                'Respiratory' => $this->input->post('respiratory'),
                'Alert' => $this->input->post('alert'),
                'Voice' => $this->input->post('voice'),
                'Pain' => $this->input->post('pain'),
                'UNR' => $this->input->post('un_responsive'),
                'Severity' => $this->input->post('severity'),
                //'Status' => 'Observe',
                'Remarks' => $this->input->post('remarks'),
                'Destination' => $this->input->post('destination')
            );
            $this->m_emergency_admission->update($emr_id, $data);
            //redirect
            $this->session->set_flashdata(
                'msg', 'Updated'
            );
            $this->redirect_if_no_continue('emergency_visit/view' . $emr_id);
        }
    }

    public function get_dropdown_severity($type = 'json')
    {
        $this->load->model('m_severity');
        $result = $this->m_severity->dropdown('Name', 'Name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_area($type = 'json')
    {
        $this->load->model('m_area');
        $result = $this->m_area->dropdown('Name', 'Name');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }


   public function triage()
   {
       $this->set_top_selected_menu('emergency_visit/triage');
       $qry = "SELECT
               EMRID,
               DateTimeOfVisit,
               patient.PID,
               CONCAT(patient.Firstname,' ',patient.Name) AS Name,
               emergency_admission.Complaint,
               emergency_admission.Severity
               FROM emergency_admission
               LEFT JOIN `patient` ON patient.PID = emergency_admission.PID
               WHERE (emergency_admission.Status = 'Triage')
	            ";
       $this->load->model('mpager', "page");
       $page = $this->page;
       $page->setSql($qry);
       $page->setDivId("patient_list"); //important
       $page->setDivClass('');
       $page->setRowid('EMRID');
       $page->setCaption(lang("Triage Patient"));
       $page->setShowHeaderRow(true);
       $page->setShowFilterRow(true);
       $page->setShowPager(true);
       $page->setColNames(array("", lang("Time"), lang("Patient ID"), lang("Patient Name"), lang("Reason for Complaint"), lang("Severity")));
       $page->setRowNum(25);
       $page->setColOption("EMRID", array("search" => false, "hidden" => true));
       $page->setColOption("DateTimeOfVisit", $page->getDateSelector(date('Y-m-d')));
       $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "40"));
       $page->setColOption("Name", array("search" => true, "hidden" => false));
       $page->setColOption("Complaint", array("search" => true, "hidden" => false));
       $page->setColOption("Severity", array('stype' => 'select',
           'editoptions' => array(
               'value' => ':'.lang('All').';Critical:Crítico;Urgent:Urgente;Normal:Normal;Less Urgent:Pouco Urgente'
           )));
       $page->setAfterInsertRow('function(rowid, data){
       var alertText = \'\';

       for (property in data)
           alertText +=data[property];
       if (alertText.match(/^.*Emergency/))
       {
           $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
       }
       if (alertText.match(/^.*Very urgent/))
       {
           $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
       }
       if (alertText.match(/^.*Urgent/))
       {
           $(\'#\'+rowid).css({\'background\':\'yellow\'});
       }
       if (alertText.match(/^.*Non-urgent/))
       {
           $(\'#\'+rowid).css({\'background\':\'#66ff66\'});
       }
       if (alertText.match(/^.*Deceased/))
       {
           $(\'#\'+rowid).css({\'background\':\'#00ccff\'});
       }
       }');
       if (has_permission('emr_visit', 'edit')) {
           $page->gridComplete_JS
               = "function() {
           $('#patient_list .jqgrow').mouseover(function(e) {
               var rowId = $(this).attr('id');
               $(this).css({'cursor':'pointer'});
           }).mouseout(function(e){
           }).click(function(e){
               var rowId = $(this).attr('id');
               window.location='" . site_url("/emergency_visit/edit") . "/'+rowId+'';
           });
           }";
       }
       if (Modules::run('permission/check_permission', 'emr_observe', 'create')) {
           $page->gridComplete_JS
               = "function() {
           $('#patient_list .jqgrow').mouseover(function(e) {
               var rowId = $(this).attr('id');
               $(this).css({'cursor':'pointer'});
           }).mouseout(function(e){
           }).click(function(e){
               var rowId = $(this).attr('id');
               $('#confirm-modal').modal('show');
               $('#confirm-observe').attr('href','" . site_url("/emergency_visit/add_observe") . "/'+rowId+'');
           });
           }";
       }

       $page->setOrientation_EL("L");
       $data['pager'] = $page->render(false);
       $this->qch_template->load_form_layout('triage', $data);
   }


//    public function triage()
//    {
//        $this->set_top_selected_menu('emergency_visit/triage');
//        $qry = "SELECT
//                EMRID,
//                DateTimeOfVisit,
//                patient.PID,
//                patient.Name,
//                patient.OtherName,
//                emergency_admission.Severity
//                FROM emergency_admission
//                LEFT JOIN `patient` ON patient.PID = emergency_admission.PID
//                WHERE (emergency_admission.Status = 'Triage')
//	            ";
//        $this->load->model('mpager', "page");
//        $page = $this->page;
//        $page->setSql($qry);
//        $page->setDivId("patient_list"); //important
//        $page->setDivClass('');
//        $page->setRowid('EMRID');
//        $page->setCaption("Triage Patient");
//        $page->setShowHeaderRow(true);
//        $page->setShowFilterRow(true);
//        $page->setShowPager(true);
//        $page->setColNames(array("", "Time", "Patient ID", "Name", "Other Name", "Severity"));
//        $page->setRowNum(25);
//        $page->setColOption("EMRID", array("search" => false, "hidden" => true));
//        $page->setColOption("DateTimeOfVisit", $page->getDateSelector(date('Y-m-d')));
//        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "70"));
//        $page->setColOption("Name", array("search" => true, "hidden" => false));
//        $page->setColOption("Severity", array('stype' => 'select',
//            'editoptions' => array(
//                'value' => ':All;Critical:Critical;Urgent:Urgent;Normal:Normal;Less Urgent:Less Urgent'
//            )));
//        $page->setAfterInsertRow('function(rowid, data){
//        var alertText = \'\';
//
//        for (property in data)
//            alertText +=data[property];
//        if (alertText.match(/^.*Emergency/))
//        {
//            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
//        }
//        if (alertText.match(/^.*Very urgent/))
//        {
//            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
//        }
//        if (alertText.match(/^.*Urgent/))
//        {
//            $(\'#\'+rowid).css({\'background\':\'yellow\'});
//        }
//        if (alertText.match(/^.*Non-urgent/))
//        {
//            $(\'#\'+rowid).css({\'background\':\'#66ff66\'});
//        }
//        if (alertText.match(/^.*Deceased/))
//        {
//            $(\'#\'+rowid).css({\'background\':\'#00ccff\'});
//        }
//        }');
//        if (has_permission('emr_visit', 'edit')) {
//            $page->gridComplete_JS
//                = "function() {
//            $('#patient_list .jqgrow').mouseover(function(e) {
//                var rowId = $(this).attr('id');
//                $(this).css({'cursor':'pointer'});
//            }).mouseout(function(e){
//            }).click(function(e){
//                var rowId = $(this).attr('id');
//                window.location='" . site_url("/emergency_visit/edit") . "/'+rowId+'';
//            });
//            }";
//        }
//        if (Modules::run('permission/check_permission', 'emr_observe', 'create')) {
//            $page->gridComplete_JS
//                = "function() {
//            $('#patient_list .jqgrow').mouseover(function(e) {
//                var rowId = $(this).attr('id');
//                $(this).css({'cursor':'pointer'});
//            }).mouseout(function(e){
//            }).click(function(e){
//                var rowId = $(this).attr('id');
//                $('#confirm-modal').modal('show');
//                $('#confirm-observe').attr('href','" . site_url("/emergency_visit/add_observe") . "/'+rowId+'');
//            });
//            }";
//        }
//
//        $page->setOrientation_EL("L");
//        $data['pager'] = $page->render(false);
//        $this->qch_template->load_form_layout('triage', $data);
//    }

    public function my_observed_patient()
    {
        $uid = $this->session->userdata('uid');
        $qry = "SELECT
                      EMRID,
                      DateTimeOfVisit,
                      patient.PID,
                      CONCAT(patient.Firstname,' ',patient.Name) AS Name,
                 
                      emergency_admission.Complaint,
                      emergency_admission.Severity,
                      emergency_admission.Status
                      
                      FROM emergency_admission
                    
                      LEFT JOIN patient ON patient.PID = emergency_admission.PID
                      WHERE (ObservationDoctorUID  = " . $uid . ")
                      
                      ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('EMRID');
        $page->setCaption(lang('My Observed Patients'));
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("", lang("Observation Date"), lang("PID"), lang("Patient Name"), lang("Reason for Complaint"), lang("Severity"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("EMRID", array("search" => false, "hidden" => true, "width" => "40"));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "40"));
        $page->setColOption("Name", array("search" => true, "hidden" => false));
        $page->setColOption("Complaint", array("search" => false, "hidden" => false));
        $page->setColOption("Severity", array('stype' => 'select',
            'editoptions' => array(
                //Antes estava assim:
                //'value' => ':All;Critical:Critical;Urgent:Urgent;Normal:Normal;Less Urgent:Less Urgent'
                //Mudei para:
                'value' => ':'.lang('All').';'.lang('Emergency').':'.lang('Emergency').';'.lang('Very Urgent').':'.lang('Very Urgent').';'.lang('Urgent').':'.lang('Urgent').';'.lang('Normal').':'.lang('Normal').';'.lang('Less Urgent').':'.lang('Less Urgent').';'.lang('Death').':'.lang('Death')
            )));
        $page->setColOption("Status", array('stype' => 'select',
            'editoptions' => array(
                'value' => ':'.lang('All').';'.lang('Observe').':'.lang('Observe').';'.lang('Discharge').':'.lang('Discharge')
            )));
           
        $page->setColOption("DateTimeOfVisit", $page->getDateSelector(date('Y-m-d')));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';

        for (property in data)
            alertText +=data[property];
        if (alertText.match(/^.*Critical/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Urgent/) || alertText.match(/^.*Urgente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Emergency/) || alertText.match(/^.*Emergencia/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Normal/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Less Urgent/) || alertText.match(/^.*Nao-Urgente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Death/) || alertText.match(/^.*Obito/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
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
                window.location='" . site_url("/emergency_visit/view") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('triage', $data);
    }
    /** Get dropdown list for all services from "hospital_services" table on 12.08.2021*/
    public function get_dropdown_services($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->order_by('abrev')->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            foreach ($result as $item) {
                $drop_down[$item->service_id] = $item->abrev;
            }
            $drop_down[''] = '';
            return $drop_down;
        }
    } 
//Added on 05.05.2020 by Jcololo 
    public function emergency_statistics()
    {
       
        if (!Modules::run('permission/check_permission', 'emr_visit', 'view'))
            die('No permission');
        $uid = $this->session->userdata('uid');
        $qry = "SELECT
                      EMRID,
                      DateTimeOfVisit,
                      patient.PID,
                    /*  CONCAT(patient.Firstname,' ',patient.Name) AS Name,*/
                      patient.Gender,
                      patient.DateOfBirth,
                     /* patient_active_list.admission_type,*/               
                     emergency_admission.Complaint,
                      emergency_admission.Severity,
                      hospital_services.abrev,
                   /*   patient_active_list.Service,*/
                      emergency_admission.Status
                      
                      FROM emergency_admission
                      LEFT JOIN patient ON patient.PID = emergency_admission.PID
                      LEFT JOIN patient_active_list ON patient_active_list.ACTIVE_ID = emergency_admission.ActiveListId
                      LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service

                      WHERE patient.PID = emergency_admission.PID
                      AND  patient_active_list.ACTIVE_ID = emergency_admission.ActiveListId

                     AND  (patient_active_list.Active  = 1)
                      
                      ";
          if ($this->DEPARTMENT == 'EMR') {
            $dropdown_services = $this->get_dropdown_services(1, 'return');
        }
        elseif ($this->DEPARTMENT == 'SAP') {
            $dropdown_services = $this->get_dropdown_services(3, 'return');
        }
        else {
            $dropdown_services = $this->get_dropdown_services(2, 'return');                                                                                                                                                   $this->form_validation->set_rules('doctor', 'Medico', 'trim|required');
        }             
         $option_service = ':'.lang('All').';';
         foreach ($dropdown_services as $service) {
             if (strlen($service) > 0) {
                 $option_service .= $service . ':' . $service . ';';
             }
         } 
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('EMRID');
        $page->setCaption("Meus Pacientes Observados");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("", "Data Observação", "NID",  "Sexo","D.Nascimento", "Motivo de Queixa", lang("Triage"), "Servico", "Status"));
        $page->setRowNum(25);
        $page->setColOption("EMRID", array("search" => false, "hidden" => true, "width" => "40"));
        $page->setColOption("DateTimeOfVisit", array("search" => true, "hidden" => false, "width" => "75"));
        $page->setColOption("PID", array("search" => true, "hidden" => false, "width" => "40"));
        $page->setColOption("Gender", array("search" => true, "hidden" => false, "width" => "25","stype" => "select", "searchoptions" => array("value" => ":".lang('All').";M:M;F:F")));
        $page->setColOption("DateOfBirth", array("search" => true, "hidden" => false, "width" => "50"));
      /*  $page->setColOption("Name", array("search" => true, "hidden" => false));*/
       /* $page->setColOption("admission_type", array("search" => true, "hidden" => false));*/
        $page->setColOption("Complaint", array("search" => false, "hidden" => false));
        $page->setColOption("Severity", array('stype' => 'select',
            'editoptions' => array(
                'value' => ':'.lang('All').';'.lang('Emergency').':'.lang('Emergency').';'.lang('Very Urgent').':'.lang('Very Urgent').';'.lang('Urgent').':'.lang('Urgent').';'.lang('Normal').':'.lang('Normal').';'.lang('Less Urgent').':'.lang('Less Urgent').';'.lang('Death').':'.lang('Death')
            )));
            $page->setColOption('abrev', array('stype' => 'select',
            'editoptions' => array(
                'value' => $option_service
            ), 'width' => '120'));
             
        $page->setColOption("Status", array('stype' => 'select',
            'editoptions' => array(
                'value' => ':Todos;Observe:Em Observacao;Discharge:Alta Clinica'
            )));
           
        $page->setColOption("DateTimeOfVisit", $page->getDateSelector(date('Y-m-d')));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';

        for (property in data)
            alertText +=data[property];
        if (alertText.match(/^.*Critical/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Emergency/) || alertText.match(/^.*Emergencia/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Death/) || alertText.match(/^.*Obito/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Urgent/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Normal/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
        if (alertText.match(/^.*Less Urgent/))
        {
            $(\'#\'+rowid).css({\'background\':\'\'});
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
                window.location='" . site_url("/emergency_visit/view") . "/'+rowId+'';
            });
            }";
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $this->qch_template->load_form_layout('triage', $data);
    }

    public function add_observe($emrid)
    {
        $emrgency_visit = $this->m_emergency_admission->get($emrid);
        if (empty($emrgency_visit)) {
            die('Wrong EMR ID');
        }
        $uid = $this->session->userdata('uid');
        $update_data = array(
            'ObservationDoctorUID' => $uid,
          //  'Status' => lang('Observe')
            'Status' => 'Observe'
        );
        $this->m_emergency_admission->update($emrid, $update_data);
        $this->m_patient_active_list->update($emrgency_visit->ActiveListID, array('Status' => lang('Observe')));
        header("Status: 200");
        header("Location: " . site_url('emergency_visit/view/' . $emrid));
    }

    public function info($emrid)
    {
        // Retrieve patient examination info using the model function
        $data['visit_info'] = $this->m_patient_examination->get_patient_exam_info_by_emrid($emrid);
        //var_dump($data);
        if (!$data) {
            // Handle case where data is not found
            show_error(lang('patient_exam_info_not_found'), 404);
            return;
        }

        $this->load->vars($data);
        $this->load->view('emr_info');
    }

    public function view($emrid)
    {
        $data = array();
        if (!isset($emrid) || (!is_numeric($emrid))) {
            die("Emergency visit not found");
            return;
        }
        $data["visit_info"] = $this->m_emergency_admission->as_array()->get($emrid);
//        var_dump($data['visit_info']);
        $visit_date = $data["visit_info"]["DateTimeOfVisit"];
        $today = date("Y-m-d H:i:s");
        $diff = abs(strtotime($today) - strtotime($visit_date));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));;
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $data["visit_info"]["days"] = $days + $months * 30 + $years * 365;
        if (isset($data["visit_info"]["PID"])) {
            $data["patient_info"] = $this->m_patient->as_array()->get($data["visit_info"]["PID"]);
        } else {
            die("Emergency visit not found 1");
            return;
        }
        if (empty($data["patient_info"])) {
            die("OPD Patient not found");
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = Modules::run('patient/print_hin', $data["patient_info"]["HIN"]);

        $data["PID"] = $data["visit_info"]["PID"];
        $data["ID"] = $emrid;
#ea7d7d
        $this->qch_template->load_form_layout('emr_view', $data);
    }

    public function refer_to_adm($emr_id)
    {
        $emrgency_visit = $this->m_emergency_admission->get($emr_id);
        if (empty($emrgency_visit)) {
            die('Wrong EMR ID');
        }
        redirect('admission/refer_to_adm/' . $emrgency_visit->PID . '/EMR/' . $emr_id);
    }

}