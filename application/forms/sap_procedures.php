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
$form["OBJID"] = "id";
$form["TABLE"] = "sap_procedures";
$form["FORM_CAPTION"] = "Lista de Procedimentos e Preçarios";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/sap_procedures";
//pager starts
$form["CAPTION"] = "Preçario <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('sap_procedures/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/sap_procedures/edit/";
$form["ROW_ID"] = "id";
$form["COLUMN_MODEL"] = array('id' => array("width" => "35px"), 'Name', 'type_id' => array("width" => "35px"), 'ref_price', 'price'=>  array("width" => "35px"), 'Remarks');
$form["ORIENT"] = "L";
$form["LIST"] = array('id', 'type_id', 'name', 'price','Remarks');
$form["DISPLAY_LIST"] = array('ID', 'Tipo', 'Nome do Procedimento', 'Preco','Observação');
//pager ends
$form["FLD"] = array(
    array(
        "id" => "type_id",
        "name" => "type_id",
        "label" => "*Type ID",
        "type" => "text",
        "width" => "15px",
        "value" => '',
        "option" => "",
        "placeholder" => "Drug Group",
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