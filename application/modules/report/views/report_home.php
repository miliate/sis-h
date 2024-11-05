<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div style="width:95%;">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/report'); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-10 ">

            <div class="panel panel-default">
                <div class="panel-heading"><b><?=lang('Reports');?></b></div>
                <?php
                echo $calendar;
                ?>
                <div class="modal fade" id="encounter-stats" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <?php echo Modules::run('report/pdf/encounters', 'view')?>
                </div>
                <div class="modal fade" id="registration-stats" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <?php echo Modules::run('report/pdf/registration', 'view')?>
                </div>
                <div class="modal fade" id="service-stats" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <?php echo Modules::run('report/pdf/service', 'view')?>
                </div>
                <div class="modal fade" id="visit-details" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
                <div class="modal fade" id="visit-complaints" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
                <div class="modal fade" id="daily" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
                <div class="modal fade" id="order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
                <div class="modal fade" id="immr" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
                <div class="modal fade" id="performance" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        window.prettyPrint && prettyPrint();
        $('#mydate').datepicker({
            viewMode: 'years',
            minViewMode: 'months',
            format: 'yyyy-mm'
        });
    });
</script>