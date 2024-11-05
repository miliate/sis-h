<?php
/**
 * Copyright (C) 2016 CNL, Inje University - cnl.inje.ac.kr
 */
echo Modules::run('menu/top', 'preference');

if (!isset($body)) {
    echo 'Body is NOT set';
} else {
    echo $body;
}