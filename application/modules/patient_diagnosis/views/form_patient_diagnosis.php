<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">

            <?php
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $ref_id, $pid, $visit_info);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/admission', $admission, $ref_id);
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

        <div class="col-md-10">

            <?php
            // include 'diagnosis_component.php';
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
                case 'STATISTIC':
                    break;
                default:
                    echo 'wrong department';
                    break;
            }
            ?>

            <?php
            $form_generator = new MY_form(lang('Diagnosis'));
            echo form_error();
            $form_generator->form_open_current_url();

            if ($ref_id == 'STATISTIC') {
                $form_generator->input_date(lang('Visit Date'), 'visitt_date', $default_visit_date, '');
            }

            echo '<div id="message1" class="alert alert-danger" style="display:none;">';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '<span id="message_text">' . lang('Please fill in all fields') . '.</span>';
            echo '</div>';

            echo '<div id="message2" class="alert alert-danger" style="display:none;">';
            echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
            echo '<span id="message_text">' . lang('Please add at least one treatment') . '.</span>';
            echo '</div>';

            echo '<div class="form-group row">';
            echo '<div class="col-sm-5"></div>';
            echo '<div class="col-sm-7">';
            echo '<div class="col-sm-1"></div>';
            echo '<label class="col-sm-1">' . lang('Date') . ':</label>';
            echo '<div class="col-sm-2">';
            echo '<label control-label">' . $default_date . '</label>';
            echo '</div>';
            echo '<label class="col-sm-1">' . lang('Time') . ':</label>';
            echo '<div class="col-sm-2">';
            echo '<label control-label">' . $default_time . '</label>';
            echo '</div>';
            echo '<label class="col-sm-1">' . lang('Doctor') . ':</label>';
            echo '<div class="col-sm-4">';
            echo '<label control-label">' . $default_doctor . '</label>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            echo '<br>';

            if (!$is_edit) {
                echo '<div class="form-group row">';
                // echo '<div class="col-sm-1"></div>';
                echo '<div class="col-sm-7">';
                $form_generator->text_area(lang('Diagnosis'), 'diagnosis', $default_diagnosis, '', (($is_edit) ? 'disabled' : ''));
                echo '</div>';
                echo '<div class="col-sm-3 text-center">';
                foreach ($option_diagnosis_type_1 as $key => $value) {
                    echo '<div class="radio-inline">';
                    echo '<label>';
                    echo '<input type="radio" name="diagnosis_type" value="' . $key . '" class="radio-input"> ' . $value;
                    echo '</label>';
                    echo '</div>';
                }
                echo '</div>';
                echo '<div class="col-sm-1 text-center">';
                echo '<button type="button" class="btn btn-primary" id="adddiagnosis">' . lang('Add') . '</span></button>';
                echo '</div>';
                echo '</div>';

                echo '<table class="table mt-3" id="selectedTreatmentsTable">';
                echo '<thead>';
                echo '<tr>';
                echo '<th style="width: 13%;">' . lang('Diagnosis Type') . '</th>';
                echo '<th style="width: 77%;">' . lang('Diagnosis') . '</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody id="selectedTreatmentsList"></tbody>';
                echo '</table>';
                echo '<input type="hidden" id="selectedTreatments" name="selected_treatments" value="[]">';
            } else {
                $form_generator->input(lang('Diagnosis'), 'diagnosis', $default_diagnosis, '', 'disabled');
                $form_generator->input(lang('Diagnosis Type'), 'diagnosis_type', $default_diagnosis_type_1, '', 'disabled');
            }

            echo '<hr>';


            $form_generator->button_submit_reset();
            $form_generator->form_close();

            ?>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        var selectedDiagnosis = '';
        var selectedDiagnosisText = '';
        var selectedTreatments = [];

        getRadioValueAndLabel(function(result) {
            selectedDiagnosis = result.value;
            selectedDiagnosisText = result.label;
        });

        function getRadioValueAndLabel(callback) {
            $('.radio-input').click(function() {
                let value = $(this).val();
                let label = $(this).closest('label').text().trim();
                callback({
                    value,
                    label
                });
            });
        }

        $('#adddiagnosis').click(function() {
            var remarks = $('#diagnosis').val();

            if (selectedDiagnosis && remarks) {
                $('#selectedTreatmentsList').append(
                    '<tr>' +
                    '<td>' + selectedDiagnosisText + '</td>' +
                    '<td>' + remarks + '</td>' +
                    '</tr>'
                );

                selectedTreatments.push({
                    diagnosis: selectedDiagnosis,
                    remarks: remarks
                });

                $('#selectedTreatments').val(JSON.stringify(selectedTreatments));

                $('#diagnosis').val('');
                selectedDiagnosis = '';
                selectedDiagnosisText = '';
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