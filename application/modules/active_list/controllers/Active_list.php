<?php

/**
 * Created by PhpStorm.
 * User: manhdx
 * Date: 11/20/15
 * Time: 10:29 AM
 */
class Active_List extends FormController
{
    var $_department;

    function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient');
        $this->load->model('m_doctor');
        $this->load->model('m_patient_costs');
        $this->load->model('m_department');
        $this->load->model('m_patient_active_list');
        $this->load->model('m_patient_active_nopay');
        $this->load->model('m_admission_type');
        $this->load->model('m_emergency_admission');
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
        } else {
            $this->show_no_permission();
        }
    }

    public function get_dropdown_services($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->order_by('name')->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            $i = 0;
            foreach ($result as $item) {
                $drop_down[$i++] = $item->abrev;
            }
            return $drop_down;
        }
    }

    public function get_dropdown_services_name($department_id = 56, $type = 'json')
    {
        $this->load->model('m_hospital_service');
        $result = $this->m_hospital_service->get_many_by(array('department_id' => $department_id));

        if ($type == 'json') {
            print(json_encode($result));
        } else {
            $i = 0;
            foreach ($result as $item) {
                $drop_down[$item->service_id] = $item->name;
            }
            return $drop_down;
        }
    }

    public function create($pid)
    {
        if (!has_permission('active_patient', 'create')) {
            $this->show_no_permission();
        }
        $data['pid'] = $pid;
        if ($this->DEPARTMENT == 'EMR') {
            $data['default_entry_time'] = date("Y-m-d H:i:s");
        } else {
            $data['default_entry_time'] = '';
        }

        $data['default_remarks'] = '';
        $data['default_active'] = '';
        $data['default_department'] = $this->DEPARTMENT;
        $data['default_reason'] = '';
        $data['default_destination'] = 'Consulta';
        $data['default_service'] = '';
        $data['default_service_name'] = '';

        $data['default_patient_costs'] = '';
        $data['default_doctor'] = '';
        $data['default_nopay'] = '';
        $data['default_admission_type'] = '';
        $data['default_severity'] = 1;
        $data['default_exam_type'] = '';
        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_doctor'] = $this->get_dropdown_doctor();
        $data['dropdown_patient_costs'] = $this->get_dropdown_costs();
        $data['dropdown_nopay'] = $this->get_dropdown_nopay();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_severity'] = $this->get_dropdown_severity('result');

        $this->form_validation->set_rules('entry_time', 'Data da Consulta', 'trim|required|callback_check_entry_time');
        $this->form_validation->set_rules('remarks', lang('Remarks'), 'trim');
        $this->form_validation->set_rules('reason', 'Motivo de Hospitalização', 'trim|required');
        $this->form_validation->set_rules('patient_costs', 'Custo da Consulta', 'trim|required');
        $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');
        $this->form_validation->set_rules('status', lang('Status'), 'trim|required');
        $this->form_validation->set_rules('service', lang('Service'), 'trim|required');
        $this->form_validation->set_rules('active', lang('Active'), 'trim|required');
        if ($this->DEPARTMENT == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
            $data['dropdown_service_name'] = $this->get_dropdown_services_name(1, 'return');
        } else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
            $data['dropdown_service_name'] = $this->get_dropdown_services_name(2, 'return');
           // $this->form_validation->set_rules('doctor', 'Medico', 'trim|required');
        }
        if ($this->form_validation->run($this) == FALSE) {
            $this->load_form($data);
        } else {
            $data_insert = array(
                'PID' => $pid,
                'Department' => $this->DEPARTMENT,
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Destination' => $this->input->post('destination'),
                'Service' => $this->input->post('service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'cost' => $this->input->post('patient_costs'),
                'reason_nopay' => $this->input->post('reason_nopay'),
                'admission_type' => $this->input->post('admission_type'),
                'Severity' => $this->input->post('severity'),
                'Remarks' => $this->input->post('remarks'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active'),
                'RegistrationDate' => date("Y-m-d H:i:s")
            );
            if ($consulta = $this->m_patient_active_list->insert($data_insert)) {

                switch ($this->DEPARTMENT) {
                    case 'EMR':
                        $this->redirect_if_no_continue('active_list');
                        break;
                    case 'OPD':
                        $this->redirect_if_no_continue('active_list');
                        break;
                };
            } //End IF

        }
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

    public function edit($active_id)
    {
        if (!has_permission('active_patient', 'edit')) {
            $this->show_no_permission();
        }
        $active_list = $this->m_patient_active_list->get($active_id);
        if (empty($active_list))
            die('not found');

        $data['pid'] = $active_list->PID;
        $data['default_entry_time'] = $active_list->EntryTime;
        $data['default_reason'] = $active_list->HospitalizationReason;
        $data['default_destination'] = $active_list->Destination;
        $data['default_remarks'] = $active_list->Remarks;
        $data['default_active'] = $active_list->Active;
        $data['default_department'] = $active_list->Department;
        $data['default_service'] = $active_list->Service;
        $data['default_service_name'] = $active_list->Service;

        $data['default_patient_costs'] = $active_list->cost;
        $data['default_doctor'] = $active_list->Doctor_ID;
        $data['default_nopay'] = $active_list->reason_nopay;
        $data['default_admission_type'] = $active_list->admission_type;
        $data['default_severity'] = $active_list->Severity;
        $data['default_exam_type'] = $active_list->ExamType;
        $data['dropdown_doctor'] = $this->m_doctor->order_by('Name', 'asc')->dropdown('Doctor_ID', 'Name');
        $data['dropdown_reasons'] = $this->get_dropdown_reasons();
        $data['dropdown_patient_costs'] = $this->get_dropdown_costs();
        $data['dropdown_nopay'] = $this->get_dropdown_nopay();
        $data['dropdown_admission_type'] = $this->get_dropdown_type();
        $data['dropdown_severity'] = $this->get_dropdown_severity('result');

        if ($active_list->Department == 'EMR') {
            $data['dropdown_service'] = $this->get_dropdown_services(1, 'return');
            $data['dropdown_service_name'] = $this->get_dropdown_services_name(1, 'return');
        } else {
            $data['dropdown_service'] = $this->get_dropdown_services(2, 'return');
            $data['dropdown_service_name'] = $this->get_dropdown_services_name(2, 'return');
            $this->form_validation->set_rules('doctor', lang('Doctor'), 'trim|required');
        }

        $this->form_validation->set_rules('remarks', 'Remarks', 'trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|required');
        // $this->form_validation->set_rules('Severity', 'Severity', 'trim|xss_clean|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|xss_clean|required');
        $this->form_validation->set_rules('service', lang('service'), 'trim|required');
        $this->form_validation->set_rules('admission_type', lang('Admission Type'), 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $this->load_form($data);
        } else {
            $data_update = array(
                'EntryTime' => $this->input->post('entry_time'),
                'HospitalizationReason' => $this->input->post('reason'),
                'Remarks' => $this->input->post('remarks'),
                'Service' => $this->input->post('service'),
                'Doctor_ID' => $this->input->post('doctor'),
                'cost' => $this->input->post('patient_costs'),
                'reason_nopay' => $this->input->post('reason_nopay'),
                'Severity' => $this->input->post('severity'),
                'Status' => $this->input->post('status'),
                'Active' => $this->input->post('active')
            );
            $this->m_patient_active_list->update($active_id, $data_update);
            $this->redirect_if_no_continue('active_list');
        }
    }

    public function search($department)
    {
        // Check if services are selected
        $selected_services = $this->input->post('services');
        $service_filter = "";
        if (!empty($selected_services)) {
            $services = implode("','", array_map('addslashes', $selected_services));
            $service_filter = "AND hospital_services.abrev IN ('" . $services . "')";
        }

        if ($this->DEPARTMENT == 'EMR') {
            $dropdown_services = $this->get_dropdown_services(1, 'return');
            $dropdown_services_name = $this->get_dropdown_services_name(1, 'return');
        } else {
            $dropdown_services = $this->get_dropdown_services(2, 'return');
            $dropdown_services_name = $this->get_dropdown_services_name(2, 'return');
        }
        $services = ['All'];

        $option_service = ':All;';


        $option_service = ':All;Null;';


        $dropdown_reason = $this->get_dropdown_nopay();
        $option_reason = ':All;';
        foreach ($dropdown_reason as $reason) {
            if (strlen($reason) > 0) {
                $option_reason .= $reason . ':' . $reason . ';';
            }
        }


        if (!has_permission('active_patient', 'view')) {
            $this->show_no_permission();
            return;
        }
        $qry = "SELECT
        patient_active_list.ACTIVE_ID,
        SUBSTR(patient_active_list.RegistrationDate, 1, 16) as RegistrationDate,
        SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
        patient.PID,
        patient.Firstname, 
        patient.Name as nome,
        TIMESTAMPDIFF(YEAR, COALESCE(NULLIF(patient.DateOfBirth, '0000-00-00'), patient.DateOfBirthReferred), CURDATE()) As DateOfBirth,
        patient_emr_reasons.HospitalizationReason,
        patient_active_list.Destination,
        hospital_services.abrev,
        doctor.Name,
        patient_active_list.cost,
        patient_active_nopay.name,
        patient_active_list.Status
        FROM patient_active_list
        LEFT JOIN patient ON patient_active_list.PID = patient.PID
        LEFT JOIN hospital_services ON patient_active_list.Service = hospital_services.service_id
        LEFT JOIN patient_emr_reasons ON patient_active_list.HospitalizationReason = patient_emr_reasons.PEMRRID
        LEFT JOIN doctor ON patient_active_list.Doctor_ID = doctor.Doctor_ID
        LEFT JOIN patient_active_nopay ON patient_active_list.reason_nopay = patient_active_nopay.id
        WHERE patient_active_list.Active = 1 AND Department = '" . $department . "' " . $service_filter;

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
        $page->setColNames(array(
            "Active ID", lang("Time"), lang("VisitDate"), lang("Patient ID"), lang("Patient"), lang('Surname'), lang("Age"),
            lang("Hospitalization Reason"), lang("Destination"), lang("Service"), lang("Doctor"), lang("cost"), "Motivo de Isenção", lang("Status"),
        ));
        $page->setRowNum(25);
        $page->setColOption("Firstname", array("search" => true));
        $page->setColOption("Name", array("search" => true));
        $page->setColOption("DateOfBirth", array("search" => true));
        $page->setColOption("ACTIVE_ID", array("hidden" => true));
        $page->setColOption("PID", array("search" => true, "width" => 200));
        $page->setColOption("RegistrationDate", $page->getDateSelector());
        if ($this->get_session('user_group_id') != 25) { //this is for emr registrar
            $page->setColOption("EntryTime", $page->getDateSelector(date('Y-m-d')));
        } else {
            $page->setColOption("EntryTime", $page->getDateSelector());
        }



        $page->setColOption('abrev', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => $option_service
            ), 'width' => '120'
        ));

        $page->setColOption('name', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => $option_reason
            ), 'width' => '70'
        ));

        $page->setColOption('Destination', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':Todos;Doenca:Doença;Consulta:Consulta'
            ), 'width' => '120'
        ));


        $page->setColOption('Status', array(
            'stype' => 'select',
            'editoptions' => array(
                'value' => ':All;' . 'Pending' . ':' . lang('Pending') . ';' . 'Triage' . ':' . lang('Triage') . ';' . 'Observe' . ':' . lang('Observe') . ';' . 'Discharge'. ':' . lang('Discharge') . ';' . 'Absent' . ':' . lang('Absent')
            ), 'width' => '70'
        ));
        $page->setAfterInsertRow('function(rowid, data){
        var alertText = \'\';
        for (property in data) {
            alertText +=data[property];
        }
        if (alertText.match(/^.*Pendente/)||alertText.match(/^.*Pending/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ea7d7d\'});
        }
        if (alertText.match(/^.*In Progress/))
        {
            $(\'#\'+rowid).css({\'background\':\'#7deaea\'});
        }
        if (alertText.match(/^.*Triage/)||alertText.match(/^.*Triagem/))
        {
            $(\'#\'+rowid).css({\'background\':\'#ffa457\'});
        }
        if (alertText.match(/^.*Observe/)||alertText.match(/^.*Em Observação/))
        {
            $(\'#\'+rowid).css({\'background\':\'#00d185\'});
        }
        if (alertText.match(/^.*Discharge/)||alertText.match(/^.*Alta/))
        {
            $(\'#\'+rowid).css({\'background\':\'#FFFFFF\'});
        }
        if (alertText.match(/^.*Absent/)||alertText.match(/^.*Ausente/))
        {
            $(\'#\'+rowid).css({\'background\':\'#00FFFF\'});
        }
       }');

        if ($department == 'OPD') {
        } elseif ($department == 'EMR') {
        }
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
        if (Modules::run('permission/check_permission', 'active_patient', 'edit')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(14)').text();
                var rowId = $(this).attr('id');
                window.location='" . site_url("/active_list/edit") . "/'+rowId+'';
            });
            }";
        }
        //        EMR triage doctor group & OPD doctor group
        if (Modules::run('permission/check_permission', 'add_patient_from_active_list', 'create')) {
            $page->gridComplete_JS
                = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var status = $(this).find('td:nth-child(14)').text();
                var rowId = $(this).attr('id');
                if (status == 'Pending'|| status == 'Pendente'|| status == 'Pedir Alta') {
                    var rowId = $(this).attr('id');
                    $('#confirm-modal').modal('show');
                    $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else {
                    var patient_id = $(this).find('td:nth-child(4)').text();
                    window.location='" . site_url("active_list/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
        }

        if (is_observe_doctor()) {
            if (Modules::run('permission/check_permission', 'emr_observe', 'create')) {
                $page->gridComplete_JS
                    = "function() {
            $('#patient_list .jqgrow').mouseover(function(e) {
                var rowId = $(this).attr('id');
                $(this).css({'cursor':'pointer'});
            }).mouseout(function(e){
            }).click(function(e){
                var rowId = $(this).attr('id');
                var status = $(this).find('td:nth-child(14)').text();
                 if (status == 'Pending'|| status == 'Pendente'|| status == 'Absent') {
                    var rowId = $(this).attr('id');
                    $('#confirm-modal').modal('show');
                    $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                    $('#confirm-Absent').attr('href','" . site_url("/active_list/Absent_patient") . "/'+rowId+'');
                } else if (status == 'Triage' || status == 'Triagem') {
                    $('#observe-confirm-modal').modal('show');
                    $('#confirm-observe').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
                } else {
                    window.location='" . site_url("active_list/redirect_for_doctor") . "/'+rowId+'';
                }
            });
            }";
            }
        }


        $page->setOrientation_EL("L");
        $data['pager'] = $page->render(false);
        $data['department'] = $department;
        $data['dropdown_services'] = $this->get_dropdown_services(1, 'return');
        $data['dropdown_services_name'] = $this->get_dropdown_services_name(1, 'return');
        $this->render_search($data);
    }

    public function Absent_patient($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get_last_active_id_by_pid($active_list_id);
        $data = array(

            'Status' => 'Absent',
        );
        $this->m_patient_active_list->update($active_list_id, $data);
        $this->redirect_if_no_continue('active_list');
    }

    public function start_add_patient($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get($active_list_id);
        if (empty($active_list_id))
            die('Not found');
        switch ($active_list->Department) {
            case 'EMR':
                if ($active_list->VisitID == 0) {
                    $data = array(
                        'PID' => $active_list->PID,
                        'Status' => 'Observe',
                        'DateTimeOfVisit' => date('Y-m-d H:i:s'),

                    );
                    $emrid = $this->m_emergency_admission->insert($data);
                    $update_patient_active = array(
                        'VisitID' => $emrid,
                        'Status' => 'Observe'
                    );

                    $this->m_patient_active_list->update($active_list_id, $update_patient_active);
                    $this->redirect_if_no_continue('patient_history/add/' . $active_list->PID . '/' . $active_list->ACTIVE_ID);
                } else {
                    $this->redirect_if_no_continue('emergency_visit/add_observe/' . $active_list->VisitID);
                }
                break;
            case 'OPD':
                $this->redirect_if_no_continue('opd_visit/create/' . $active_list->PID . '/' . $active_list->ACTIVE_ID);
                break;
            default:
                die('Wrong department');
        }
    }

    public function redirect_for_doctor($active_list_id)
    {
        $active_list = $this->m_patient_active_list->get($active_list_id);
        if (empty($active_list_id))
            die('Not found');
        switch ($active_list->Department) {
            case 'EMR':
                $this->redirect_if_no_continue('emergency_visit/view/' . $active_list->VisitID);
                break;
            case 'OPD':
                $this->redirect_if_no_continue('opd_visit/view/' . $active_list->VisitID);
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

    public function get_dropdown_costs()
    {
        $re = $this->m_patient_costs->order_by('custo', 'asc')->dropdown('custo', 'descricao');
        $re[''] = '';
        return $re;
    }

    //added on 14.02.2019 by JCOLOLO
    public function get_dropdown_nopay()
    {
        $resultado = $this->m_patient_active_nopay->order_by('name', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
    }

    //added on 14.02.2019 by JCOLOLO
    public function get_dropdown_type()
    {
        $resultado = $this->m_admission_type->order_by('id', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
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
}
