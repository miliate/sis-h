<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $patient->PID);
            ?>
            <?php
            $form_generator = new MY_Form('Emergency Visit');
            $form_generator->form_open_current_url();
            $form_generator->hidden_field('pid', $patient->PID);
            //            $form_generator->input('Patient Name', 'patient_name', $patient->Full_Name_Registered, '', 'readonly');
            $form_generator->input('Datetime Visit', 'date_time_visit', $default_time, '', 'readonly');
            $form_generator->input_date_future(lang('Exam Date'), 'exam_date_visit', '',lang('Exam scheduling'));
            $form_generator->dropdown('Severity', 'severity', array('Critical' => 'Critical', 'Urgent' => 'Urgent', 'Normal' => 'Normal', 'Less Urgent' => 'Less Urgent'), $default_severity);
            $form_generator->input('Complaint / Injury', 'complaint', $default_complaint, '');
            $form_generator->input('Weight in KG', 'weight', $default_weight, 'eg. 50');
            $form_generator->input('Height in M', 'height', $default_height, 'eg. 170');
            $form_generator->input_with_default_value_button('sys BP', 'sys_bp', $default_sys_bp, 120);
            $form_generator->input_with_default_value_button('diast BP', 'diast_bp', $default_diast_bp, 80);
            $form_generator->input_with_default_value_button('Temperature in *C', 'temperature', $default_temperature, 36.6);
            $form_generator->input_with_default_value_button('Pulse', 'pulse', $default_pulse, 120);
            $form_generator->input_with_default_value_button('Saturation', 'saturation', $default_saturation, 120);
            $form_generator->input_with_default_value_button('Respiratory', 'respiratory', $default_respiratory, 120);

            $form_generator->dropdown('Alert', 'alert', array('1' => 'Yes', '0' => 'No'), $default_alert);
            $form_generator->dropdown('Voice', 'voice', array('1' => 'Yes', '0' => 'No'), $default_voice);
            $form_generator->dropdown('Pain', 'pain', array('1' => 'Yes', '0' => 'No'), $default_pain);
            $form_generator->dropdown('Un-Responsive', 'un_responsive', array('1' => 'Yes', '0' => 'No'), $default_un_responsive);

            $destination_option = array(
                'Discharged' => 'Discharged',
                'Appointment for' => 'Appointment for',
                'Admission on' => 'Admission on',
                'Died on' => 'Died on'
            );

            if($default_destination == 'Discharged') {
                $text = substr($emr->Destination, 11);
            } else if($default_destination == 'Appointment for') {
                $text = substr($emr->Destination, 16);
            } else if($default_destination == 'Admission on') {
                $text = substr($emr->Destination, 18);
            } else {
                $text = substr($emr->Destination, 8);
            }


            $form_generator->dropdown_destination1('Destination', 'destination', $destination_option, $default_destination, 'onchange="CheckDestination(this.value)";', 'Notes on discharge', '');

            $form_generator->text_area('Remarks', 'remarks', $default_remarks, 'Any remarks');
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();
            ?>
        </div>
    </div>


</div>
<script type="text/javascript">
    function CheckDestination(val){
        var element=document.getElementById('area');

        if(val=='Discharged'){
            $('#destination1').attr('placeholder', 'Notes on discharge');
            $('#destination1').attr('value', '');
        }
        else if (val=='Appointment for') {
            $('#destination1').attr('placeholder', 'Details');
            $('#destination1').attr('value', '');
        }
        else if (val=='Admission on') {
            $('#destination1').attr('placeholder', 'Ward number');
            $('#destination1').attr('value', '');
        } else {
            $('#destination1').attr('value', datetime);
        }
    }

</script>

<script>
    $(function () {
        function split(val) {
            return val.split(/,\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }

        $("#complaint")
        // don't navigate away from the field on tab when selecting an item
            .bind("keydown", function (event) {
                if (event.keyCode === $.ui.keyCode.TAB &&
                    $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
            .autocomplete({
                source: function (request, response) {
                    $.getJSON("<?php echo site_url() ?>/complaints/search/" + extractLast(request.term), {}, response);
                },
                search: function () {
                    // custom minLength
                    var term = extractLast(this.value);
                    if (term.length < 2) {
                        return false;
                    }
                },
                focus: function () {
                    // prevent value inserted on focus
                    return false;
                },
                select: function (event, ui) {
                    var terms = split(this.value);
                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push(ui.item.value);
                    // add placeholder to get the comma-and-space at the end
                    terms.push("");
                    this.value = terms.join(", ");
                    return false;
                }
            });
    });
</script>
