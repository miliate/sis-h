<?php

/**
 * Created by Trung Hoang.
 */
class Patient_pathological_anatomy extends FormController
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
//        $this->load->model('m_patient_pathological_anatomy');
        $this->load->model('m_patient_active_nopay');
        $this->load->model('m_admission_type');
        $this->load->model('m_pa_visit');


        $this->_department = $this->session->userdata('department');
        $this->load_form_language();
    }

    function index()
    {
        $this->set_top_selected_menu('active_list');
        if ($this->_department == 'EMR') {
            $this->search('EMR');
        } elseif ($this->_department == 'OPD') {
            $this->search('OPD');
        }
        elseif ($this->_department == 'PA') {
            $this->search('PA');
        }
        else {
            $this->show_no_permission();
        }
    }

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

    public function create($pid, $ref_id)
    {

        $this->DEPARTMENT='PA';
        if (!has_permission('pathological_anatomy', 'create')) {
            $this->show_no_permission();
        }
        
        $data['pid'] = $pid;
        $data['ref_id'] = $ref_id;
        if ($this->DEPARTMENT == 'EMR') {
            $data['default_entry_time'] = date("Y-m-d H:i:s");
        } elseif ($this->DEPARTMENT == 'PA') {
            $data['default_entry_time'] = date("Y-m-d H:i:s");
        }
        else {
            $data['default_entry_time'] = '';
        }

        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['default_department'] = $this->DEPARTMENT;
        $data['default_reason'] = '';
        $data['default_destination'] = 'Consulta';
        $data['default_service'] = '';
        $data['default_doctor'] = '';
        $data['default_admission_type'] = '';

        $data['default_department_hospital'] = '1';
        $data['default_service'] = '1';

        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_doctor'] = $this->get_dropdown_doctor();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_department'] = $this->get_dropdown_departments('return');
        $data['dropdown_service'] = $this->get_dropdown_services(set_value('entry_department', 1), 'return');

        $this->form_validation->set_rules('entry_time', 'Data da Consulta', 'trim|required|callback_check_entry_time');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('reason', 'Motivo de Hospitalização', 'trim|required');
        $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');
        $this->form_validation->set_rules('status', lang('Status'), 'trim|required');
        $this->form_validation->set_rules('entry_service', lang('Service'), 'trim|required');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');
        $this->form_validation->set_rules('destination', lang('Destination'), 'trim|required');

        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data_insert = array(
                'PID' => $pid,
                'Department' => $this->DEPARTMENT,
                'EntryTime' => $this->input->post('entry_time'),
                'admission_type' => $this->input->post('admission_type'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Destination' => $this->input->post('destination'),
                'hospital_department' => $this->input->post('entry_department'),
                'Service' => $this->input->post('entry_service'),
                'Doctor_ID' => $this->input->post('doctor'),

                'Remarks' => $this->input->post('remarks'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'RegistrationDate' => date("Y-m-d H:i:s")
            );

            if( $consulta =  $this->m_patient_active_list->insert($data_insert)) {

                $this->session->set_flashdata(
                    'msg', 'REC: ' . ucfirst(strtolower($this->input->post("name"))) . ' Paciente '.$consulta.' Rastreado Com Sucesso!'
                );

                $this->redirect_if_no_continue('patient_pathological_anatomy');

            } //IF consulta Saved
            else {}

        }
    }

    public function edit($active_id)
    {
        if (!has_permission('pathological_anatomy', 'edit')) {
            $this->show_no_permission();
        }
        $active_list = $this->m_patient_active_list->get($active_id);
        /*     $active_tracker = $this->m_patient_tracker->get(2);
             echo $active_bill = $this->m_sap_bill->get(2);*/
        $patient = $this->m_patient->get($active_list->PID);

        if (empty($active_list))
            die('not found');

        $data['patient'] = $patient;
        $data['active_id'] = $active_id;
        $data['pid'] = $active_list->PID;
        $data['default_department'] = $active_list->Department;
        $data['default_entry_time'] = $active_list->EntryTime;
        $data['default_admission_type'] = $active_list->admission_type;
        $data['default_reason'] = $active_list->HospitalizationReason;
        $data['default_destination'] = $active_list->Destination;
        $data['default_hospital_department'] = $active_list->hospital_department;
        $data['default_service'] = $active_list->Service;
        $data['default_doctor'] = $active_list->Doctor_ID;
        $data['default_remarks'] = $active_list->Remarks;
        $data['default_active'] = $active_list->Active;
        $data['default_status'] = $active_list->Status;

        $data['dropdown_doctor'] = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();

        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_doctor'] = $this->get_dropdown_doctor();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_department'] = $this->get_dropdown_departments('return');
        $data['dropdown_service'] = $this->get_dropdown_services($active_list->hospital_department, 'return');

        $this->set_common_validation();

        if ($this->form_validation->run() == FALSE) {
            $this->render('form_edit_patient_pathological_anatomy', $data);
        } else {
            $data_update = array(
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Remarks' => $this->input->post('remarks'),
                'Service' => $this->input->post('entry_service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'admission_type' => $this->input->post('admission_type'),
                'Destination' => $this->input->post('destination'),
                'hospital_department' => $this->input->post('entry_department'),
            );
            $this->m_patient_active_list->update($active_id, $data_update);

            $this->set_common_validation();

            if ($this->form_validation->run() == FALSE) {
                $this->load_form($data);
            } else {

            }

            $this->redirect_if_no_continue('patient_pathological_anatomy');
        }
    }

    public function get_dropdown_departments($type = 'json')
    {
        $this->load->model('m_hospital_department');
        $result = $this->m_hospital_department->order_by('department_id')->dropdown('department_id', 'abrev');
        if ($type == 'json') {
            print(json_encode($result));
        }
        return $result;
    }

    public function check_entry_time()
    {
        $entry_time = $this->input->post('entry_time');
        $today = date("Y-m-d");
        $max_time = new DateTime();
        $max_time->modify('+300 day');
        $max_time = $max_time->format("Y-m-d");
        if ($entry_time < $today or $entry_time > $max_time) {
            $this->form_validation->set_message('check_entry_time', 'O tempo de entrada estava incorreto');
            return false;
        }
        return true;
    }

    public function search($department)
    {

        $department='PA';
         if ($this->DEPARTMENT == 'EMR') {
            $dropdown_services = $this->get_dropdown_services(1, 'return');
        } elseif ($this->DEPARTMENT == 'PA') {
            $dropdown_services = $this->get_dropdown_services(1, 'return');
        } else {
            $dropdown_services = $this->get_dropdown_services(2, 'return');
        }

        $option_service = ':'.lang('All').';';
        foreach ($dropdown_services as $service) {
            if (strlen($service) > 0) {
                $option_service .= $service . ':' . $service . ';';
            }
        }

        if (!has_permission('pathological_anatomy', 'view')) {
            $this->show_no_permission();
        }
        $qry = "SELECT
                patient_active_list.ACTIVE_ID,
                SUBSTR(patient_active_list.RegistrationDate, 1, 16) as RegistrationDate,
                SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
                patient.PID,
                CONCAT(patient.Firstname,' ',patient.Name) AS Patient,
                patient_emr_reasons.HospitalizationReason,
                patient_active_list.Destination,
                hospital_services.abrev,               
                patient_active_list.Status
                FROM patient_active_list
                LEFT JOIN patient ON patient.PID = patient_active_list.PID
                LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
                LEFT JOIN patient_emr_reasons ON patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
              /*  LEFT JOIN doctor ON doctor.Doctor_ID = patient_active_list.Doctor_ID*/
                WHERE patient_active_list.Active = 1 AND Department = '" . $department . "'
                ";
        $this->load->model('mpager', "page");
        $page = $this->page;
        $page->setSql($qry);
        $page->setRowid('ACTIVE_ID');
        $page->setSortname('ACTIVE_ID');
        $page->setDivId("patient_list"); //important
        $page->setDivClass('');
        $page->setRowid('ACTIVE_ID');
        $page->setCaption("");
        $page->setShowHeaderRow(true);
        $page->setShowFilterRow(true);
        $page->setShowPager(true);
        $page->setColNames(array("Active ID", lang("Time"), lang("VisitDate"), lang("Patient ID"), lang("Patient"),
            lang('Hospitalization Reason'), lang('Destination'), lang("Service"), lang("Status")));
        $page->setRowNum(25);
        $page->setColOption("ACTIVE_ID", array("hidden" => true));
        $page->setColOption("PID", array("search" => true, "width" => 200));
        $page->setColOption("RegistrationDate", $page->getDateSelector());
        if ($this->get_session('user_group_id') != 25) {//this is for emr registrar
            $page->setColOption("EntryTime", $page->getDateSelector(date('Y-m-d')));
        } else {
            $page->setColOption("EntryTime", $page->getDateSelector());
        }
        $page->setColOption('abrev', array('stype' => 'select',
            'editoptions' => array(
                'value' => $option_service
            ), 'width' => '120'));

        $page->setColOption('Destination', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':Todos;Alta:Alta;Consulta:Consulta'
            ), 'width' => '120'));

        $page->setColOption('Status', array('stype' => 'select',
            'editoptions' => array(
                'value' => ':'.lang('All').';'.lang('Pending').':'.lang('Pending').';'.lang('Observe').':'.lang('Observe')
            ), 'width' => '70'));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pending/) || alertText.match(/^.*Pendente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*Observe/) || alertText.match(/^.*Em observacao/))
        {
            $(\'#\'+rowid).css({\'background\':\'#00d185\'});
        }
       }');

        //default group
        $page->gridComplete_JS
            = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                    var patient_id = $(this).find('td:nth-child(4)').text();
                    window.location='" . site_url("/patient/view") . "/'+patient_id+'';
            });
            }";
