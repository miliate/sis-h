<?php

////////Configuration for ICD10 form added on 13.05.2024 by Jcololo
$form = array();
$form["OBJID"] = "ICDID";
$form["TABLE"] = "icd10";
$form["FORM_CAPTION"] = "Lista de Código CID-10";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/icd10";
//pager starts
$form["CAPTION"] = "Lista de CID-10 <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('icd10/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/icd10/edit/";
$form["ROW_ID"] = "ICDID";
$form["COLUMN_MODEL"] = array('ICDID' => array("width" => "35px"), 'Code' => array("width" => "35px"), 'Code' => array("width" => "35px"), 'Name', 'isNotify' =>  array("width" => "50px"), 'Remarks', 'Active' => array("width" => "35px"));
$form["ORIENT"] = "L";
$form["LIST"] = array('ICDID', 'Code', 'Name', 'isNotify', 'Remarks', 'Active');
$form["DISPLAY_LIST"] = array('ICDID', lang('Code'), lang('Name'), lang('isNotify'), lang('Remarks'), lang('Active'));

//pager ends
$form["FLD"] = array(
    array(
        "ICDID" => "ICDID",
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
////////Configuration for patient form END;                   
