<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/form_active_list', $pid); //runs the available left menu for preferance
            echo Modules::run('leftmenu/sap', $pid); 
            ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient/banner_full', $pid);

            $form_generator = new MY_Form(lang('Active List'));
            $form_generator->form_open_current_url();

          //  $form_generator->input(lang('Department'), 'department', lang($default_department), '', 'readonly');
            $form_generator->input(lang('Status'), 'status', lang('Pending'), '', 'readonly');

            $js = 'onmousedown="onmousedown=$(\'#' . 'entry_time' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',dateFormat: \'yy-mm-dd\',maxDate: \'+120D\', minDate: \'+0D\'});"';
            $form_generator->input(lang('VisitDate'), 'entry_time', $default_entry_time, '', $js);

            $form_generator->dropdown(lang('Admission Type'), utf8_decode('admission_type'), $dropdown_admission_type,set_value('admission_type',$default_admission_type));

            $form_generator->dropdown(lang('Hospitalization Reason'), utf8_decode('reason'), $dropdown_reasons, set_value('reason',$default_reason));

            $destination_options = array(
                'Alta' => 'Alta',
                'Consulta' => 'Consulta',
                'Tratamento'=>'Tratamento',
                'Internamento' => 'Internamento',
                'Exame Laboratorial' => 'Exame Laboratorial',
                'Abandono' => 'Abandono',
                'Falecido' => 'Falecido',
            );
            $form_generator->dropdown(lang('Destination'), 'destination', $destination_options, $default_destination);

            $form_generator->dropdown(lang('Service'), 'service', $dropdown_service, set_value('service', $default_service));

            if ($default_department == 'SAP') {
                $form_generator->dropdown('Médico Principal', 'doctor', $dropdown_doctor, set_value('doctor',$default_doctor));
            }

            $paymode_options = array(
                'Pending' => 'Aguarda Pagamento',
                'Cash' => 'Numerário',
                'POS' => 'POS',
                'Cheque'=>'Cheque',
            );

         
           // $form_generator->dropdown('*' .'Motivo de Isenção', 'reason_nopay', $dropdown_nopay, set_value('reason_nopay',$default_nopay));
            ?>
        <div id='hidden'>
            <p><font style="size:small;color:#fff;weight:bold;background:#ff00ff">TAXA DA CONSULTA</font></p>

            <?php

            if ($default_department == 'SAP') {
                $form_generator->dropdown(lang('Doctor'), 'doctor_taxa', $dropdown_doctor, set_value('doctor_taxa',$default_doctor));
            }

            $form_generator->dropdown('Taxa Da Consulta', 'patient_costs',$dropdown_patient_costs,set_value('patient_costs',$default_patient_costs));
            $form_generator->input('Price', 'price', $default_price, '', 'readonly');
            ?>
            <div class="form-group" style="text-align: center">
                <td align="center" style="align-items: center;">
                    <button type="button" class="btn btn-info" id="add_taxa_button"
                            onclick="add_taxa()">
                        <span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('Add') ?>
                    </button>
                </td>
        </div>
<hr>
            <table class="table input-sm" id="table_taxa">
                <tbody>
                <tr>
                    <th>#</th>
                    <th width="280px">Procedimento</th>
                    <th>Pre&ccedil;o</th>
                    <th><?php echo lang('Doctor') ?></th>
                    <th></th>
                </tr>
                <?php
                echo '<tr id="total_paid">';
                echo '<td></td>';
                echo '<td style="font-weight: bold; font-size: 15px">Pre&ccedil;o Total</td>';
                echo '<td style="font-weight: bold; font-size: 15px" id="total_box" name="total_box">' . $default_total . '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
                ?>
                </tbody>
                <tbody>
                </tbody>
            </table>
                <?= $form_generator->dropdown('*Forma de Pagamento', 'pay_mode', $paymode_options, $default_PayMode); ?>
                <?= $form_generator->dropdown('*Tipo de Empresa', 'company_type_id',$dropdown_company_type,set_value('company_type_id',$default_company_type));?>
                <?= $form_generator->dropdown('*Nome Institui&ccedil;&atilde;o', 'company_id',$dropdown_company,set_value('company_id',$default_company));?>
                <?= $form_generator->input('*NID do Membro Principal', 'member_pid',$pid, $default_member_pid); ?>
        </div>

 <hr>
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
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);

            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
