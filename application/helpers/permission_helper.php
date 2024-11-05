<?php
/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */

if (!function_exists('has_permission')) {
    function has_permission($name, $type)
    {
        return Modules::run('permission/check_permission', $name, $type);
    }
}