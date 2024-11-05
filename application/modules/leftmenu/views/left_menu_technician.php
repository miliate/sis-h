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
$menu .= "<a href='" . base_url() . "index.php/patient_lab_order/search/' class='list-group-item'><span class='fa fa-eyedropper'></span>&nbsp;" . lang("Lab Tests") . "</a>";
$menu .= "<a href='" . base_url() . "index.php/pathological_anatomy_order/search/' class='list-group-item'><span class='fa fa-stethoscope'></span>&nbsp;" . lang("Pathological Anatomy Tests") . "</a>";
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