<?php

////////Configuration for patient form
$form = array();
$form["OBJID"] = "RID";
$form["SQL"] = "SELECT RID, wr.Name, w.Name as ward, wr.Active From ward_rooms wr INNER JOIN ward w ON wr.WID=w.WID";
$form["TABLE"] = "ward_rooms";
$form["FORM_CAPTION"] = "Ward_rooms";
$form["SAVE"] = "";
$form["NEXT"]  = "preference/load/ward_rooms";
//pager starts
$form["CAPTION"]  = lang('Rooms')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('ward_rooms/create')."\' value=\'Adicionar\'>";
$form["ACTION"]  = base_url()."index.php/ward_rooms/edit/";
$form["ROW_ID"] = "RID";
$form["COLUMN_MODEL"] = array('RID', 'Name', 'ward', 'Active');
$form["ORIENT"] = "L";
$form["LIST"] = array('RID', 'Name', 'ward', 'Active');
$form["DISPLAY_LIST"] = array('RID', lang('Room Name'), lang('Ward'), lang('Active'));
// Pager ends
$form["FLD"] = array(
    array(
        "id" => "RID",
        "name" => "RID",
        "label" => "ID",
        "type" => "text",
        "value" => '',
        "option" => "",
        "placeholder" => "",
        "rules" => "trim|xss_clean",
        "style" => "display:none;",
        "class" => "input"
    ),
    array(
        "id" => "Name",
        "name" => "Name",
        "label" => "*Room Name",
        "type" => "text",
        "value" => '',
        "option" => "",
        "placeholder" => "Room Name",
        "rules" => "trim|required|xss_clean",
        "style" => "",
        "class" => "input"
    ),
    array(
        "id"=>"ward", 
        "name"=>"ward",
        "label"=>"ward",
        "type"=>"text",
        "value"=>"",
        "option"=>"",
        "placeholder"=>"",
        "rules"=>"required",
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