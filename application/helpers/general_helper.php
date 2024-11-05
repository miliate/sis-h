<?php
/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */

if (!function_exists('no_day_different')) {
    function no_day_different($date, $date_before)
    {
        $datediff = $date - $date_before;
        return floor($datediff / (60 * 60 * 24));
    }
}

if (!function_exists('render_yes_no')) {
    function render_yes_no($value) {
        if ($value == 1) {
            return lang('Yes');
        }   else {
            return lang('No');
        }
    }
}

if (!function_exists('is_triage_doctor')) {
    function is_triage_doctor() {
        if ($_SESSION['user_group_id'] == 17) {
            return true;
        }
        return false;
    }
}

if (!function_exists('is_observe_doctor')) {
    function is_observe_doctor() {
        if ($_SESSION['user_group_id'] == 15 || $_SESSION['user_group_id'] == 19) {
            return true;
        }
        return false;
    }
}