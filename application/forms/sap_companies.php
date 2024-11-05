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


////////Configuration for company form added on 18.08.2021 by Jcololo
$form = array();
$form["OBJID"] = "id";
$form["TABLE"] = "sap_companies";
$form["FORM_CAPTION"] = "Lista de Instituicoes";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/sap_companies";
//pager starts
$form["CAPTION"] = lang('Companies')."  <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('sap_companies/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/sap_companies/edit/";
$form["ROW_ID"] = "id";
$form["COLUMN_MODEL"] = array('id' => array("width" => "15px"),'Name' => array("width" => "105px"), 'address' => array("width" => "35px"), 'phone_number', 'mobile_number'=>  array("width" => "35px"),'registration_number','Remarks','Active');
$form["ORIENT"] = "L";
$form["LIST"] = array('id','Name', 'address','phone_number','mobile_number','registration_number','Remarks','Active');
$form["DISPLAY_LIST"] = array('ID','Nome da Instituicao', lang('Address'), lang('Telephone'),'Cel.',lang('NUIT'),lang('Remarks'),lang('Status'));
//pager ends
$form["FLD"] = array(
    array(
        "id" => "name",
        "name" => "name",
        "label" => "*Nome da Instituicao",
        "type" => "text",
        "width" => "15px",
        "value" => '',
        "option" => "",
        "placeholder" => "Nome da Empresa",
        "rules" => "trim|required|xss_clean",
        "style" => "",
        "class" => "input"
    ),
   

);

$patient["JS"] = "
<script>
function ForceSave(){
}
</script>
";
////////Configuration for patient form END;                   
?>