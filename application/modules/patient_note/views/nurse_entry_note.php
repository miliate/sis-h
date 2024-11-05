<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-10 ">
            <div class="panel-heading">
                <b><?php echo "<i class='fa fa-group'></i> - " . lang("Entry Note"); ?></b>
            </div>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php
                    $form_generator = new MY_Form(lang("Clinical Diary"));
                    $form_generator->get_nurse_diary_menu($pid, $ref_id); 
                    ?>
                </div>
            </div>

            <div class="container-fluid">
                <div class="panel panel-info">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="hist_cont" style='padding: 5px;'><?php echo $patient_history;  ?></div>
                            <div id="exami_cont" style='padding: 5px;'><?php echo $exams; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal HTML -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel"><?php echo lang("Details"); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body" id="modalContent">
        <!-- Examination details will be loaded here -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('Close'); ?></button>
        </div>
    </div>
</div>