//        registrar group
        if (Modules::run('permission/check_permission', 'pathological_anatomy', 'edit')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(9)').text();
                var rowId = $(this).attr('id');
                if (status == 'Pending') {
                    window.location='" . site_url("/patient_pathological_anatomy/edit") . "/'+rowId+'';
                } else {
                     window.location='" . site_url("patient_pathological_anatomy/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
        }
//        EMR (PA) doctor group
        if (Modules::run('permission/check_permission', 'add_patient_from_active_list', 'create')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(9)').text();
                var rowId = $(this).attr('id');
                if (status == 'Pending') {
                    $('#observe-confirm-modal').modal('show');
                    $('#confirm-observe').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else {
                    window.location='" . site_url("patient_pathological_anatomy/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
        }

//        if (is_observe_doctor()) {
//            if (Modules::run('permission/check_permission', 'emr_observe', 'create')) {
//                $page->gridComplete_JS
//                    = "function() {
//            $('#patient_list .jqgrow').mouseover(function(e) {
//                var rowId = $(this).attr('id');
//                $(this).css({'cursor':'pointer'});
//            }).mouseout(function(e){
//            }).click(function(e){
//                var rowId = $(this).attr('id');
//                var status = $(this).find('td:nth-child(13)').text();
//                if (status == 'Pending') {
//                    var rowId = $(this).attr('id');
//                    $('#confirm-modal').modal('show');
//                    $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
//                } else if (status == 'Triage') {
//                    $('#observe-confirm-modal').modal('show');
//                    $('#confirm-observe').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
//                } else {
//                    window.location='" . site_url("active_list/redirect_for_doctor") . "/'+rowId+'';
//                }
//            });
//            }";
//            }
//        }
        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $data['department'] = $department;
        $this->render_search($data);
    }

    public function add_observe($pid, $active_list_id)
    {
        $data = array();
        $data['pid'] = $pid;
        $data['default_date_time_visit'] = date("Y-m-d H:i:s");
        $data['default_complaint'] = '';
        $data['default_remarks'] = '';
        $data['default_doctor'] = $this->get_session('name') . ' ' . $this->get_session('other_name');

        $this->form_validation->set_rules('date_time_visit', 'Date Time Of Visit', 'trim|xss_clean|required');
        $this->form_validation->set_rules('complaint', lang('Complaint / Injury'), 'trim|xss_clean|required');
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->render('add_observe', $data);
        } else {
            $insert_data = array(
                'PID' => $pid,
                'DateTimeOfVisit' => $this->input->post('date_time_visit'),
                'Complaint' => $this->input->post('complaint'),
                'Doctor' => $this->get_session('uid'),
                'ActiveListID' => $active_list_id,
                'Remarks' => $this->input->post('remarks'),
                'Status' => 'Observe',
                'Active' => 1
            );
            $pa_id = $this->m_pa_visit->insert($insert_data);
            $update_active_list = array(
                'VisitID' => $pa_id,
                'Status' => 'Observe'
            );
            $this->load->model('m_patient_active_list');
            $this->m_patient_active_list->update($active_list_id, $update_active_list);
            $this->session->set_flashdata(
                'msg', 'REC: ' . ucfirst($pa_id . ' created')
            );
            $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $pa_id);
        }
    }

    public function redirect_for_doctor($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get($active_list_id);
        if (empty($active_list_id))
            die ('Not found');
        switch ($active_list->Department) {
            case 'EMR':
                $this->redirect_if_no_continue('emergency_visit/view/' . $active_list->VisitID);
                break;
            case 'OPD':
                $this->redirect_if_no_continue('opd_visit/view/' . $active_list->VisitID);
                break;
            case 'PA':
                $this->redirect_if_no_continue('patient_pathological_anatomy/view/' . $active_list->VisitID);
                break;
            default:
                die('Wrong department');
        }

    }

    public function is_in_active_list($pid)
    {
        $current_date = date("Y-m-d");
        $sql = 'SELECT * FROM patient_active_list WHERE PID = ? AND Active = 1 AND Status != "Discharged" AND Department = ? AND CreateDate LIKE ?';
        $query = $this->db->query($sql, array($pid, $this->_department, '%' . $current_date . '%'));
//        $result['is_in_active_list'] = $query->num_rows() > 0;
        $result['is_in_active_list'] = FALSE;
        echo json_encode($result);
    }

    public function active_list_click_redirect($id)
    {
        $active_list = $this->m_patient_active_list->get($id);
        if (Modules::run('permission/check_permission', 'emr_active_patient', 'edit') || Modules::run('permission/check_permission', 'opd_active_patient', 'edit')) {
            if ($active_list->Status == 'Pending') {
                $this->redirect_if_no_continue('/active_list/edit/' + $active_list);
            } else {
                $this->redirect_if_no_continue('/patient/view.' + $active_list->PID);
            }
        }
    }

    public function get_dropdown_reasons($type = 'json')
    {
        $this->load->model('m_emergency_reason');
        $result = $this->m_emergency_reason->order_by('HospitalizationReason')->dropdown('PEMRRID', 'HospitalizationReason');
        $result[''] = '';

        if ($type == 'json') {
            // print(json_encode($result));
        }
        return $result;
    }

    public function get_dropdown_doctor()
    {
        $res = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $res[''] = '';
        return $res;
    }

    //added on 14.02.2019 by JCOLOLO
    public function get_dropdown_type()
    {
        $resultado = $this->m_admission_type->order_by('id', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
    }

    private function set_common_validation()
    {
        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
        $this->form_validation->set_rules('entry_service', lang('service'), 'trim|required');
        $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');
    }

    public function view($paid)
    {
        $data = array();
        $this->load->model('mpersistent');
        $this->load->model('mpatient');
        $data["pa_visits_info"] = $this->m_pa_visit->as_array()->get($paid);
        $visit_date = $data["pa_visits_info"]["DateTimeOfVisit"];
        $today = date("Y-m-d H:i:s");
        $diff = abs(strtotime($today) - strtotime($visit_date));
        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));;
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $data["pa_visits_info"]["days"] = $days + $months * 30 + $years * 365;
        if (isset($data["pa_visits_info"]["PID"])) {
            $data["patient_info"] = $this->mpersistent->open_id($data["pa_visits_info"]["PID"], "patient", "PID");
        } else {
            $data["error"] = "OPD Patient  not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (empty($data["patient_info"])) {
            $data["error"] = "OPD Patient not found";
            $this->load->vars($data);
            $this->load->view('opd_error');
            return;
        }
        if (isset($data["patient_info"]["DateOfBirth"])) {
            $data["patient_info"]["Age"] = Modules::run('patient/get_age', $data["patient_info"]["DateOfBirth"]);
        }
        $data["patient_info"]["HIN"] = Modules::run('patient/print_hin', $data["patient_info"]["HIN"]);

        $data["PID"] = $data["pa_visits_info"]["PID"];
        $data["is_discharged"] = $data["pa_visits_info"]["discharge_order"];
        $data["PA_ID"] = $paid;

        $this->render('pa_view', $data);
    }

    public function info($paid)
    {
        $data['pa_visits_info'] = $this->m_pa_visit->with('Doctor')->as_array()->get($paid);
        $this->load->vars($data);
        $this->load->view('pa_info');
    }


}
