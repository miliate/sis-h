<div>
    <div class="row">
        <div class="col-md-2 ">
            <?php  echo Modules::run('leftmenu/active_list', $department); //runs the available left menu for preferance ?>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><b><?php echo "<i class='fa fa-group'></i> ".lang('Active List')." - Ginecologia e Obstetr&iacute;cia"; ?></b></div>
                <div id="patient_list">
                    <?php echo $pager; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="observe-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel1"><?=lang('Observer Patient')?></h4>
            </div>

            <div class="modal-body">
                <p><?=lang('Do you want to observe this patient?')?></p>
                <p class="debug-url"></p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=lang('Cancel')?></button>
                <a id="confirm-observe" class="btn btn-danger btn-ok"><?=lang('OK')?></a>
            </div>
        </div>
    </div>
</div>
