<?php
/**
 * Configuration for "top_menu" table form
 */
$form = array();
$form["OBJID"] = "UMID"; //primary key
$form["TABLE"] = "top_menu"; //data table
$form["FORM_CAPTION"] = "User Menu";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/user_menu";
//pager starts
$form["CAPTION"]  = lang('User menu')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('/top_menu/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/top_menu/edit/";
$form["ROW_ID"]="MID";
$form["COLUMN_MODEL"] = array( 'MID'=>array("width"=>"35px"), 'Name','Link', 'MenuOrder', 'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No')));
$form["ORIENT"] = "L";
$form["LIST"] = array( 'MID', 'Name','Link', 'MenuOrder', 'Active'); // List of all fields got from database
$form["DISPLAY_LIST"] = array( 'Id', lang('Name'),lang('Link'), lang('Menu Order'), lang('Active')); // List of all column names that display in table
//pager ends
$form["FLD"]=array(
array(		
		"id"=>"Name", 
		"name"=>"Name",
		"label"=>"*Name",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Name",
		"rules"=>"trim|required|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(		
		"id"=>"UserGroup", 
		"name"=>"UserGroup",
		"label"=>"User Group",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"User Group",
		"rules"=>"trim|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(		
		"id"=>"Link", 
		"name"=>"Link",
		"label"=>"Link",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Link",
		"rules"=>"trim|xss_clean",
		"style"=>"",
		"class"=>"input"
	),
array(		
		"id"=>"MenuOrder", 
		"name"=>"MenuOrder",
		"label"=>"Menu Order",
		"type"=>"text",
		"value"=>'',
		"option"=>"",
		"placeholder"=>"Menu Order",
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