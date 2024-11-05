<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/patient', $pid, $ref_id);
            echo Modules::run('leftmenu/form_active_list', $pid);
            ?>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient/banner_full', $pid);

            $form_generator = new MY_Form(lang('Active List'));
            $form_generator->form_open_current_url();

            //  $form_generator->input(lang('Department'), 'department', lang($default_department), '', 'readonly');
            $form_generator->input(lang('Status'), 'status', lang('Pending'), '', 'readonly');

            $js = 'onmousedown="onmousedown=$(\'#' . 'entry_time' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+120D\', minDate: \'+0D\'});"';
            $form_generator->input(lang('VisitDate'), 'entry_time', $default_entry_time, '', $js);

            $form_generator->dropdown('*' . lang('Admission Type'), utf8_decode('admission_type'), $dropdown_admission_type, set_value('admission_type', $default_admission_type));

            $form_generator->dropdown('*' . lang('Hospitalization Reason'), utf8_decode('reason'), $dropdown_reasons, set_value('reason', $default_reason));

            $destination_options = array(
                'Alta' => 'Alta',
                'Consulta' => 'Consulta',
                'Tratamento' => 'Tratamento',
                'Internamento' => 'Internamento',
                'Abandono' => 'Abandono',
                'Falecido' => 'Falecido',
            );
            $form_generator->dropdown(lang('Destination'), 'destination', $destination_options, $default_destination);

            $form_generator->dropdown(lang('Department'), 'entry_department', $dropdown_department, $default_department_hospital);
            $form_generator->dropdown(lang('Service'), 'entry_service', $dropdown_service, $default_service);

            $form_generator->dropdown(lang('Doctor'), 'doctor', $dropdown_doctor, set_value('doctor', $default_doctor));

            ?>

            <?php
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);

            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    // change Department
    $("#service").change(function() {
        service_id = $("#service").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/doctor/get_dropdown_doctors/" + service_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                console.log(response[i]);
                html += '<option value="' + response[i].Doctor_ID + '">' + response[i].Name + '</option>';
                if (i == 0) {
                    doctor = response[i].Doctor_ID;
                }
            }
            $("#doctor").html(html);
            $("#doctor_taxa").html(html);

        }).fail(function() {
            alert('Error');
        });
    });

    $(document).ready(function() {
        $("#doctor").select2();
    });
    $(document).ready(function() {
        $("#service").select2();
    });

    // change Department
    $("#entry_department").change(function() {
        department_id = $("#entry_department").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_dropdown_services/" + department_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                console.log(response[i]);
                html += '<option value="' + response[i].service_id + '">' + response[i].abrev + '</option>';
                if (i == 0) {
                    service_id = response[i].service_id;
                }
            }
            $("#entry_service").html(html);

        }).fail(function() {
            alert('Error');
        });
    });
</script>