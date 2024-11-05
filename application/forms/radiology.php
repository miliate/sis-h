<?php
// //////Configuration for patient form
$form = array();
$form ["OBJID"] = "radiology_id"; //primary key
$form ["TABLE"] = "radiology"; //data table
$form ["FORM_CAPTION"] = "Radiology";
$form ["SAVE"] = "user/save";  //save URI
$form ["NEXT"] = "preference/load/radiology";
$form["CAPTION"] = "Radiology <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url('radiology/create') . "\' value=\'Add new\'>";
$form ["ACTION"] = base_url() . "index.php/radiology/edit/";
$form ["ROW_ID"] = "radiology_id";
$form["LIST"] = array('radiology_id', 'name', 'parent_group', 'Remarks', 'Active');
$form["DISPLAY_LIST"] = array('ID', 'Name', 'Group', 'Remarks', 'Active');
$form["COLUMN_MODEL"] = array('radiology_id' => array("width" => "35px"), 'name', 'parent_group', 'Remarks', 'Active' => array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
?>