<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands"); 	
$menu .= "</a>";
if (Modules::run('permission/check_permission', 'active_patient', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/active_list' class='list-group-item'>  <i class='fa fa-street-view' style='font-size:24px;'></i> ".lang('Active Patient'). "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_tracker' class='list-group-item'> <i class='fa fa-check-square-o' style='font-size:24px;'></i>   Rastreio de Covid-19</a>";
}
if (Modules::run('permission/check_permission', 'confirm_discharge', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/order_discharge' class='list-group-item'><i class='fa fa-wheelchair' style='font-size:24px;'></i> ".lang('Discharge Order'). "</a>"; 
    
}
if (Modules::run('permission/check_permission', 'pathological_anatomy', 'view')){
    $menu .= "<a href='" . base_url() . "index.php/patient_pathological_anatomy' class='list-group-item'> 
     <i class='fa fa-stethoscope' style='font-size:24px;'></i> ".lang('Pathological Anatomy Patient'). "</a>";
}
if (has_permission('opd_observer', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/opd_visit/my_observe_patient' class='list-group-item'>".lang('My Observed Patient'). "</a>";
}

if (Modules::run('permission/check_permission', 'emr_observe', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/emergency_visit/my_observed_patient' class='list-group-item'>".lang('My Observed Patient'). "</a>";
}

$menu .= "</div>";
$menu .= " </div> \n";
echo $menu;
?>
