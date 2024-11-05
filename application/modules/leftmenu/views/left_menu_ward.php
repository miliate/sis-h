<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
//print_r($user_menu);
// $mdsPermission = MDSPermission::GetInstance();
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";
$menu .= "<a href='" . base_url() . "index.php/ward/search/". "' class='list-group-item'> <i class='fa fa-bed'></i> ".lang('Ward List')."</a>";
$menu .= "<a href='" . base_url() . "index.php/order_discharge/search/ADM" . "' class='list-group-item'> <i class='fa fa-check-square-o' aria-hidden='true'></i> ".lang('Discharge Order')."</a>";
$menu .= "</div>";
$menu .= " </div> \n";
echo $menu;
?>