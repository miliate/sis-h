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


////////Configuration for patient form
$form = array();
$form["OBJID"] = "Id";
$form["TABLE"] = "radiology_group";
$form["FORM_CAPTION"] = "Radiology Group";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/lab_radiology_group";	
//pager starts
$form["CAPTION"]  = lang('radiology group')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('lab_radiology/create_lab_test_group')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/lab_radiology/edit_lab_test_group/";
$form["ROW_ID"]="Id";
$form["COLUMN_MODEL"] = array( 'Id'=>array("width"=>"35px"), 'Group_Name', 'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
$form["ORIENT"] = "L";
$form["LIST"] = array( 'Id', 'Group_Name', 'Active');
$form["DISPLAY_LIST"] = array( lang('LABGRPTID'), lang('Name'), lang('Active'));
//pager ends	
                  
?>