<?php
/**
 * Configuration for "user_group" table form
 */
$form = array();
$form["OBJID"] = "UGID";
$form["TABLE"] = "user_group"; //primary key
$form["FORM_CAPTION"] = "User Group"; //data table
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/user_group";
//pager starts
$form["CAPTION"] = lang('User Group') ."<input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('user_group/create') . "\' value=\'Adicionar\'>";
$form["ACTION"] = base_url() . "index.php/user_group/edit/";
$form["ROW_ID"] = "UGID";
$form["COLUMN_MODEL"] = array('UGID' => array("width" => "35px"), 'Name', 'Active' => array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')), 'Remarks', 'MainMenu');
$form["ORIENT"] = "L";
$form["LIST"] = array('UGID', 'Name', 'Active', 'Remarks', 'MainMenu'); // List of all fields got from database
$form["DISPLAY_LIST"] = array('Id', lang('Name'), lang('Active'), lang('Remarks'), lang('Main Menu')); // List of all column names that display in table
//pager ends
$form["FLD"] = array(
    array(
        "id" => "Name",
        "name" => "Name",
        "label" => "*Name",
        "type" => "text",
        "value" => '',
        "option" => "",
        "placeholder" => "Name",
        "rules" => "trim|required|xss_clean",
        "style" => "",
        "class" => "input"
    ),
    array(
        "id" => "Active",
        "name" => "Active",
        "label" => "Active",
        "type" => "boolean",
        "value" => '',
        "option" => "",
        "placeholder" => "Active",
        "rules" => "trim|xss_clean",
        "style" => "",
        "class" => "input"
    ),
    array(
        "id" => "Remarks",
        "name" => "Remarks",
        "label" => "Remarks",
        "type" => "remarks",
        "value" => '',
        "option" => "",
        "placeholder" => "Remarks",
        "rules" => "trim|xss_clean",
        "style" => "",
        "class" => "input"
    ),
    array(
        "id" => "MainMenu",
        "name" => "MainMenu",
        "label" => "Main Menu",
        "type" => "text",
        "value" => '',
        "option" => "",
        "placeholder" => "Main Menu",
        "rules" => "trim|xss_clean",
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