document. getElementById("hidden").style.display = "none"; //hide.
    // Button add Taxa Da Consulta
    var index = 0;
    var total = 0;

    function add_taxa() {
        index++;

        var selected_patient_costs = $("#patient_costs :selected").text();
        var price = $("#price").val();
        var selected_doctor_taxa = $("#doctor_taxa :selected").text();

        var html = '<tr>';
        html += '<td>' + index + '</td>';
        html += '<td>' + selected_patient_costs + '</td>';
        html += '<td>' + price + '</td>';
        html += '<td>' + selected_doctor_taxa + '</td>';
        html += '<td align="center">' + '<button class="btn btn-danger btn_delete_taxa" type="button"> Deletar</button>' + '</td>';

        html += '<input type="hidden" name="patient_costs_selected[' + index + ']" value = "' + $("#patient_costs :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="price_selected[' + index + ']" value = "' + $("#price").val() + '">' + '</input>';
        html += '<input type="hidden" name="doctor_taxa_selected[' + index + ']" value = "' + $("#doctor_taxa :selected").val() + '">' + '</input>';

        html += '</tr>';

        $( "#total_paid" ).before(html);

        total = parseInt($('#total_box').html()) + parseInt(price);
        $("#total_box").html(total);
    }

    // Remove Taxa Da Consulta item
    $('#table_taxa').on('click', '.btn_delete_taxa', function () {
        $(this).closest('tr').remove();
        var price = $(this).closest('tr').children('td:eq(2)').text();
        var total = parseInt($('#total_box').html()) - parseInt(price);
        $("#total_box").html(total);
    });

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
                html += '<option value="' + response[i].Doctor_ID + '">' + response[i].Name + '</option>';
                if (i == 0) {
                    doctor = response[i].Doctor_ID;
                }
            }
            $("#doctor").html(html);
            $("#doctor_taxa").html(html);

        }).fail(function () {
            alert('Error');
        });
    });

    // get price after Taxa Da Consulta changed
    $("#patient_costs").change(function () {
        procedure_id = $("#patient_costs").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient_hospital_clinic/get_price/" + procedure_id,
            type: "post"
        }).done(function (response) {
            response = JSON.parse(response);
            var html = '';
            html = response.price
            $("#price").val(html);

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

    $(':input[name="company_id"]').hide();
    $('label[for="company_id"]').hide();
    $(':input[name="member_pid"]').hide();
    $('label[for="member_pid"]').hide();

    $('#company_type_id').change(function () {
        comp_val = $("#company_type_id").val();
        if (comp_val==2||comp_val==3) {
            $(':input[name="company_id"]').show();
            $('label[for="company_id"]').show();
            $(':input[name="member_pid"]').show();
            $('label[for="member_pid"]').show();
            
        //    var e = document.getElementById("company_id");
        //    var comp = e.value;


         //   console.log(comp);
          
        } else {
            $(':input[name="company_id"]').hide();
            $('label[for="company_id"]').hide();

            $(':input[name="member_pid"]').hide();
            $('label[for="member_pid"]').hide();
        }
    });

    $( "select[name='company_type_id']" ).change(function () {
    var compID = $(this).val();


    if(compID>0) {

        console.log(compID);
        $.ajax({
            url: "get_dropdown_company",
            dataType: 'Json',
            data: {'id':compID},
            success: function(data) {
                $('select[name="company_id"]').empty();
                $.each(data, function(key, value) {
                    $('select[name="company_id"]').append('<option value="'+ key +'">'+ value +'</option>');
                });
            }
        });


    }else{
        $('select[name="company_id"]').empty();
    }
});   



</script>
