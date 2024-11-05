<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Print");
$menu .= "</a>";

// registration statistics
$menu .= "<a onclick='openWindow(\"" . base_url() .
    "index.php/report/pdf/cv_cytology_report/print/". $cv_cytology_id ."\")' 
    class='list-group-item'> <i class='fa fa-print' style='font-size:24px;'></i> ".lang('Print Result')."</a>";

$menu .= "</div>";

$menu .= " </div> \n";
echo $menu;
?>