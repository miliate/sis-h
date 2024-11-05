<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";

if (Modules::run('permission/check_permission', 'special_clinic', 'edit')) {
    $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_procedures' class='list-group-item'> Procedimentos</a>";
    $menu .= "<a href='" . base_url() . "index.php/preference/load/sap_companies' class='list-group-item'> Empresas </a>";
}
$menu .= "</div>";


$menu .= " </div> \n";
echo $menu;
?>