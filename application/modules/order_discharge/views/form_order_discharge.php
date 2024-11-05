<!-- Inclua os arquivos CSS e JS do Select2 -->

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">

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
        <div class="col-md-8 col-md-offset-1">

            <?php
            include dirname(__FILE__) . '/../../patient_diagnosis/views/diagnosis_component.php';

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
            ?>

            <?php
            /*
                        $my_sql_date = "2011-07-26 20:05:00";
                        $date_time_obj = new DateTime($my_sql_date);
                        echo $date_time_obj->format('H') . ":" . $date_time_obj->format('m');
            */

            $form_generator = new MY_Form(lang('Discharge Order'));
            $form_generator->form_open_current_url();
            $js = 'onmousedown="onmousedown=$(\'#' . 'date' . '\').datepicker({changeMonth: true,changeYear: true,yearRange: \'c-40:c+40\',datetFormat: \'yy-mm-dd\',maxDate: \'+0D\', minDate: \'-90D\'});"';
            $form_generator->input(lang('DatetimeDischarge'), 'date', $default_date, '', $js);

            $form_generator->input(lang('Status'), 'status', $default_status, '', 'disabled');

            $discharge_options = array(
                '-' => '-----',
                'Alta Clinica' =>lang('Clinic discharge with treatment') ,
                'Por Abandono' => lang('Abandonment'),
                'A Pedido' => lang('On request'),
                'Obito' => lang('Death'),
                'Transferencia para o Seguinte Estabelecimento' => lang('Transfer to the next establishment')
            );

            $form_generator->dropdown(lang('Outcome'), 'out_come', $discharge_options, $default_out_come);
            // $form_generator->diagnosis(lang('Direct Diagnosis'), 'direct_diagnosis', $default_direct_diagnosis);


            //                $form_generator->diagnosis('Principal-------1*', 'direct_diagnosis', $default_direct_diagnosis);

            get_diagnosis_search_component("direct_diagnosis");
            ?>


            <div id="extra_form" class="mt-3">
            </div>
            <?php

            $die_type_options = array(
                'Neonatal' => lang('Fetal'),
                'Outros' => lang('Non-Fetal'),
                'Materna' => lang('Maternal'),
            );

            $global_result_options = array(
                'Curado' => lang('Cured'),
                'Melhorado' => lang('Improved'),
                'Estacionario' => lang('Stationary'),
                'Piorado' => lang('worsened'),
                'Indeterminado' => lang('Undetermined'),
                'Sem doenca' => lang('Without Disease'),
                'Grau de incapacidade' =>lang('Degree of Disability') ,
                'Falecido' => lang('Deceased'),
            );

            $alta_type_options = array(
                'Clinica com Tratamento Terminado' => 'Clínica com Tratamento Terminado',
                'Clinica com Tratamento a continuar nas CE' => 'Clinica com Tratamento a continuar nas CE',
            );

            $form_generator->dropdown(lang('Die Type'), 'die_type', $die_type_options, $default_die_type);
            $form_generator->dropdown(lang('Clinical Discharge'), 'discharge_type', $alta_type_options, $default_alta_type);
            ?>

            <?php $form_generator->input('Transferido para', 'TransferTo', '', 'Destino', $default_transfer_to); ?>

            <?php $form_generator->dropdown(lang('Overall Result'), 'global_result', $global_result_options, $default_global_result); ?>
            <div id="extra_form">
                <?php echo
                Modules::run('order_discharge/render_extra_form', $order_discharge_id, set_value('out_come', $default_out_come), set_value('die_type', $default_die_type)); ?>
            </div>

            <!--            <div id="diagnosis_form">-->
            <!--                --><?php
                                    //                    $form_generator->diagnosis(lang('Direct Diagnosis'), 'direct_diagnosis', $default_direct_diagnosis);
                                    //                
                                    ?>
            <!--            </div>-->

            <div id="die_form">
                <?php
                $form_generator->legend(lang('Diagnosis'));
                $form_generator->dropdown(lang('Diagnosis Confirmed By'), 'diagnosis_confirmed_by', array(
                    '1' => 'Anatomia Patológica',
                    '2' => 'Médico Legal',
                    '3' => 'Sem Autópsia'
                ), $default_diagnosis_confirmed_by);


                //                $form_generator->diagnosis(lang('Direct Diagnosis'), 'direct_diagnosis', $default_direct_diagnosis);
                ?>

                <?php


                function get_icd10_code($id)
                {

                    /*  $this->load->model('m_patient_diagnosis');
  $this->load->model('m_icd10');
    $res = array();
    foreach ($this->m_icd10->get($id) as $icd) {
        $res[$icd->ICDID] = $icd->Code;
    }
    return $res;*/
                }

                ?>

                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo lang('Approximate duration of the illness')?>:</label>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaDirecta_anos" value="<?= set_value('TempoCausaDirecta_anos', $default_direct_diagnosis_anos) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaDirecta_anos" autocomplete="off">
                            <label><?php echo lang('Years')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaDirecta_meses" value="<?= set_value('TempoCausaDirecta_meses', $default_direct_diagnosis_meses) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaDirecta_meses" autocomplete="off">
                            <label><?php echo lang('Months')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaDirecta_dias" value="<?= set_value('TempoCausaDirecta_dias', $default_direct_diagnosis_dias) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaDirecta_dias" autocomplete="off">
                            <label><?php echo lang('Days')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="time" name="TempoCausaDirecta_horas" value="<?= set_value('TempoCausaDirecta_horas', $default_direct_diagnosis_horas) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaDirecta_horas" autocomplete="off">
                            <label><?php echo lang('Time')?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">&nbsp;</div>
                </div>

                <?php get_diagnosis_search_component("medium_diagnosis_o"); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo lang('Approximate duration of the illness')?>:</label>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaIntermedia_anos" value="<?= set_value('TempoCausaIntermedia_anos', $default_medium_diagnosis_anos) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaIntermedia_anos" autocomplete="off">
                            <label><?php echo lang('Years')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaIntermedia_meses" value="<?= set_value('TempoCausaIntermedia_meses', $default_medium_diagnosis_meses) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaIntermedia_meses" autocomplete="off">
                            <label><?php echo lang('Months')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaIntermedia_dias" value="<?= set_value('TempoCausaIntermedia_dias', $default_medium_diagnosis_dias) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaIntermedia_dias" autocomplete="off">
                            <label><?php echo lang('Days')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="time" name="TempoCausaIntermedia_horas" value="<?= set_value('TempoCausaIntermedia_horas', $default_medium_diagnosis_horas) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaIntermedia_horas" autocomplete="off">
                            <label><?php echo lang('Time')?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">&nbsp;</div>
                </div>


                <?php get_diagnosis_search_component("basic_diagnosis_o"); ?>
                <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo lang('Approximate duration of the illness')?>:</label>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaBasica_anos" value="<?= set_value('TempoCausaBasica_anos', $default_basic_diagnosis_anos) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaBasica_anos" autocomplete="off">
                            <label><?php echo lang('Years')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaBasica_meses" value="<?= set_value('TempoCausaBasica_meses', $default_basic_diagnosis_meses) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaBasica_meses" autocomplete="off">
                            <label><?php echo lang('Months')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="number" name="TempoCausaBasica_dias" value="<?= set_value('TempoCausaBasica_dias', $default_basic_diagnosis_dias) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaBasica_dias" autocomplete="off">
                            <label><?php echo lang('Days')?></label>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <div class="form-inline">
                            <input type="time" name="TempoCausaBasica_horas" value="<?= set_value('TempoCausaBasica_horas', $default_basic_diagnosis_horas) ?>" class="form-control input-sm ui-autocomplete-input" id="TempoCausaBasica_horas" autocomplete="off">
                            <label><?php echo lang('Time')?></label>
                        </div>
                    </div>
                    <div class="col-sm-6">&nbsp;</div>
                </div>

                <b><?php echo lang('MEDICAL CERTIFICATE OF DEATH')?></b>

                <?php
                $form_generator->input(lang('Doctor Name'), 'diagnosis_assigned_by', $default_diagnosis_assigned_by, '', 'Nome do Médico'); ?>
            </div> <!-- die Form -->


            <div id="diagnostico_alta_form">
                <b><?php echo lang('DISCHARGE DIAGNOSIS')?></b>

                <?php


                //                $form_generator->diagnosis('Principal-------1*', 'direct_diagnosis', $default_direct_diagnosis);
                echo '<hr>'.lang('Secondary').'---2</hr>';

                get_diagnosis_search_component("medium_diagnosis");
                echo '<hr>'.lang('Secondary').'---3</hr>';

                get_diagnosis_search_component("basic_diagnosis");
                echo '<hr>'.lang('Secondary').'---4</hr>';

                get_diagnosis_search_component("basic_diagnosis2");
                ?>

            </div>
            <?php
            $form_generator->hr();
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
            //    $form_generator->dropdown('Evento concluído?', 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->checkbox_confirm(lang('Event completed').'?', 'active', $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(':input[name="status"]').hide();
    $('label[for="status"]').hide();

    function load_extra_form() {
        type_1 = $("#out_come").val();
        type_2 = $("#die_type").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/order_discharge/load_additional_form/<?= $order_discharge_id ?>",
            type: "post",
            dataType: "html",
            data: {
                'type_1': type_1,
                'type_2': type_2
            }
        }).done(function(response) {
            $("#extra_form").html(response);
        }).fail(function() {
            alert('Error');
        });
    }

    function render() {
        outcome_type = $("#out_come").val();
        if (outcome_type == 'Obito') {
            $(':input[name="die_type"]').show();
            $('label[for="die_type"]').show();
            $('#die_form').show();
            //            $("#die_form").find("#hide_diagnosis_die").attr("id", "direct_diagnosis");
            //            $("#die_form").find("div[name=direct_diagnosis_die]").attr("name", "direct_diagnosis");
            $('#diagnosis_form').hide();
            //            $("#diagnosis_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_others");
            //            $("#diagnosis_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_others");
            $(':input[name="discharge_type"]').hide();
            $('label[for="discharge_type"]').hide();
            $(':input[name="TransferTo"]').hide();
            $('label[for="TransferTo"]').hide();
            $('#diagnostico_alta_form').hide();
            //            $("#diagnostico_alta_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_clinic");
            //            $("#diagnostico_alta_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_clinic");
            $(':input[name="global_result"]').val('Falecido');
            $("#global_result").attr('disabled', 'disabled');
        } else if (outcome_type == 'Alta Clinica') {

            $(':input[name="discharge_type"]').show();
            $('label[for="discharge_type"]').show();
            $(':input[name="TransferTo"]').hide();
            $('label[for="TransferTo"]').hide();
            $(':input[name="die_type"]').hide();
            $('label[for="die_type"]').hide();
            $('#diagnostico_alta_form').show();
            //            $("#diagnostico_alta_form").find("#hide_diagnosis_clinic").attr("id", "direct_diagnosis");
            //            $("#diagnostico_alta_form").find("div[name=hide_diagnosis_clinic]").attr("name", "direct_diagnosis");
            $('#die_form').hide();
            //            $("#die_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_die");
            //            $("#die_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_die");
            $('#diagnosis_form').hide();
            //            $("#diagnosis_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_others");
            //            $("#diagnosis_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_others");
            $(':input[name="global_result"]').val('Curado');
            $("#global_result").attr('disabled', false);

        } else if (outcome_type == 'Transfer') {
            $(':input[name="TransferTo"]').show();
            $('label[for="TransferTo"]').show();
            $(':input[name="die_type"]').hide();
            $('label[for="die_type"]').hide();
            $(':input[name="discharge_type"]').hide();
            $('label[for="discharge_type"]').hide();
            $('#die_form').hide();
            //            $("#die_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_die");
            //            $("#die_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_die");
            $('#diagnosis_form').show();
            //            $("#diagnosis_form").find("#hide_diagnosis_others").attr("id", "direct_diagnosis");
            //            $("#diagnosis_form").find("div[name=hide_diagnosis_others]").attr("name", "direct_diagnosis");
            $('#diagnostico_alta_form').hide();
            //            $("#diagnostico_alta_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_clinic");
            //            $("#diagnostico_alta_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_clinic");
            $(':input[name="global_result"]').val('Indeterminado');
            $("#global_result").attr('disabled', false);
        } else {
            $(':input[name="die_type"]').hide();
            $('label[for="die_type"]').hide();
            $(':input[name="discharge_type"]').hide();
            $('label[for="discharge_type"]').hide();
            $(':input[name="TransferTo"]').hide();
            $('label[for="TransferTo"]').hide();
            $('#die_form').hide();
            //            $("#die_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_die");
            //            $("#die_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_die");
            $('#diagnosis_form').show();
            //            $("#diagnosis_form").find("#hide_diagnosis_others").attr("id", "direct_diagnosis");
            //            $("#diagnosis_form").find("div[name=hide_diagnosis_others]").attr("name", "direct_diagnosis");
            $('#diagnostico_alta_form').hide();
            //            $("#diagnostico_alta_form").find("#direct_diagnosis").attr("id", "hide_diagnosis_clinic");
            //            $("#diagnostico_alta_form").find("div[name=direct_diagnosis]").attr("name", "hide_diagnosis_clinic");
            $(':input[name="global_result"]').val('Curado');
            $("#global_result").attr('disabled', false);

        }
    }
    $(document).ready(function() {
        render();
        //        load_extra_form();
        $("#out_come").change(function() {
            render();
            load_extra_form();
        });
        $("#die_type").change(function() {
            load_extra_form();
        });
    });
</script>

<script type="text/javascript">
    function load_mother_id() {
        mother_id = $("#mother_id").val();
        if (mother_id.length > 0) {
            $.ajax({
                url: "<?php echo base_url() ?>index.php/order_discharge/mother_id/" + mother_id,
                type: "GET"
            }).done(function(response) {

                $("#mother_id_search_result").html(response);
            }).fail(function() {
                alert('Error');
            });
        }
    }
    $("#mother_id_btn_search").click(function() {
        load_mother_id();
    });
</script>