<?php

////////Configuration for ICD10 form added on 13.05.2024 by Jcololo
$form = array();
$form["OBJID"] = "PFID";
$form["TABLE"] = "pharmaceutical_form";
$form["FORM_CAPTION"] = "Pharmaceutical Form";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/pharmaceutical";
//pager starts
$form["CAPTION"] = "Lista de Forma Farmacéutica <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('pharmaceutical/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/pharmaceutical/edit/";
$form["ROW_ID"] = "PFID";
$form["COLUMN_MODEL"] = array('PFID' => array("width" => "35px"), 'Name',  'Remarks', 'Active' => array("width" => "35px"));
$form["ORIENT"] = "L";
$form["LIST"] = array('PFID',  'Name', 'Remarks', 'Active');
$form["DISPLAY_LIST"] = array('PFID',  'Name',  'Remarks', 'Active');
//pager ends
$form["FLD"] = array(
    array(
        "PFID" => "PFID",
        "name" => "Name",
        "label" => "*Nome",
        "type" => "text",
        "width" => "15px",
        "value" => '',
        "option" => "",
        "placeholder" => "Nome CID10",
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
