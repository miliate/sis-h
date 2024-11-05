<?php

/**
 * Configuration for "patient" table form
 */
$form = array ();
$form ["OBJID"] = "UID"; //primary key
$form ["TABLE"] = "user"; //data table
$form ["FORM_CAPTION"] = "User";
$form ["SAVE"] = "user/save";  //save URI
$form ["NEXT"] = "preference/load/user";
// pager starts //
$form ["CAPTION"] = lang('Available user')." <input type=\'button\' class=\'btn btn-xs btn-success\' onclick=self.document.location=\'" . site_url ( 'user/create/' ) . "\' value=\'Adicionar\'>";
$form ["ACTION"] = base_url () . "index.php/user/edit/";
$form ["ROW_ID"] = "UID";
$form ["COLUMN_MODEL"] = array (
		'UID' => array (
				"width" => "35px" 
		),
		'Name',
		'OtherName',
		'DateOfBirth',
		'Active' => array (
				'stype' => 'select',
				'editoptions' => array (
						'value' => ':All;1:Yes;0:No' 
				) 
		),
		'Gender' => array (
				'stype' => 'select',
				'editoptions' => array (
						'value' => ':All;Male:Male;Female:Female' 
				) 
		),
		'UserName',
		'Address_Village'
);
$form ["ORIENT"] = "L";

// List of all fields got from database
$form ["LIST"] = array (
		'UID',
		'Name',
		'OtherName',
		'DateOfBirth',
		'Active',
		'Gender',
		'UserName',
		'Address_Village'
);

// List of all column names that display in table
$form ["DISPLAY_LIST"] = array (
		'Id',
		lang('First name'),
		lang('Other name'),
		lang('Date of birth'),
		lang('Active'),
		lang('Gender'),
		lang('User name'),
		lang('Village')
);
// Address_Street Address_Village Address_DSDivision Address_District
$patient ["JS"] = "
<script>
function ForceSave(){
}
</script>
";
// //////Configuration for patient form END;
?>