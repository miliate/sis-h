<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/form_active_list', $pid); //runs the available left menu for preferance
            ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient/banner_full', $pid);

            $form_generator = new MY_Form(lang('Active List'));
            $form_generator->form_open_current_url();

            $form_generator->input(lang('Department'), 'department', lang($default_department), '', 'readonly');
            $form_generator->input(lang('Status'), 'status', 'Pending', '', 'readonly');

            $js = 'onmousedown="onmousedown=$(\'#' . 'entry_time' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+120D\', minDate: \'+0D\'});"';
            $form_generator->input(lang('VisitDate'), 'entry_time', $default_entry_time, '', $js);

            $form_generator->dropdown(lang('Admission Type'), utf8_decode('admission_type'), $dropdown_admission_type,set_value('admission_type',$default_admission_type));

            $form_generator->dropdown(lang('Hospitalization Reason'), utf8_decode('reason'), $dropdown_reasons,
          set_value('reason',$default_reason));



            $destination_options = array(
                'Alta' => 'Alta',
                'Consulta' => 'Consulta',
                'Tratamento'=>'Tratamento',
                'Internamento' => 'Internamento',
                'Abandono' => 'Abandono',
                'Falecido' => 'Falecido',
            );
            $form_generator->dropdown(lang('Destination'), 'destination', $destination_options, $default_destination);

            $form_generator->dropdown('Custo (em Mt)', 'patient_costs',$dropdown_patient_costs,set_value('patient_costs',$default_patient_costs));

            $form_generator->dropdown('*' .'Motivo de Isenção', 'reason_nopay', $dropdown_nopay, set_value('reason_nopay',$default_nopay));

            $form_generator->dropdown(lang('Service'), 'service', $dropdown_service, set_value('service', $default_service));
            if ($default_department == 'OPD') {
                $form_generator->dropdown(lang('Doctor'), 'doctor', $dropdown_doctor, set_value('doctor',$default_doctor));
            }
             ?>
  
             
              <p><font style="size:small;color:#fff;weight:bold;background:#ff00ff">DADOS DE RASTREIO DA COVID-19</font></p>
<?php

$repiratory_options = array(
    '0' => 'Doente Sem Sintomas Respiratórios',
    '1' => 'Doente Com Febre',
    '2'=>'Doente Com Tosse',
    '3' => 'Doente Com Febre e Tosse',
    '4' => 'Doente Com Dor de Cabeça',
    '5' => 'Doente Com Dor de Garganta',
);

            $form_generator->input('Temperatura (°C)', 'temperature', $default_temp,  'Temperatura de Rastreio');
            $form_generator->dropdown('Quadro Respiratório', 'respiratory_chart', $repiratory_options, $default_resp);
            $form_generator->dropdown('Caso Suspeito?', 'covid19_case', array('1' => 'SIM', '0' => 'NAO'), $default_case);
           echo "<p></p>";
   

           $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);

            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>

        </div>
    </div>
</div>

<script type="text/javascript">
// change Department
$("#service").change(function () {
    service_id = $("#service").val();
    $.ajax({
        url: "<?php echo base_url() ?>index.php/doctor/get_dropdown_doctors/" + service_id,
        type: "post"
    }).done(function (response) {
        response = JSON.parse(response);
        var html = '';
        for (var i = 0; i < response.length; i++) {
            console.log(response[i]);
            html += '<option value="' + response[i].id + '">' + response[i].Name + '</option>';
            if (i == 0) {
                doctor = response[i].id;
            }
        }
        $("#doctor").html(html);

    }).fail(function () {
        alert('Error');
    });
});


    $(document).ready(function () {
        $("#doctor").select2();
    });
    $(document).ready(function () {
        $("#service").select2();
    });

    $(document).ready(function () {
      cost_val = $("#patient_costs").val();
      if (cost_val=='NULL'||cost_val>0) {
        $(':input[name="reason_nopay"]').hide();
        $('label[for="reason_nopay"]').hide();
        console.log(cost_val);
      } else {
        $(':input[name="reason_nopay"]').show();
        $('label[for="reason_nopay"]').show();
      }
    });

    $('#patient_costs').change(function () {
        cost_val = $("#patient_costs").val();
        if (cost_val==0) {
            $(':input[name="reason_nopay"]').show();
            $(':input[name="reason_nopay"]').attr("required", "true");
            $('label[for="reason_nopay"]').show();
        } else {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
        }
    });
    $('#service').change(function () {
        cost_val = $("#service").val();
      /*  if (cost_val>0) {
            $(':input[name="reason_nopay"]').show();
            $(':input[name="reason_nopay"]').attr("required", "true");
            $('label[for="reason_nopay"]').show();
        } else {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
        }*/
    });


</script>
