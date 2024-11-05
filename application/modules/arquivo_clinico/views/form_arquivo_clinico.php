<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>


            <?php
            $form_generator = new MY_Form('Arquivo Clinico');
            $form_generator->form_open_current_url();
            $form_generator->input_date('* Data de Entrada', 'data_entrada', $default_data_entrada, '');
            $form_generator->dropdown('Proveniência', 'department', $dropdown_department, $default_department);
          //  $form_generator->dropdown('Serviço', 'service', $dropdown_service, $default_service);
            $form_generator->dropdown('Serviço', 'service', $dropdown_service, set_value('service', $default_service));
            $discharge_options = array(
                '' => '--------',
                'Alta Normal' => 'Clínica com tratamento terminado',
                'Clínica com tratamento a continuar nas consultas externas' => 'Clínica com tratamento a continuar nas consultas externas',
                'Clínica com tratamento a continuar nas consultas externas' => 'Clínica com tratamento a continuar nas consultas externas',
                'Abandono' => 'Por Abandono',
                'A Pedido' => 'A Pedido',
                'Compulsiva' => 'Compulsiva',
                'Óbitoa48' => 'Falecimento antes de 48 horas de internamento',
                'Óbitod48' => 'Falecimento após 48 horas de internamento',
                'Transferência para o seguinte estabelecimento' => 'Transferência para o seguinte estabelecimento',

            );
            $form_generator->dropdown('Tipo de Alta', 'tipo_alta', $discharge_options, $default_tipo_alta);
            $form_generator->input('Diagnostico de Alta', 'diagnostico_alta', $default_diagnostico_alta);
            $form_generator->input('Código de Alta (CID-10)', 'cid10_alta', $default_cid10_alta);
            $form_generator->input_date('* Data da Alta', 'data_alta', $default_data_alta);
            $form_generator->input('Expede', 'remetente', $default_remetente);
            $form_generator->input('Autorizado Por', 'autorizado_por', $default_autorizado_por);
            $form_generator->input('Recebido Por', 'recebido_por', $default_recebido_por);
            $form_generator->input_date('* Data da Recepção', 'recebido_em', $default_recebido_em, '');
            $form_generator->dropdown('Estado do Arquivo', 'estado_arquivo', array(
                'Arquivado' => 'Arquivado',
                'Requisitado' => 'Requisitado',
                'Desaparecido' => 'Desaparecido',
                'No Arquivo Morto' => 'No Arquivo Morto',
                'Sem Informação' =>    'Sem Informação',
                'Outro' =>    'Outro'
            ), $default_active);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Sim', '0' => 'N&atilde;o'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();



            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    // change Department
    $("#department").change(function() {
        department_id = $("#department").val();
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
            $("#service").html(html);

        }).fail(function() {
            alert('Error');
        });
    });
</script>
