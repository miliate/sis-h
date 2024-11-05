<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= "Sector Financeiro";
$menu .= "</a>";

if (Modules::run('permission/check_permission', 'special_clinic', 'edit')) {
   // $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_procedures' class='list-group-item'> SAP Procedures". lang('SAP Procedures'). "</a>";
   $menu .= "<a href='" . base_url() . "index.php/patient_hospital_clinic/bill/".$active_list."' class='list-group-item'> <i class='fa fa-money'></i>  Factura&ccedil;&atilde;o". lang('SAP Procedures'). "</a>";
    $menu .= "<a onclick='openWindow(\"" . base_url() . "index.php/report/pdf/clinicBill/print/". $active_list ."\")' class='list-group-item'><i class='fa fa-print'></i>   Factura ao Cliente</a>";
    $menu .= "<a onclick='openWindow(\"" . base_url() . "index.php/patient_hospital_clinic/patientBill/".$bill_id."\")' class='list-group-item'> <i class='fa fa-print'></i>   Factura ao M&eacute;dico</a>";
}
$menu .= "</div>";


$menu .= " </div> \n";
echo $menu;
?>