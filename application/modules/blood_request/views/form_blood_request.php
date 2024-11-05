<?php if (Modules::run('permission/check_permission', 'blood_donation', 'edit')) { ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form('Pedido de Sangue');
            $form_generator->form_open_current_url();
            
            $form_generator->dropdown(lang('Department'), 'departament_id',
                array(
                    'Ginecologia' => 'Ginecologia',
                    'Ortopedia' => 'Ortopedia',
                ),
                $default_departament_id);
                $form_generator->dropdown(lang('Service'), 'service_id',
                array(
                    'Banco de Socorros' => 'Banco de Socorros',
                    'Urgência de Pediatria' => 'Urgência de Pediatria',
                ),
                $default_service_id);
          /*  
            $form_generator->dropdown(lang('Prev Donation'), 'prev_donation', array('1' => 'Sim', '0' => 'Não'), $default_prev_donation); */
            $form_generator->input(lang('Requested By'), 'request_by', $default_request_by);
            $form_generator->input(lang('Authorized By'), 'authorized_by', $default_authorized_by);
            $form_generator->input(lang('Response Time'), 'response_time', $default_response_time);
            $form_generator->input_date(lang('Date of Collection'), 'date_collection', $default_date_collection);
            $form_generator->input_date(lang('Date of Submission'), 'date_submission', $default_date_submission);
            $form_generator->input(lang('Issued By'), 'issued_by', $default_issued_by);
            $form_generator->input(lang('Received By'), 'receved_by', $default_receved_by);
            $form_generator->input(lang('Request Product'), 'request_product', $default_request_product);
            $form_generator->input(lang('Quantity'), 'quantity', $default_quantity);
            $form_generator->dropdown(lang('Patient Blood Group'), 'patient_gs',
                array(
                    'O' => 'O',
                    'A' => 'A',
                    'B' => 'B',     
                    'AB' => 'AB',
                ),
                $default_patient_gs);
            $form_generator->dropdown('Rhesus', 'rhesus',
                array(
                    '+' => '+',
                    '-' => '-',
                ),
                $default_rhesus);
            $form_generator->dropdown(lang('Status'), 'status', array('Em Processamento' => 'Em Processamento', 'Processado' => 'Processado', 'Aviado' => 'Aviado'), $default_status);
            $form_generator->input_date(lang('Date of Process'), 'date_process', $default_date_process);
            $form_generator->dropdown(lang('Result'), 'result',
                array(
                    'Albumina 30%' => 'Albumina 30%',
                    'Liss' => 'Liss',
                    'Coombs Indirecto' => 'Coombs Indirecto'
                ),
                $default_result);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Sim', '0' => 'N&atilde;o'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>





<script type="text/javascript">
    function render() {
        donation_type = $("#prev_donation").val();
        console.log(donation_type);
        if (donation_type == '0') {
            $(':input[name="number_of_donation"]').prop('disabled', true);
            $(':input[name="prev_place_of_donation"]').prop('disabled', true);
            $(':input[name="prev_donation_date"]').prop('disabled', true);
        }   else {
            $(':input[name="number_of_donation"]').prop('disabled', false);
            $(':input[name="prev_place_of_donation"]').prop('disabled', false);
            $(':input[name="prev_donation_date"]').prop('disabled', false);
        }
    }
    $(document).ready(function () {
        render();
        $("#prev_donation").change(function () {
            render();
        });
    });
</script>

<?php } ?>
