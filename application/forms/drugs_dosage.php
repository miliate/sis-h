<?php
/**
 * Configuration for "drugs_dosage" table form
 */
$form = array();
$form["OBJID"] = "DDSGID"; //primary key
$form["TABLE"] = "drugs_dosage"; //data table
$form["FORM_CAPTION"] = "Drugs Dosage";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/drugs_dosage";	
//pager starts
$form["CAPTION"]  = lang('Dosage')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('drug_dosage/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/drug_dosage/edit/";
$form["ROW_ID"]="DDSGID";
$form["COLUMN_MODEL"] = array( 'DDSGID'=>array("width"=>"35px"), 'Dosage', 'Type', 'Factor', 'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
$form["ORIENT"] = "L";
$form["LIST"] = array( 'DDSGID', 'Dosage', 'Type', 'Factor', 'Active'); // List of all fields got from database
$form["DISPLAY_LIST"] = array( 'ID', lang('Dosage'), lang('Type'), lang('Factor'), lang('Active')); // List of all column names that display in table
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
		"id"=>"Factor", 
		"name"=>"Factor",
		"label"=>"Factor",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Factor",
		"rules"=>"trim|xss_clean",
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
?>