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
$form["OBJID"] = "WID";
$form["TABLE"] = "ward";
$form["FORM_CAPTION"] = "Ward";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/ward";	
//pager starts
$form["CAPTION"]  = lang('Ward')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('ward/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/ward/edit/";
$form["ROW_ID"]="WID";
$form["COLUMN_MODEL"] = array( 'WID','Name', 'BedCount', 'Telephone');
$form["ORIENT"] = "L";
$form["LIST"] = array( 'WID','Name', 'BedCount', 'Telephone');
$form["DISPLAY_LIST"] = array( 'WID',lang('Name'), lang('BedCount'), lang('Telephone'));
//pager ends	
$form["FLD"]=array(
array(		
		"id"=>"Name", 
		"name"=>"Name",
		"label"=>"*Ward Name",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"complaints",
		"rules"=>"trim|required|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(		
		"id"=>"Type", 
		"name"=>"Type",
		"label"=>"Type of the ward",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Type of the ward",
		"rules"=>"trim|xss_clean",
		"style"=>"",
		"class"=>"input"
	),	
array(		
		"id"=>"Telephone", 
		"name"=>"Telephone",
		"label"=>"Telephone",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Telephone",
		"rules"=>"trim|xss_clean",
		"style"=>"",
		"class"=>"input"
	),	
array(		
		"id"=>"BedCount", 
		"name"=>"BedCount",
		"label"=>"*Number of beds",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Number of beds",
		"rules"=>"trim|required|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(		
		"id"=>"Remarks", 
		"name"=>"Remarks",
		"label"=>"Remarks",
		"type"=>"remarks",
		"value"=>"",
		"option"=>"",
		"placeholder"=>"Any remarks",
		"rules"=>"xss_clean",
		"class"=>"input",
		"style"=>"",
		"rows"=>"2",
		"cols"=>"300"
	),
array(		
		"id"=>"Active", 
		"name"=>"Active",
		"label"=>"Active",
		"type"=>"boolean",
		"value"=>"",
		"option"=>"",
		"placeholder"=>"",
		"rules"=>"required",
		"style"=>"",
		"class"=>"input"
	)	
	);

$patient["JS"] = "
<script>
function ForceSave(){
}
</script>
";  									
////////Configuration for patient form END;                   
?>