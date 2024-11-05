<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$menu = "";
$menu .= "<div id='left-sidebar1'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";

$admission_is_discharged = null;

if (is_array($admission)) {
    $admission_is_discharged = $admission["IsDischarged"];
} elseif (is_object($admission)) {
    $admission_is_discharged = $admission->IsDischarged;
}

if ($admission_is_discharged == 1) {
    if (isset($_GET["BACK"])) {
        $menu .= "<a href='" . site_url($_GET["BACK"]) . "' class='list-group-item'><span class='glyphicon glyphicon-circle-arrow-left'></span>&nbsp;Back to ward</a>";
    }
    $menu .= "<a href='" . base_url() . "index.php/patient/view/" . $pid . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;".lang("Patient overview")."</a>";
} else {
    if (isset($_GET["BACK"])) {
        $menu .= "<a href='" . site_url($_GET["BACK"]) . "' class='list-group-item'><span class='glyphicon glyphicon-circle-arrow-left'></span>&nbsp;Back to ward</a>";
    }
    $menu .= "<a href='" . base_url() . "index.php/patient/view/" . $pid . '/' . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-user'></span>&nbsp;".lang("Patient overview")."</a>";

    if (Modules::run('permission/check_permission', 'order_lab_test', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_lab_order/create_adm_lab_order/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-tint'></span>&nbsp;" . lang('Order Lab Test') . "</a>";
    }

    if (Modules::run('permission/check_permission', 'order_radiology_test', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_radiology_order/create_adm_radiology_order/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-flash'></span>&nbsp;" . lang("Order Radiology Test") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'prescribe_drug', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_prescription/prescribe/adm/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Prescribe Drugs") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'dietetic_prescription', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_prescription/dietetic_prescription/adm/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Dietetic Prescription") . "</a>";
    }
     if (Modules::run('permission/check_permission', 'therapeutic_prescription', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_prescription/therapeutic_prescription/adm/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Therapeutic Prescription") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'prescribe_drug_cardex', 'view')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_prescription/cardex_prescription/adm/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-list-alt'></span>&nbsp;" . lang("Cardex") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'order_treatment', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/treatment_order/nursing_care/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;" . lang("Nursing Care") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'order_injection', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/patient_injection/create_adm_injection/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-pushpin'></span>&nbsp;" . lang("Order an Injection") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'ward_transfer', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/admission/ward_transfer/" . $admid . "/" . "?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-transfer'></span>&nbsp;" . lang("Ward Transfer") . "</a>";
    }

    if (Modules::run('permission/check_permission', 'order_discharge', 'create')) {
        $menu .= "<a href='" . base_url() . "index.php/order_discharge/create_adm_discharge/" . $admid . "/?CONTINUE=admission/view/" . $admid . "' class='list-group-item'><span class='glyphicon glyphicon-folder-close'></span>&nbsp;" . lang("Order Discharge") . "</a>";
    }
}

$menu .= "</div>";
$menu .= "</div>";
echo $menu;
?>
