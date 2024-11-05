<?php

/**
 * Created by @jordao.cololo.
 * User: jcololo
 * Date: 12-Oct-15
 * Time: 9:10 PM
 */
class Patient_tracker extends FormController
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('m_patient_tracker');
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


    public function search($department)
    {


        if ($this->DEPARTMENT == 'EMR') {
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

        $option_hospitalization = ':'.lang('All').';';
        $dropdown_hospitalization = $this->get_dropdown_reasons('return');
        foreach ($dropdown_hospitalization as $hospitalization) {
            if (strlen($hospitalization) > 0) {
                $option_hospitalization .= $hospitalization . ':' . $hospitalization . ';';
            }
        }
     //   $repiratory_options = ':All';
        $repiratory_options = array(
            '0' => 'Doente Sem Sintomas Respiratórios',
            '1' => 'Doente Com Febre',
            '2'=>'Doente Com Tosse',
            '3' => 'Doente Com Febre e Tosse',
            '4' => 'Doente Com Dor de Cabeça',
            '5' => 'Doente Com Dor de Garganta',
        );


        

        $dropdown_reason = $this->get_dropdown_nopay();
        $option_reason = ':'.lang('All').';';
        foreach ($dropdown_reason as $reason) {
            if (strlen($reason) > 0) {
                $option_reason .= $reason . ':' . $reason . ';';
            }
        }


        if (!has_permission('active_patient', 'view')) {
            $this->show_no_permission();
        }

          $qry = "SELECT
                patient_active_list.ACTIVE_ID,
                SUBSTR(patient_active_list.RegistrationDate, 1, 16) as RegistrationDate,
                SUBSTR(patient_active_list.EntryTime, 1, 10) as EntryTime,
                patient.PID,
             /*   CONCAT(patient.Firstname,' ',patient.Name) AS Patient,*/
                patient_emr_reasons.HospitalizationReason,
                hospital_services.abrev,
               CONCAT( round(patient_tracker.temperature, 1), '°C') as Temp,
               patient_tracker.respiratory_chart,
               patient_tracker.covid19_case,
                patient_active_list.Status
                FROM patient_active_list
                LEFT JOIN patient ON patient.PID = patient_active_list.PID
                LEFT JOIN patient_emr_reasons On patient_emr_reasons.PEMRRID = patient_active_list.HospitalizationReason
                LEFT JOIN hospital_services ON hospital_services.service_id = patient_active_list.Service
                LEFT JOIN patient_tracker ON patient_tracker.consulta_id = patient_active_list.ACTIVE_ID
                WHERE patient_active_list.Active = 1 AND patient_tracker.temperature>0  AND Department = '" . $department . "'
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
$page->setColNames(array("Active ID", lang("Time"), lang("VisitDate"), lang("Patient ID"), 
lang('Hospitalization Reason'), lang("Service"),"Temp", lang("Respiratory Chart"), lang("Suspected Case"), lang("Status")));
$page->setRowNum(25);
$page->setColOption("ACTIVE_ID", array("hidden" => true));
$page->setColOption("PID", array("search" => true, "width" => 50));
$page->setColOption("Temp", array("search" => true, "width" => 50));
$page->setColOption("respiratory_chart", array("search" => true, "width" => 50));
$page->setColOption("RegistrationDate",array( $page->getDateSelector(), "width" => 100));
if ($this->get_session('user_group_id') != 25) {//this is for emr registrar
    $page->setColOption("EntryTime", $page->getDateSelector(date('Y-m-d')), array("search" => true, "width" => 100) );
} else {
    $page->setColOption("EntryTime", $page->getDateSelector(), array("search" => true, "width" => 100));
}

$page->setColOption('abrev', array('stype' => 'select',
    'editoptions' => array(
        'value' => $option_service
    ), 'width' => '75'));

    $page->setColOption('covid19_case', array('stype' => 'select',
    'editoptions' => array(
        'value' => ':'.lang('All').';1:SIM;0:NAO'
    ), 'width' => '70'));

    $page->setColOption('respiratory_chart', array('stype' => 'select',
    'editoptions' => array(
        'value' => $repiratory_options
    ), 'width' => '70'));

$page->setColOption('HospitalizationReason', array('stype' => 'select',
    'editoptions' => array(
        'value' => $option_hospitalization
    ), 'width' => '120'));


$page->setColOption('Status', array('stype' => 'select',
    'editoptions' => array(
        'value' => ':'.lang('All').';'.lang('Pending').':'.lang('Pending').';'.lang('Triage').':'.lang('Triage').';'.lang('Observe').':'.lang('Observe')
    ), 'width' => '70'));

$page->setAfterInsertRow('function(rowid, data){
var alertText = \'\';
for (property in data) {
    alertText +=data[property];
}
if (alertText.match(/^.*Pending/)||alertText.match(/^.*Pendente/))
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
if (alertText.match(/^.*Observe/)||alertText.match(/^.*Em observacao/))
{
    $(\'#\'+rowid).css({\'background\':\'#00d185\'});
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
        var status = $(this).find('td:nth-child(12)').text();
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
        var status = $(this).find('td:nth-child(12)').text();
        var rowId = $(this).attr('id');
        if (status == 'Pending'|| status == 'Pendente') {
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
        var status = $(this).find('td:nth-child(12)').text();
        if (status == 'Pending'|| status == 'Pendente') {
            var rowId = $(this).attr('id');
            $('#confirm-modal').modal('show');
            $('#confirm-create').attr('href','" . site_url("/active_list/start_add_patient") . "/'+rowId+'');
        } else if (status == 'Triage') {
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
$this->render_search($data);






     //  echo $department." Chegou e Obrigado";


    } //Finish Search

    public function get_dropdown_reasons($type = 'json')
    {
        $this->load->model('m_emergency_reason');
        $result = $this->m_emergency_reason->order_by('PEMRRID')->dropdown('PEMRRID', 'HospitalizationReason');
        $result[''] = '';

        if ($type == 'json') {
            // print(json_encode($result));
        }
        return $result;
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

    //added on 15.06.2020 by JCOLOLO
    public function get_dropdown_nopay()
    {
        $this->load->model('m_patient_active_nopay');
        $resultado = $this->m_patient_active_nopay->order_by('name', 'asc')->dropdown('id', 'name');
        $resultado[''] = '';
        return $resultado;
    }




}