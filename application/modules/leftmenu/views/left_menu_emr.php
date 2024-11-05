<?php
/* Discharge */
if (!defined('BASEPATH')) exit('No direct script access allowed');

$menu = "";
$menu .= "<div id='left-sidebar1'>\n";
$menu .= "<div class='list-group'>";

// Commands
$menu .= "<a href='' class='list-group-item active'>" . lang('Commands') . "</a>";
$menu .= "<a href='" . base_url() . "index.php/patient/view/" . $pid . '/' . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;" . lang('Patient overview') . "</a>";

// Check if array or object and set variables accordingly
if (is_array($emr_info)) {
    $refer_to_adm_id = $emr_info['refer_to_adm_id'];
    $discharge_order = $emr_info['discharge_order'];
    $status = $emr_info['Status'];
} else if (is_object($emr_info)) {
    $refer_to_adm_id = $emr_info->refer_to_adm_id;
    $discharge_order = $emr_info->discharge_order;
    $status = $emr_info->Status;
}

// Continue with other checks and permissions
if ($this->config->item('block_opd_after') >= $d_day) {
    if ($refer_to_adm_id == 0 && $discharge_order == 0 && $status == 'Observe') {

        if (Modules::run('permission/check_permission', 'order_lab_test', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_lab_order/create_emr_lab_order/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-tint'></span>&nbsp;" . lang("Order Lab Test") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_radiology_test', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_radiology_order/create_emr_radiology_order/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-flash'></span>&nbsp;" . lang("Order Radiology Test") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'prescribe_drug', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_prescription/prescribe/emr/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Prescribe Drugs") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'prescribe_drug', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_prescription/cardex_prescription/emr/" . $emrid . "/?CONTINUE=emergency_visit' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Cardex") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_treatment', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/treatment_order/create_emr_treatment/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;" . lang("Order Treatment") . "</a>";
        }
      
        

        $menu .= "<a href='" . base_url() . "index.php/patient_note/add_emr_note/" . $pid . "/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;Adicionar Notas de Enfermagem</a>";

        if (Modules::run('permission/check_permission', 'refer_to_admission', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/emergency_visit/refer_to_adm/" . $emrid . "' class='list-group-item'><span class='glyphicon glyphicon-export'></span>&nbsp;" . lang("Transfer Patients") . "</a>";
        }

        if (Modules::run('permission/check_permission', 'refer_to_admission', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_diagnosis/create_emr_diagnosis/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><i class=\"fa fa-file-text-o\" aria-hidden=\"true\"></i></span>&nbsp;" . "Diagnóstico" . "</a>";
        }

        if (Modules::run('permission/check_permission', 'order_discharge', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/order_discharge/create_emr_discharge/" . $emrid . "/?CONTINUE=emergency_visit/view/" . $emrid . "' class='list-group-item'><span class='fa fa-sign-out'></span>&nbsp;" . lang("Order Discharge") . "</a>";
        }
    }
}

$menu .= "</div>";
$menu .= " </div> \n";
echo $menu;
?>
