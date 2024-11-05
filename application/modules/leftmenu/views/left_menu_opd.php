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
$menu .= "<a href='" . base_url() . "index.php/patient/view/" . $pid . '/' . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;" . lang("Patient overview") . "</a>";
//var_dump($this->config->item('block_opd_after'));
//var_dump($d_day);$data['is_discharged'] = $is_discharged;
if ($this->config->item('block_opd_after') >= $d_day) {
//    $menu .= "<a href='" . base_url() . "index.php/patient_history/add/" . $pid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-header'></span>&nbsp;Add History</a>";
//    $menu .= "<a href='" . base_url() . "index.php/patient_allergy/add/" . $pid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-bell'></span>&nbsp;Add Allergy</a>";
//    $menu .= "<a href='" . base_url() . "index.php/patient_examination/add/" . $pid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-check'></span>&nbsp;Add Examination</a>";
    if ($opd_info['refer_to_adm_id'] == 0 && ($is_discharged == 0 || $is_discharged == NULL)) {
        if (Modules::run('permission/check_permission', 'order_lab_test', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_lab_order/create_opd_lab_order/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-tint'></span>&nbsp;" . lang("Order Lab Test") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_radiology_test', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_radiology_order/create_opd_radiology_order/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-flash'></span>&nbsp;" . lang("Order Radiology Test") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'prescribe_drug', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_prescription/prescribe/opd/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Prescribe Drugs") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_treatment', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/treatment_order/create_opd_treatment/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;" . lang("Order Treatment") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_injection', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/patient_injection/create_opd_injection/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-pushpin'></span>&nbsp;" . lang("Order an Injection") . "</a>";
        }
//$menu .= "<a href='" . base_url() . "index.php/patient_note/add_opd_note/" . $pid . "/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='glyphicon glyphicon-leaf'></span>&nbsp;Add OPD nursing notes</a>";
        if (Modules::run('permission/check_permission', 'refer_to_admission', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/opd_visit/refer_to_adm/" . $opdid . "' class='list-group-item '><span class='glyphicon glyphicon-export'></span>&nbsp;" . lang("Refer to admission") . "</a>";
        }
        if (Modules::run('permission/check_permission', 'order_discharge', 'create')) {
            $menu .= "<a href='" . base_url() . "index.php/order_discharge/create_opd_discharge/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><span class='fa fa-sign-out'></span>&nbsp;" . lang("Order Discharge") . "</a>";
        }
    }
}
$menu .= "</div>";


$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Record");
$menu .= "</a>";
//var_dump($d_day);
if ($this->config->item('block_opd_after') >= $d_day) {
    $menu .= "<a href='" . base_url() . "index.php/patient_diagnosis/create_opd_diagnosis/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><i class=\"fa fa-file-text-o\" aria-hidden=\"true\"></i></span>&nbsp;" . "Diagnóstico" . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_soap/create_opd_soap/" . $opdid . "/?CONTINUE=opd_visit/view/" . $opdid . "' class='list-group-item'><i class=\"fa fa-file-text\" aria-hidden=\"true\"></i></span>&nbsp;" . "SOAP" . "</a>";
}
$menu .= "</div>";


//$menu .= "<div class='list-group'>";
//$menu .= "<a href='' class='list-group-item active'>";
//$menu .= "Prints";
//$menu .= "</a>";
//// Print patient slip
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSlip/print/$pid"
//    ) . "')\" href='#'>Print patient slip</a>";
//
//// Print patient card
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientCard/print/$pid"
//    ) . "')\" href='#'>Print patient card</a>";
//
//// Print patient summery
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSummery/print/$pid"
//    ) . "')\" href='#'>Print patient summary</a>";
//
//// Print visit summery
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/patientSummery/print/$pid"
//    ) . "')\" href='#'>Print visit summary</a>";
//
//// Print OPD Prescription
////$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
////    "report/pdf/opdPrescription/print/$opdid"
////) . "')\" href='#'>Prescription</a>";
//
//// Print OPD Labtests
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/opdLabtests/print/$opdid"
//    ) . "')\" href='#'>Lab test</a>";
//
//// Print clinic book
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//        "report/pdf/clinicBook/print/$opdid"
//    ) . "')\" href='#'>Print Clinic Book</a>";
//
//$menu .= "</div>";

//$menu .= "<div class='list-group'>";
//$menu .= "<a href='' class='list-group-item active'>";
//$menu .= "Generic Modules";
//$menu .= "</a>";
//$menu .= "</div>";

$menu .= " </div> \n";
echo $menu;
?>