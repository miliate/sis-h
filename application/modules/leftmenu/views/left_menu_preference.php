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
__________________________________________________________________________________
Private Practice configuration :

Date : July 2015		ICT Agency of Sri Lanka (www.icta.lk), Colombo
Author : Laura Lucas
Programme Manager: Shriyananda Rathnayake
Supervisors : Jayanath Liyanage, Erandi Hettiarachchi
URL: http://www.govforge.icta.lk/gf/project/hhims/ 
----------------------------------------------------------------------------------
*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";

$menu .= "<div id='left-sidebar1'>\n";
if (Modules::run('permission/check_permission', 'left_menu_system', 'view')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'>" . lang('System Tables') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/user' class='list-group-item'>  <i class='fa fa-user'></i> " . lang('Add/Edit Users') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/user_group' class='list-group-item'> <i class='fa fa-users'></i> " . lang('Add/Edit Group') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/user_menu' class='list-group-item'>  <i class='fa fa-sitemap'></i> " . lang('Add/Edit Menu') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/permission' class='list-group-item'>  <i class='fa fa-lock'></i> " . lang('Edit Permission') . "</a>";
    $menu .= "<a href='"  . base_url() . "index.php/preference/load/institution' class='list-group-item'>  <i class='fa fa-university'></i>   Add/Edit Institui&ccedil;&atilde;o </a>";
    //    $menu .= "<a href='" . base_url() . "index.php/preference/load/clinic' class='list-group-item'>Add/Edit Clinic</a>";
    //    $menu .= "<a href='" . base_url() . "index.php/preference/load/hospital' class='list-group-item'>Hospital Settings</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/doctor' class='list-group-item'> <i class='fa fa-user-md'></i>  M&eacute;dicos</a>";
    $menu .= "</div>";
}

if (Modules::run('permission/check_permission', 'left_menu_system', 'view')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'>" . lang('Clinical Tables');
    $menu .= "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/complaints' class='list-group-item'>" . lang('Complaints') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/treatment' class='list-group-item'>" . lang('Treatments') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/injection' class='list-group-item'>" . lang('Injection') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/who_drug' class='list-group-item'>" . lang('Drugs') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/drugs_dosage' class='list-group-item'>" . lang('Drugs dosage') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/drugs_frequency' class='list-group-item'>" . lang('Drugs frequency') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/drug_route_administration' class='list-group-item'>" . lang('drug route administration') . "</a>";
    // $menu .="<a href='".base_url()."index.php/preference/load/drugs_period' class='list-group-item'>".lang('Drugs period')."</a>";
    //$menu .="<a href='".base_url()."index.php/preference/load/drug_stock' class='list-group-item'>".lang('Drug Stock')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/drug_stock/view' class='list-group-item'>" . lang('Stock management') . "</a>";
    //    $menu .= "<a href='" . base_url() . "index.php/preference/load/canned_text' class='list-group-item'>".lang('Canned text')."</a>";

    $menu .= "<a href='" . base_url() . "index.php/preference/load/lab_tests' class='list-group-item'>".lang('Lab tests')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/lab_radiology' class='list-group-item'>".lang('Lab Radiology')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/lab_radiology_group' class='list-group-item'>".lang('Radiology groups')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/lab_test_group' class='list-group-item'>".lang('Lab test groups')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/lab_test_department' class='list-group-item'>".lang('Lab departments')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/ward' class='list-group-item'>".lang('Wards')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/ward_beds' class='list-group-item'>" . lang('Ward Beds') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/ward_rooms' class='list-group-item'>".lang('Rooms')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/icd10' class='list-group-item'>".lang('ICD10')."</a>";

    //$menu .="<a href='".base_url()."index.php/module' class='list-group-item alert'>.lang('Generic Module')."</a>";
    $menu .= "</div>";
}

