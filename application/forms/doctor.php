<?php
/**
 * Configuration for "doctor" table form
 */
$form = array();
$form["OBJID"] = "Doctor_ID"; //primary key
$form["TABLE"] = "doctor"; //data table
$form["FORM_CAPTION"] = "Doctor";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/doctor";
//pager starts
$form["CAPTION"]  = lang('Doctor')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('doctor/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/doctor/edit/";
$form["ROW_ID"]="Doctor_ID";
$data['dropdown_Especialidade'] = array('1','2');
$form["COLUMN_MODEL"] = array( 'Doctor_ID'=>array("width"=>"35px"), 'Name',
'Especialidade'=> array('stype' => 'select', 'editoptions' =>  array('value' => ':All;5:Yes;0:No')),
'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No'))
);
$form["ORIENT"] = "L";
$form["LIST"] = array( 'Doctor_ID', 'Name','Especialidade', 'Active'); // List of all fields got from database
$form["DISPLAY_LIST"] = array( 'ID', lang('Name'),lang('Specialty'), lang('Active')); // List of all column names that display in table
//pager ends
$form["FLD"]=array(

		array(
		"id"=>"Dosage",
		"name"=>"Dosage",
		"label"=>"*Dosage",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Dosage",
		"rules"=>"trim|required|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(
		"id"=>"Type",
		"name"=>"Type",
		"label"=>"*Type",
		"type"=>"select",
		"value"=>"",
		"option"=>array("Tablet","Liquid","Multidose","Other"),
		"placeholder"=>"Complaint Type",
		"rules"=>"required",
		"style"=>"",
		"class"=>"input"
	),
array(
		"id"=>"Active",
		"name"=>"Active",
		"label"=>"*Active",
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