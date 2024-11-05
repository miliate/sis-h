<?php
/**
 * Configuration for "drugs_frequency" table form
 */
$form = array();
$form["OBJID"] = "DFQYID"; //primary key
$form["TABLE"] = "drugs_frequency"; //data table
$form["FORM_CAPTION"] = "Drugs Frequency";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/drugs_frequency";	
//pager starts
$form["CAPTION"]  = lang('Frequency')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('/drug_frequency/create')."\' value=\'Add new\'>";
$form["ACTION"]  = base_url()."index.php/drug_frequency/edit/";
$form["ROW_ID"]="DFQYID";
$form["COLUMN_MODEL"] = array( 'DFQYID'=>array("width"=>"35px"), 'Frequency', 'Factor', 'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
$form["ORIENT"] = "L";
$form["LIST"] = array( 'DFQYID', 'Frequency', 'Factor', 'Active'); // List of all fields got from database
$form["DISPLAY_LIST"] = array( 'ID', lang('Frequency'), lang('Factor'), lang('Active')); // List of all column names that display in table
//pager ends
$form["FLD"]=array(
array(		
		"id"=>"Frequency", 
		"name"=>"Frequency",
		"label"=>"*Frequency",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Frequency",
		"rules"=>"trim|required|xss_clean",
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
		"label"=>"Active",
		"type"=>"boolean",
		"value"=>"",
		"option"=>"",
		"placeholder"=>"",
		"rules"=>"",
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