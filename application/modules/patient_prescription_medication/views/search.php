<div>
    <div class="row">
        <!--        <div class="col-md-2 ">-->
        <!--            --><?php //echo Modules::run('leftmenu/pharmacy'); //runs the available left menu for preferance ?>
        <!--        </div>-->
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?=lang("Prescription")?></b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>