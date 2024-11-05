<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= "Arquivo Clínico"; 	
$menu .= "</a>";
if (Modules::run('permission/check_permission', 'clinical_storage', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/arquivo_clinico' class='list-group-item'> <i class='fa fa-file'></i> Processos Clinicos</a>";
}
if (Modules::run('permission/check_permission', 'confirm_discharge', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/order_discharge' class='list-group-item'>".lang('Discharge Order'). "</a>";
}
if (has_permission('opd_observer', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/opd_visit/my_observe_patient' class='list-group-item'>".lang('My Observed Patient'). "</a>";
}

$menu .= "</div>";
$menu .= " </div> \n";
echo $menu;
?>
