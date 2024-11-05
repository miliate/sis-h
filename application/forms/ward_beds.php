<?php

////////Configuration for patient form

$form = array();
$form["OBJID"] = "BID";
$form["SQL"] = "SELECT BID, BedNo, wr.Name, w.Name as ward, wd.Active FROM ward_beds wd INNER JOIN ward_rooms wr ON wr.RID=wd.RID INNER JOIN ward w ON w.WID=wr.WID";
$form["TABLE"] = "ward_beds";
$form["FORM_CAPTION"] = "Ward Beds";
$form["SAVE"] = "";
$form["NEXT"] = "preference/load/ward_beds";	

//pager starts
$form["CAPTION"] = lang('Bed'). " <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'".site_url('ward_beds/create')."\' value=\'Adicionar\'>";
$form["ACTION"] = base_url()."index.php/ward_beds/edit/";
$form["ROW_ID"] = "BID";
$form["COLUMN_MODEL"] = array('BID', 'BedNo', 'Name', 'ward', 'Active');
$form["ORIENT"] = "L";
$form["LIST"] = array('BID','BedNo', 'Name', 'ward','Active');
$form["DISPLAY_LIST"] = array('ID', lang('BedNo'), lang('Rooms'), lang('Ward'), lang('Active'));

//pager ends	
$form["FLD"]=array(
    array(		
            "id"=>"BID", 
            "name"=>"BID",
            "label"=>"*ID of the ward bed",
            "type"=>"number",
            "value"=>'',
            "option"=>"",
            "placeholder"=>"Ward Beds id",
            "rules"=>"trim|required|xss_clean",
            "style"=>"",
            "class"=>"input"
        ),
    array(		
            "id"=>"BedNo", 
            "name"=>"BedNo",
            "label"=>"Number of the ward beds",
            "type"=>"number",
            "value"=>'',
            "option"=>"",
            "placeholder"=>"Number of the ward beds",
            "rules"=>"trim|xss_clean",
            "style"=>"",
            "class"=>"input"
        ),	
        array(		
            "id"=>"Name", 
            "name"=>"Name",
            "label"=>"Name",
            "type"=>"text",
            "value"=>"",
            "option"=>"",
            "placeholder"=>"",
            "rules"=>"required",
            "style"=>"",
            "class"=>"input"
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