if (Modules::run('permission/check_permission', 'drug_management', 'view')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'>" . lang('Stock Management') . "";
    $menu .= "</a>";
    $menu .= "<a href='" . base_url() . "index.php/user_favour_drug' class='list-group-item'>" . lang('My Medication List') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/who_drug' class='list-group-item'>" . lang('Medicine') . "</a>";
    // $menu .= "<a href='" . base_url() . "index.php/preference/load/drugs_dosage' class='list-group-item'>".lang('Dosage')."</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/drugs_frequency' class='list-group-item'>" . lang('Frequency') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_external_prescription/dispense' class='list-group-item'>" . lang('external prescription') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/drug_stock/request' class='list-group-item'>" . lang('Drug Request') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/drug_stock/view' class='list-group-item'>" . lang('Stock Management') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/patient_prescription/cardex_prescription_quantitiy' class='list-group-item'>" . lang('Quantity Of Drugs Prescribed In Rooms') . "</a>";
    $menu .= "</div>";
}



if (Modules::run('permission/check_permission', 'left_menu_system', 'view')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'>" . lang('SAP Management') . "";
    $menu .= "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_procedures' class='list-group-item'>  <i class='fa fa-money'></i>" . lang('Procedures') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_companies' class='list-group-item'> <i class='fa fa-briefcase'></i> " . lang('Companies') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_companies' class='list-group-item'> <i class='fa fa-calendar'></i>" . lang('Invoicing') . "</a>";
    $menu .= "</div>";
}

if (Modules::run('permission/check_permission', 'left_menu_system', 'view')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'> <i class='fa fa-book'></i> " . lang('Manuals') . "";
    $menu .= "</a>";
    $menu .= "<a href='" . base_url() . "/manual#'  target='_blank' class='list-group-item'> <i class='fa fa-briefcase'></i>" . lang('Forms') . "</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference' target='_blank' class='list-group-item'>  <i class='fa fa-money'></i>  " . lang('Decrees') . "</a>";
    $menu .= "</div>";
}



if (Modules::run('security/check_leftmenu_access', 'preference', 'application_table')) {
    $menu .= "<div class='list-group'>";
    $menu .= "<a href='' class='list-group-item active'>";
    $menu .= "Application Tables";
    $menu .= "</a>";
    if ($this->config->item('purpose') != "PP") {
        $menu .= "<a href='" . base_url() . "index.php/preference/load/finding' class='list-group-item'>SNOMED Findings</a>";
        $menu .= "<a href='" . base_url() . "index.php/preference/load/disorder' class='list-group-item'>SNOMED Disorders</a>";
        $menu .= "<a href='" . base_url() . "index.php/preference/load/event' class='list-group-item'>SNOMED Events</a>";
        $menu .= "<a href='" . base_url() . "index.php/preference/load/procedures' class='list-group-item'>SNOMED Procedures</a>";
        $menu .= "<a href='" . base_url() . "index.php/preference/load/immr' class='list-group-item'>IMMR</a>";
    }
    $menu .= "<a href='" . base_url() . "index.php/preference/load/village' class='list-group-item'>Village</a>";
    $menu .= "</div>";
}


//if (Modules::run('security/check_leftmenu_access', 'preference', 'qmodule')) {
//    if ($this->config->item('purpose') != "PP") {
//        $menu .= "<div class='list-group'>";
//        $menu .= "<a href='' class='list-group-item active'>";
//        $menu .= "Q Module";
//        $menu .= "</a>";
//
//        $menu .= "<a href='" . base_url() . "index.php/question' class='list-group-item'>Question Repository</a>";
//        $menu .= "<a href='" . base_url() . "index.php/questionnaire' class='list-group-item'>Questionnaires</a>";
//        $menu .= "<a href='" . base_url() . "index.php/diagram' class='list-group-item'>Clinical Diagrams</a>";
//
//        //$menu .="<a href='".base_url()."index.php/module' class='list-group-item alert'>Generic Modules</a>";
//        $menu .= "</div>";
//    }
//}

$menu .= " </div> \n";
echo $menu;
