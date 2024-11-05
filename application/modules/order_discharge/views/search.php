<div>
    <div class="row" >
        <div class="col-md-2 ">
            <?php
            switch ($department) {
                case 'EMR':
                    echo Modules::run('leftmenu/active_list', $department);
                    break;
                case 'OPD':
                    echo Modules::run('leftmenu/active_list', $department);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/ward'); //runs the available left menu for preferance
                    break;
                default:
                    echo 'wrong department';
            }
            ?>
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?=lang('Discharge Order');?></b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>