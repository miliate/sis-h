<?php
/*
--------------------------------------------------------------------------------
HHIMS - Hospital Health Information Management System
Copyright (c) 2011 Information and Communication Technology Agency of Sri Lanka
<http: www.hhims.org/>
----------------------------------------------------------------------------------
This program is free software: you can redistribute it and/or modify it under the
terms of the GNU Affero General Public License as published by the Free Software 
Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,but WITHOUT ANY 
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR 
A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License along 
with this program. If not, see <http://www.gnu.org/licenses/> or write to:
Free Software  HHIMS
C/- Lunar Technologies (PVT) Ltd,
15B Fullerton Estate II,
Gamagoda, Kalutara, Sri Lanka
---------------------------------------------------------------------------------- 
Author: Mr. Thurairajasingam Senthilruban   TSRuban[AT]mdsfoss.org
Consultant: Dr. Denham Pole                 DrPole[AT]gmail.com
URL: http: www.hhims.org
----------------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Patient");
$menu .= "</a>";


//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/emergency_visit/emergency_statistics'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;Pacientes
      Observados</a>";
//}


//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/admission_report'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;".lang ('Admission Report')."</a>";
//}


//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/obsevation_medico_report'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;".lang('Medical Report')."</a>";
//}

// if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/graphyc_medical_report'
     class='list-group-item'><span class='glyphicon glyphicon-stats'></span>&nbsp;".lang('Medical Report Graph')."</a>";
// }


//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/discharge_report'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;".lang('Report by Outcome')."</a>";
//}

//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/diagnosis_report'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;".lang ('Diagnosis Report')."</a>";
//}
//if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/surveillance_report'
     class='list-group-item'><span class='glyphicon glyphicon-list'></span>&nbsp;".lang ('Surveillance Report')."</a>";
//}


// if (Modules::run('permission/check_permission', 'STATISTIC', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/report/graphyc_report'
     class='list-group-item'><span class='glyphicon glyphicon-stats'></span>&nbsp;".lang('Statistics')."</a>";
// }

// registration statistics
$menu .= "<a class='list-group-item' data-toggle=\"modal\" 
data-target=\"#registration-stats\">".lang('Registration Statistics')."</a>";

// encounter statistics
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
    "report/pdf/encounters/view"
) . "\" data-target=\"#encounter-stats\">".lang('Visit Statistics')."</a>";

// encounter statistics
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
    "report/pdf/service/view"
) . "\" data-target=\"#service-stats\">".lang('Service Generation')."</a>";

// inward statistics
$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
        "report/pdf/inwardStatistic/view/"
    ) . "\" data-target=\"#encounter-stats\">".lang('Inward Statistics')."</a>";

//// visit details
//$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//    "report/pdf/visitDetails/view"
//) . "\" data-target=\"#visit-details\">Visit Details</a>";
//
//// visit complaint treated
//$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//    "report/pdf/visitComplaints/view"
//) . "\" data-target=\"#visit-complaints\">Visit Complaint Treated</a>";

$menu .= "</div>";

//$menu .= "<div class='list-group'>";
//$menu .= "<a href='' class='list-group-item active'>";
//if ($this->config->item('purpose') !="PP"){
//	$menu .= "Hospital";
//}else{
//	$menu .= "Private practice";
//}
//$menu .= "</a>";
//// current stock balance
//$menu .= "<a class='list-group-item' onclick=\"openWindow('" . site_url(
//    "report/pdf/pharmacyCurrentStock/print"
//) . "')\" href='#'>Current stock balance</a>";
////create drug order
//$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//    "report/pdf/drugOrder/view"
//) . "\" data-target=\"#order\">Create drug order</a>";
//// daily drugs dispensed
//$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//    "report/pdf/pharmacyBalance/view"
//) . "\" data-target=\"#daily\">Daily drugs dispensed</a>";
//// immr
//if ($this->config->item('purpose') !="PP"){
//	$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//	    "report/pdf/immr/view"
//	) . "\" data-target=\"#immr\">Hospital IMMR</a>";
//
//	// immr
//	$menu .= "<a class='list-group-item' data-toggle=\"modal\" href=\"" . site_url(
//	    "report/pdf/hospitalPerformance/view"
//	) . "\" data-target=\"#performance\">Hospital performance</a>";
//}
//$menu .= "</div>";
//
$menu .= " </div> \n";
echo $menu;
?>