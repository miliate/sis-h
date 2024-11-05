<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
$menu = "";
$menu .= "<div id='left-sidebar1' style='position:fixed1;'>\n";
$menu .= "<div class='list-group'>";
$menu .= "<a href='' class='list-group-item active'>";
$menu .= lang("Commands");
$menu .= "</a>";
$menu .= "<a href='" . base_url() . "index.php/emergency_visit/triage' class='list-group-item'>". lang('Triaged Patient'). "</a>";
if (Modules::run('permission/check_permission', 'emr_observe', 'view')) {
    $menu .= "<a href='" . base_url() . "index.php/emergency_visit/my_observed_patient' class='list-group-item'>" . lang('My Observed Patient') . "</a>";
}
$menu .= "</div>";


$menu .= " </div> \n";
echo $menu;
?>