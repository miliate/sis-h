<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/form_active_list', $pid); //runs the available left menu for preferance
            ?>
            <?php echo Modules::run('leftmenu/vendas'); //runs the available left menu for preferance 
            ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient/banner_full', $pid);

            $form_generator = new MY_Form(lang('Active List'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown_severity(lang('Triage'), 'severity', $dropdown_severity, $default_severity);

            $form_generator->input(lang('Department'), 'department', lang($default_department), '', 'readonly');
            $form_generator->input(lang('Status'), 'status', 'Pending', '', 'readonly');

            $js = 'onmousedown="onmousedown=$(\'#' . 'entry_time' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+120D\', minDate: \'+0D\'});"';
            $form_generator->input('*'.lang('VisitDate'), 'entry_time', $default_entry_time, '', $js);

            $form_generator->dropdown('*'.lang('Admission Type'), utf8_decode('admission_type'), $dropdown_admission_type, set_value('admission_type', $default_admission_type));

            $form_generator->dropdown('*'.
                lang('Hospitalization Reason'),
                utf8_decode('reason'),
                $dropdown_reasons,
                set_value('reason', $default_reason)
            );

            $destination_options = array(
                'Alta' => 'Alta',
                'Consulta' => 'Consulta',
                'Tratamento' => 'Tratamento',
                'Internamento' => 'Internamento',
                'Abandono' => 'Abandono',
                'Falecido' => 'Falecido',
            );
            $form_generator->dropdown(lang('Destination'), 'destination', $destination_options, $default_destination);



            $form_generator->dropdown('*'.lang('Cost (in Mt)'), 'patient_costs', $dropdown_patient_costs, set_value('patient_costs', $default_patient_costs));

            $form_generator->dropdown('*' . lang('Reason for Exemption'), 'reason_nopay', $dropdown_nopay, set_value('reason_nopay', $default_nopay));


            $form_generator->dropdown(lang('Service'), 'service', $dropdown_service_name, set_value('service', $default_service_name));

            $form_generator->input(lang('Exam Type'), 'exam_type', $default_exam_type, 'Raio-X de Torax');

            if ($default_department == 'OPD') {
                $form_generator->dropdown(lang('Doctor'), 'doctor', $dropdown_doctor, set_value('doctor', $default_doctor));
            }
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active, 'readonly');

            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>

        </div>
        <div id="pop1" class="popbox"><img src="<?php echo base_url('images/severity.png') ?>" width="503" height="195"></div>
    </div>

</div>

<script type="text/javascript">
    // change Department
    $("#service").change(function() {
        service_id = $("#service").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/doctor/get_dropdown_doctors/" + service_id,
            type: "get"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].Doctor_ID + '">' + response[i].Name + '</option>';
                if (i == 0) {
                    doctor = response[i].id;
                }
            }
            $("#doctor").html(html);

        }).fail(function() {
            alert('Error');
        });
    });


    $(document).ready(function() {
        $("#doctor").select2();
        $(':input[name="exam_type"]').hide();
        $('label[for="exam_type"]').hide();
    });
    $(document).ready(function() {
        $("#service").select2();
        $(':input[name="active"]').hide();
            $('label[for="active"]').hide();
    });

    $(document).ready(function() {
        cost_val = $("#patient_costs").val();
        if (cost_val == 'NULL' || cost_val > 0) {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
            console.log(cost_val);
        } else {
            $(':input[name="reason_nopay"]').show();
            $('label[for="reason_nopay"]').show();
        }
    });

    $('#patient_costs').change(function() {
        cost_val = $("#patient_costs").val();
        if (cost_val == 0) {
            $(':input[name="reason_nopay"]').show();
            $(':input[name="reason_nopay"]').attr("required", "true");
            $('label[for="reason_nopay"]').show();
        } else {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
        }
    });

    $('#service').change(function() {
        service_id = $("#service").val();

        if (service_id > 0 && service_id == 29) {
            $(':input[name="exam_type"]').show();
            $(':input[name="exam_type"]').attr("required", "true");
            $('label[for="exam_type"]').show();
        } else {
            $(':input[name="exam_type"]').hide();
            $('label[for="exam_type"]').hide();
        }
    });

    /*    $('#service').change(function () {
           cost_val = $("#service").val();
          if (cost_val>0) {
               $(':input[name="reason_nopay"]').show();
               $(':input[name="reason_nopay"]').attr("required", "true");
               $('label[for="reason_nopay"]').show();
           } else {
               $(':input[name="reason_nopay"]').hide();
               $('label[for="reason_nopay"]').hide();
           }
       });*/

    var moveLeft = 0;
    var moveDown = 0;
    $('a.popper').hover(function(e) {

        var target = '#' + ($(this).attr('data-popbox'));
        $(target).show();
        moveLeft = $(this).outerWidth();
        moveDown = ($(target).outerHeight() / 2);

        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);

        /* if (maxRight > windowLeft && maxLeft > windowRight) {
             leftD = maxLeft;
         }*/


    }, function() {
        var target = '#' + ($(this).attr('data-popbox'));
        if (!($("a.popper").hasClass("show"))) {
            $(target).hide();
        }
    });

    /*$('a.popper').mousemove(function (e) {
        var target = '#' + ($(this).attr('data-popbox'));

        leftD = e.pageX + parseInt(moveLeft);
        maxRight = leftD + $(target).outerWidth();
        windowLeft = $(window).width() - 40;
        windowRight = 0;
        maxLeft = e.pageX - (parseInt(moveLeft) + $(target).outerWidth() + 20);

        if (maxRight > windowLeft && maxLeft > windowRight) {
            leftD = maxLeft;
        }*/
</script>