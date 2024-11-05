<?php

$form = array();
$form["OBJID"] = "DRAID";
// $form["TABLE"] will be used in SQL query
$form["TABLE"] = "drug_route_administration";
$form["FORM_CAPTION"] = "drug Route Administration";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/drug_route_administration";	
//pager starts
$form["CAPTION"]  = lang('drug route administration')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('/drug_route_administration/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/drug_route_administration/edit/";
$form["ROW_ID"]="DRAID";
//add ICPC Name and code
$form["COLUMN_MODEL"] = array( 'DRAID'=>array("width"=>"35px"), 'Name', 'Active'=> array('stype' => 'select', 'editoptions' => array('value' => ':All;1:Yes;0:No'))); 	
$form["ORIENT"] = "L";
// $form["LIST"] will be used in SQL query
$form["LIST"] = array( 'DRAID', 'Name', 'Active');
$form["DISPLAY_LIST"] = array( 'DRAID', lang('Name'), lang('Active'));
//pager ends
$form["FLD"]=array(
    array(		
            "id"=>"Name", 
            "name"=>"Name",
            "label"=>"*Name",
            "type"=>"text",
            "value"=>'',
            "option"=>"",
            "placeholder"=>"Dosage",
            "rules"=>"trim|required|xss_clean",
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
