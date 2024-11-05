<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= "Departments";
$menu .= "</a>";
$menu .= "<a href='" . base_url() . "index.php/pharmacy/show_list/OPD' class='list-group-item'>OPD Pharmacy</a>";
if ($this->config->item('purpose') !="PP"){
	$menu .= "<a href='" . base_url() . "index.php/pharmacy/show_list/CLN' class='list-group-item'>Clinic Pharmacy</a>";
}
//$menu .= "<a href='" . base_url() . "index.php/pharmacy' class='list-group-item'>InPatient</a>";
$menu .= "</div>";

$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= "Reports";
$menu .= "</a>";

// daily drugs dispensed
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
        "report/pdf/pharmacyBalance/view"
    ) . "\" data-target=\"#daily\">Daily drugs dispensed</a>";

// current stock balance
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
        "report/pdf/pharmacyCurrentStock/view"
    ) . "\" data-target=\"#current-stock\">Current stock balance</a>";
/**
$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
        "report/pdf/pharmacyCurrentStock/print"
    ) . "')\" href='#'>Current stock balance</a>";
 **/

//create drug order
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
        "report/pdf/drugOrder/view"
    ) . "\" data-target=\"#order\">Create drug order</a>";

// prescriptions
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
    "report/pdf/prescriptions/view"
) . "\" data-target=\"#prescription\">Prescriptions</a>";

// prescription by drug
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
        "report/pdf/prescriptionsByDrug/view"
    ) . "\" data-target=\"#prescription-by-drug\">Prescription by Drug</a>";

$menu .= "</div>";

$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= "Maintain";
$menu .= "</a>";
$menu .= "<a href='" . base_url() . "index.php/preference/load/who_drug' class='list-group-item'>Drugs</a>";
$menu .= "</div>";

$menu .= " </div> \n";

echo $menu
?>

