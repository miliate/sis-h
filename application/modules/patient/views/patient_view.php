<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/patient', $id, $ref_id); //runs the available left menu for preferance
            ?>
        </div>
        <div class="col-md-10 ">
            <?php
            echo Modules::run('patient/banner_full', $id);
            ?>
            <!--            --><?php //if (has_permission('patient_all_history', 'view')) { ?>
            <div class="panel panel-info">
                <div id="contact_person" style='padding: 5px;'><?php echo $contact_person; ?></div>
            </div>
            <?php if (Modules::run('permission/check_permission', 'child_birth', 'create')) {?>
            <div class="panel panel-info">
                <div id="child_birth" style='padding: 5px;'><?php echo $child_birth; ?></div>
            </div>
            <?php }?>

            <div class="panel panel-info">
                <div id="blood_donation" style='padding: 5px;'><?php echo $blood_donation; ?></div>
                <div id="blood_donation_result"
                     style='padding: 5px;'><?php echo $blood_donation_result; ?></div>
            </div>
            <div class="container-fluid">
                <div class="panel panel-info">
                    <div class="row">
                        <div class="col-md-6">
                            <div id="emr_cont" style='padding: 5px;'><?php echo $previous_emergency_visit; ?></div>
                            <div id="opd_cont" style='padding: 5px;'><?php echo $previous_opd_visits; ?></div>
                            <div id="adm_cont" style='padding: 5px;'><?php echo $admissions; ?></div>
                            <div id="diagnosis_cont" style='padding: 5px;'><?php echo $diagnosis; ?></div>
                        </div>
                        <div class="col-md-6">
                            <div id="exam_cont" style='padding: 5px;'><?php echo $exams; ?></div>
                            <div id="his_cont" style='padding: 5px;'><?php echo $patient_history; ?></div>
                            <div id="alergy_cont" style='padding: 5px;'><?php echo $allergy; ?></div>
                            <!-- <div id="pre_cont" style='padding: 5px;'>--> <?php //echo $prescriptions; ?><!--</div>-->
                            <!-- <div id="lab_cont" style='padding: 5px;'>--> <?php //echo $lab_orders; ?><!--</div>-->
                            <div id="notes_cont" style='padding: 5px;'><?php print_r($notes); ?></div>
                            <div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (Modules::run('permission/check_permission', 'add_diagnosis_statistic', 'create')) {
                echo Modules::run('patient_diagnosis/get_previous_statistic_diagnosis', $id, 'patient/view/' . $id);
            } ?>
            <!--            --><?php //} ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div id="exam_cont"></div>
            <div id="alergy_cont"></div>
            <div id="notes_cont"></div>
            <div id="diagnosis_cont"></div>
        </div>
    </div>
</div>

<!-- Modal HTML -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Detalhes</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modalContent">
        <!-- Examination details will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<script>
    $("#add_to_active_list").click(function () {
        event.preventDefault();
        $.getJSON("<?php echo base_url() . 'index.php/active_list/is_in_active_list/' . $id ?>", function (data) {
            if (data.is_in_active_list) {
                alert('This patient on active list');
            } else {
                window.location.href = $("#add_to_active_list").attr("href");
            }
        })
    })
</script>