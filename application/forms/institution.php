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
$form["TABLE"] = "institution";
$form["FORM_CAPTION"] = "Lista de Instituicoes";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/institution";
//pager starts
$form["CAPTION"] = "Lista de Institui&ccedil;&otilde;es <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('institution/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/institution/edit/";
$form["ROW_ID"] = "id";
$form["COLUMN_MODEL"] = array('Name', 'Type' => array("width" => "35px"),'Email'=>  array("width" => "50px"),'Telephone','Address_Village','Remarks','Active');
$form["ORIENT"] = "L";
$form["LIST"] = array('INSTID', 'Name','Abrev', 'Type', 'Email', 'Telephone', 'Address_Village','Remarks', 'Active');
$form["DISPLAY_LIST"] = array('Id', lang('Name'), lang('Abrev'),lang('Type'), 'E mail', lang('Telephone'), lang('Village'),lang('Remarks'), lang('Active'));
//pager ends
$form["FLD"] = array(
    array(
        "id" => "Name",
        "name" => "Name",
        "label" => "*Nome da Instituicao",
        "type" => "text",
        "width" => "15px",
        "value" => '',
        "option" => "",
        "placeholder" => "Nome da Instituicao",
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