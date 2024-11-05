<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Configuration");
$menu .= "</a>";
$menu .= "<a href='" . site_url('user_config/change_language') . "' class='list-group-item'>".lang("Change language")."</a>";
$menu .= "<a href='" . site_url('user_config/change_password') . "' class='list-group-item'>".lang("Change password")."</a>";
$menu .= "</div>";


$menu .= " </div> \n";
echo $menu;
?>