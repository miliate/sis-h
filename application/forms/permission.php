<?php
/**
 * Configuration for "user_group" table form
 */
$form = array();
$form["OBJID"] = "UGID"; //primary key
$form["TABLE"] = "user_group"; //data table
$form["FORM_CAPTION"] = "User Group";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/user_group";
//pager starts
$form["CAPTION"] = "";
$form["ACTION"] = base_url() . "index.php/permission/edit/";
$form["ROW_ID"] = "UGID";
$form["COLUMN_MODEL"] = array('UGID' => array("width" => "35px"), 'Name', 'Active' => array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
$form["ORIENT"] = "L";
$form["LIST"] = array('UGID', 'Name', 'Active'); // List of all fields got from database
$form["DISPLAY_LIST"] = array('Id', lang('Name'), lang('Active')); // List of all column names that display in table

$patient["JS"] = "
<script>
function ForceSave(){
}
</script>
";
////////Configuration for patient form END;                   
?>