<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php
            switch ($ref_type) {
                case 'EMR':
                echo Modules::run('leftmenu/emr', $ref_id, $pid, $visit_info);
                    break;
                case 'ADM':
                     echo Modules::run('leftmenu/admission', $admission, $ref_id); //runs the available left menu for preferance 
                    break;
                case 'OPD':
                echo Modules::run('leftmenu/opd', $ref_id, $pid, $opd_visits_info, $is_discharged);
                    break;
                default:
                echo 'wrong department';
                    break;
            }
            ?>
        </div>
        <div class="col-md-10 ">
            <div id="message1" class="alert alert-danger" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Please fill in all fields.'); ?></span>
            </div>
            <div id="message2" class="alert alert-danger" style="display:none;">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <span id="message_text"><?php echo lang('Please add at least one treatment.'); ?></span>
            </div>
            <?php
            echo Modules::run('patient/banner', $pid);
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('emergency_visit/info', $ref_id);
                    break;
                case 'ADM':
                    echo Modules::run('admission/info', $ref_id);
                    break;
                case 'OPD':
                    echo Modules::run('opd_visit/info', $ref_id);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }

            $form_generator = new MY_Form(lang('Treatment Order'));
            $form_generator->form_open_current_url();
            ?>
            <div class="form-group">
                <label for="treatment"><?php echo lang('Treatment Name'); ?></label>
                <select class="form-control" id="treatment" name="treatment">
                    <?php foreach ($treatment_options as $treatment_id => $treatment_name): ?>
                        <option value="<?php echo $treatment_id; ?>"><?php echo $treatment_name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks"><?php echo lang('Remarks'); ?></label>
                <textarea class="form-control" id="remarks" name="remarks"></textarea>
            </div>

            <button type="button" class="btn btn-primary" id="addTreatment"><span class="glyphicon glyphicon-plus"></span></button>

            <table class="table mt-3" id="selectedTreatmentsTable">
                <thead>
                    <tr>
                        <th><?php echo lang('Treatment Name'); ?></th>
                        <th><?php echo lang('Remarks'); ?></th>
                    </tr>
                </thead>
                <tbody id="selectedTreatmentsList"></tbody>
            </table>

            <input type="hidden" id="selectedTreatments" name="selected_treatments" value="[]">
            <div class="mt-3">
                <?php
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>
</div>

<?php
echo Modules::run('template/footer');
?>
<script>
    $(document).ready(function() {
        var selectedTreatments = [];

        $('#addTreatment').click(function() {
            var selectedTreatment = $('#treatment').val();
            var selectedTreatmentText = $('#treatment option:selected').text();
            var remarks = $('#remarks').val();

            if (selectedTreatment) {
                var treatmentDetails = {
                    treatment_id: selectedTreatment,
                    treatment_name: selectedTreatmentText,
                    remarks: remarks
                };

                selectedTreatments.push(treatmentDetails);

                $('#selectedTreatmentsList').append(
                    '<tr>' +
                    '<td>' + selectedTreatmentText + '</td>' +
                    '<td>' + remarks + '</td>' +
                    '</tr>'
                );
                $('#selectedTreatments').val(JSON.stringify(selectedTreatments));

                // Clear the input fields
                $('#remarks').val('');
            } else {
                $("#message1").fadeIn();
            }
        });

        $('form').submit(function(event) {
            if (selectedTreatments.length === 0) {
                $("#message2").fadeIn();
                event.preventDefault();
            }
        });
    });
</script>
