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
$form["OBJID"] = "radiology_id";
$form["TABLE"] = "radiology";
$form["FORM_CAPTION"] = "Lab Tests";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/lab_radiology";
//pager starts
$form["CAPTION"]  = "Teste de radiologia <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('lab_radiology/create_lab_test')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/lab_radiology/edit_lab_test/";
$form["ROW_ID"]="radiology_id";
$form["COLUMN_MODEL"] = array( 'radiology_id'=>array("width"=>"35px"), 'parent_group', 'name', 'Remarks', 'RefValue');
$form["ORIENT"] = "L";
$form["LIST"] = array( 'radiology_id', 'parent_group', 'name', 'Remarks', 'RefValue');
$form["DISPLAY_LIST"] = array( 'ID', lang('parent_group'), lang('Name'), lang('Remarks'), lang('RefValue'));
//pager ends	
                  
?>