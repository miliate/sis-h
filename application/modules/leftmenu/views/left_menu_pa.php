<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();
$menu = "";
$menu .= "<div id='left-sidebar1'>\n";

$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";
$menu .= "<a href='" . base_url() . "index.php/patient/view/" . $pid . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;" . lang("Patient overview") . "</a>";
//var_dump($this->config->item('block_opd_after'));
//var_dump($d_day);$data['is_discharged'] = $is_discharged;
//if ($pa_info['refer_to_adm_id'] == 0 && ($is_discharged == 0 || $is_discharged == NULL)) {
if (Modules::run('permission/check_permission', 'order_pa', 'create')) {
    $menu .= "<a href='" . base_url() . "index.php/pathological_anatomy_order/create/" . $pid . "/" . $paid . "/?CONTINUE=patient_pathological_anatomy/view/" . $paid . "' class='list-group-item'><span class='glyphicon glyphicon-tint'></span>&nbsp;" . lang("Order Pathological Anatomy Test") . "</a>";
}

$menu .= "</div>";
$menu .= "</div>";

//$menu .= "<div class='list-group'>";
//$menu .= "<a href='' class='list-group-item active'>";
//$menu .= lang("Record");
//$menu .= "</a>";
//$menu .= "<a href='" . base_url() . "index.php/patient_diagnosis/create_opd_diagnosis/" . $paid . "/?CONTINUE=opd_visit/view/" . $paid . "' class='list-group-item'><i class=\"fa fa-file-text-o\" aria-hidden=\"true\"></i></span>&nbsp;" . "Diagnóstico" . "</a>";
//$menu .= "<a href='" . base_url() . "index.php/patient_soap/create_opd_soap/" . $paid . "/?CONTINUE=opd_visit/view/" . $paid . "' class='list-group-item'><i class=\"fa fa-file-text\" aria-hidden=\"true\"></i></span>&nbsp;" . "SOAP" . "</a>";
//$menu .= "</div>";
//$menu .= " </div> \n";

echo $menu;

?>