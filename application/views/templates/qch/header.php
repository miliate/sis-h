<!DOCTYPE html>
<html lang="pt">
<head>
    <title>QHIS - Health Information System</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url() ?>/images/mds-icon.png" rel="icon">

    <link href="<?php echo base_url() ?>assets/lib/bootstrap-3.1.1-dist/css/bootstrap.css" rel="stylesheet"
          media="screen">
    <!--    <link href="-->
    <?php //echo base_url()?><!--assets/lib/bootswatch/bootstrap.min.css" rel="stylesheet" media="screen">-->
    <!-- JQGrid -->
    <link href="<?php echo base_url() ?>/css/jquery.alerts.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>/css/demo.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>/css/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>/css/themes/ui.jqgrid.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url() ?>/assets/lib/font-awesome-4.4.0/css/font-awesome.css" rel="stylesheet"
          type="text/css"/>
    <!-- QCH -->
    <link href="<?php echo base_url() ?>assets/common/qch.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url() ?>assets/common/navbar_custom.css" rel="stylesheet" type="text/css">

    <!-- SCRIPT-->
    <script src="<?php echo base_url() ?>assets/lib/jquery.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>assets/lib/bootstrap-3.1.1-dist/js/bootstrap.js"
            type="text/javascript"></script>
    <script src="<?php echo base_url() ?>/js/ui.js" type="text/javascript"></script>

    <?php
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/jquery.hotkeys-0.7.9.min.js'></script>";
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/jquery.print.js'></script>";
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/chili-1.7.pack.js'></script>";
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/jquery.cookie.js'></script> ";

    echo "\n<script type='text/javascript' src='" . base_url() . "/js/mdsCore.js'></script> ";
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/mdsmailer.js'></script> ";


    //<!--        scripts for pager starts-->
    echo "\n<script src='" . base_url() . "/js/jquery.layout.js' type='text/javascript'></script>";
    echo "\n<script src='" . base_url() . "/js/i18n/grid.locale-en.js' type='text/javascript'></script>";
    echo "\n<script type='text/javascript'>";
    echo "\n$.jgrid.no_legacy_api = true;";
    echo "\n$.jgrid.useJSON = true;";
    echo "\n</script>";
    echo "\n<script src='" . base_url() . "/js/jquery.jqGrid.min.js' type='text/javascript'></script>";
    echo "\n<script src='" . base_url() . "/js/jquery.tablednd.js' type='text/javascript'></script>";
    echo "\n<script src='" . base_url() . "/js/jquery.contextmenu.js' type='text/javascript'></script>";
    echo "\n<script src='" . base_url() . "/js/ui.multiselect.js' type='text/javascript'></script>";
    echo "\n<script type='text/javascript' src='" . base_url() . "/js/datepicker-pt.js'></script>";
    ?>
    <link href="<?php echo base_url() ?>/assets/lib/select2/css/select2.min.css" rel="stylesheet" type="text/css">
    <script src="<?php echo base_url() ?>/assets/lib/select2/js/select2.min.js" type="text/javascript"></script>
<!--    <style>-->
<!--        .row li {-->
<!--            border-right: solid white;-->
<!--        }-->
<!--    </style>-->

<style>
     .image-container {
        position: relative;
        display: inline-block;
    }
    
    .notify-badge {
        position: absolute;
        
        color: white;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
    }
    
    .notify-badge.top {
        top: -3px;
        right: -10px;
        background-color: red;
    }
    
    .notify-badge.bottom {
        bottom: -20px;
        right: -20px;
        background-color: green;
    }
    
    .image-container img {
        display: block;
        max-width: 100%;
        height: auto;
    }
</style>

</head>
<body>
<div class="container">