<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";

$menu .= lang("Prints");
$menu .= "</a>";
// Print patient slip
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSlip/print/$id"
//    ) . "')\" href='#'>Print patient slip</a>";

if (Modules::run('permission/check_permission', 'active_patient', 'create')){

    $_department='';
  $this->_department = $this->session->userdata('department');

if ($department == 'EMR') {
$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
        "report/pdf/patientBoletimSUP/print/$pid"
    ) . "')\" href='#'><span class='glyphicon glyphicon-baby'></span>&nbsp;  Boletim SUP</a>";

  }

  if (Modules::run('permission/check_permission', 'active_patient', 'edit')){

  $menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
    "report/pdf/patientBoletimSUP/print/$pid"
) . "')\" href='#'> <span class='glyphicon glyphicon-print'></span>&nbsp;".lang('pediatric emergency bulletin')."</a>";

  }
  // Print patient card
$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
    "report/pdf/patientCard/print/$pid"
) . "')\" href='#'> <span class='glyphicon glyphicon-barcode'></span>&nbsp;". lang('Print patient card') . "</a>";

// Print patient summery
/*$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
    "report/pdf/patientSummery/print/$pid"
) . "')\" href='#'>".lang('Print patient summary')."</a>";*/

}

if (Modules::run('permission/check_permission', 'special_clinic', 'edit')) {
    $menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
        "report/pdf/patientBoletimSap/print/$pid"
    ) . "')\" href='#'><span class='glyphicon glyphicon-print'></span>&nbsp; ".lang('consultation process')."</a>";
}







//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "patient/notes/$id"
//    ) . "')\" href='#'>Print nursing notes</a>";
$menu .= "</div>";
echo $menu;
?>
