<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<?php require("charts/liquido_view.php") ?>
<?php require("charts/line_graph.php") ?>
<?php require("charts/dilatacao_view.php") ?>
<?php require("charts/contractions.php") ?>
<?php require("charts/ocitocina_view.php") ?>
<?php require("charts/temperature_view.php")?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
                echo Modules::run('patient/banner', $pid);
            ?>
            <?php
                $form_generator = new MY_FORM(lang('Birth'));
                $form_generator->form_open_current_url();
            ?>
            <?php
                $form_generator->input_date_and_time(lang('Entrance'),"entrance",$default_entrance, '');
                $form_generator->input_date_and_time(lang('Discharge'),"discharge",$default_discharge, '');
                $form_generator->input(lang('Transfer'),"transfer", $default_transfer,'');
                $form_generator->input(lang('Reason'),"reason", $default_reason,'');
            ?>
            <hr/><h4><?php echo lang('Obstetric History'); ?></h4>
            <?php
                $form_generator->dropdown('','obstetric_history',array(
                    "Gestation" => lang('Gestation'),
                    "Deliveries" => lang('Deliveries'),
                    "Abortions" => lang('Abortions'),
                    "Dead" => lang('Dead'),
                    "Alive" => lang('Alive'),
                    "Deceased" => lang('Deceased'),
                    "Deceased in 1st week" => lang('Deceased in 1st week'),
                    "Current Living Children" => lang('Current Living Children')
                ));

                $form_generator->checkboxes(lang('Previous Difficult Births'),'prev_difficult_births',array(
                    "Cesarean" => lang('Cesarean'),
                    "Forceps" => lang('Forceps'),
                    "Vacuum Extraction" => lang('Vacuum Extraction'),
                ));
            ?>
            <?php
                $form_generator->input(lang('HIV')." ".lang('Result'),"hiv_result", $default_hiv_result,'');
                $form_generator->input(lang('HIV')." ".lang('Prophylaxis'),"hiv_prophylaxis", $default_hiv_prophylaxis,'');
                $form_generator->input(lang('HIV')." ".lang('TARV'),"hiv_tarv", $default_hiv_tarv,'');
                $form_generator->input_date(lang('Last Menstrual Period'),"lmp", $default_lmp,'');
                $form_generator->input_date(lang('Conception Date'),"conception_date", $default_conception_date,'');
                $form_generator->input_date(lang('Estimated Due Date'),"edd", $default_edd,'');
            ?>
            <?php
                $form_generator->input_with_unit(lang('Months'),lang('Pregnancy Duration'),"pregnancy_duration_months",$default_pregnancy_duration_months, '');
                $form_generator->input_with_unit(lang('Weeks'),'',"pregnancy_duration_weeks",$default_pregnancy_duration_weeks, '');
            ?>
            <hr/><h4><?php echo lang('General Examination'); ?></h4>
            <?php
                $form_generator->input_with_unit("m",lang('Height Below 1.5m'),"height_below_1_5m", $default_height_below_1_5m, '');
                $form_generator->input_with_unit("kg",lang('Weight'),"weight",$default_weight, '');
                $form_generator->input_with_unit("mmHg", lang('Blood Pressure')." ".lang('Maximum'),"blood_pressure_max", $default_blood_pressure_max, '');
                $form_generator->input_with_unit("mmHg", lang('Blood Pressure')." ".lang('Minimum'),"blood_pressure_min", $default_blood_pressure_min, '');
                $form_generator->input(lang('Proteinuria'),"proteinuria", $default_proteinuria, '');
                $form_generator->input(lang('Edema'),"edema", $default_edema, '');
                $form_generator->dropdown(lang('Mucous Membranes'),"mucous_membranes",array(
                    "Pale" => lang('Pale'),
                    "Pink" => lang('Pink'),
                ));
                $form_generator->input_with_unit("°c",lang('Temp.'),"temp", $default_temp, '');
                $form_generator->input_with_unit("bpm",lang('Pulse'),"pulse", $default_pulse, '');
            ?>
            <hr/><h4><?php echo lang('Obstetric Examination'); ?></h4>
            <?php
                $form_generator->input_with_unit(lang("in 10 minutes"),lang("Palpation"), "palpation", $default_palpation, '');
                $form_generator->dropdown(lang('Uterine TONE'),"uterine_tone",array(
                    "Hypertonic" => lang('Hypertonic'),
                    "Normal" => lang('Normal'),
                    "Hypotonic" => lang('Hypotonic'),
                ));
                $form_generator->dropdown(lang('Fetal Back'),"fetal_back",array(
                    "on the right" => lang('on the right'),
                    "on the left" => lang('on the left'),
                ));
                $form_generator->dropdown(lang('Engaged in pelvis'),"engaged_in_pelvis",array(
                    "Mobile" => lang('Mobile'),
                    "Engaged" => lang('Engaged'),
                ));
                $form_generator->input_with_unit("bpm",lang('Fetal Heart Rate'),"fetal_heart_rate", $default_fetal_heart_rate, '');
                $form_generator->input_with_unit("cm",lang('Measurement: Uterine height'),"uterine_height", $default_uterine_height, '');
                $form_generator->dropdown(lang('Cervix'),"cervix",array(
                    "Soft" => lang('Soft'),
                    "Stiff" => lang('Stiff'),
                    "Thick" => lang('Thick'),
                    "Fine" => lang('Fine'),
                ));
                $form_generator->input(lang('Absent'),"cervix_absent", $default_cervix_absent, '');
                $form_generator->input(lang('Formed'),"cervix_formed", $default_cervix_formed, '');
                $form_generator->input(lang('Dilation'),"cervix_dilation", $default_cervix_dilation, '');
                $form_generator->dropdown(lang('AMNIOTIC FLUID'),"amniotic_fluid",array(
                    "Clear" => lang('Clear'),
                    "Turbid" => lang('Turbid'),
                    "MECONIUM" => lang('MECONIUM'),
                ));
                $form_generator->input(lang('G.C.S.'),"gcs", $default_gcs, '');
                $form_generator->input_date_and_time('','gcs_time', $default_gcs_time, '');
                $form_generator->dropdown(lang('Pelvis'),"pelivis",array(
                    "Compatible" => lang('Compatible'),
                    "Incompatible" => lang('Incompatible'),
                ));
                $form_generator->input(lang('Presentation'),"presentation", $default_presentation, '');
            ?>
            <hr/>
            <?php
                $form_generator->text_area(lang('DIAGNOSIS'),"diagnosis", $default_diagnosis, '');
                $form_generator->dropdown(lang('PROGNOSIS'),"prognosis",array(
                    "Good" => lang('Good'),
                    "Uncertain" => lang('Uncertain'),
                    "Bad" => lang('Bad'),
                ));
            ?>
            <br>
            <?php
            $data = "";
            lineGraph("focofetal", $data, "Foco Fetal", 0, 25, 1, 100, 180, 10);
            echo $data;
            liquido_view("liquido")
            ?>
            <br>

            <h4>DILATACAO</h4>
            <?php 
            $data2 = "";
            dilatacao_view("dilatacao",$data2) ?>
            <br>

            <h4>CONTRACTIONS em 10 MINUTOS</h4>
            <?php
            $data3 = "";
            contractions("contractions") ?>
            <br>

            <h4>OCITOCINA</h4>
            <?php
            $data4 = "";
            ocitocina_view("ocitocina") ?>
            <br>

            <h4>TENSAO ARTERIAL</h4>
            <?php
            $data5 = "";
            lineGraph("tensaoarterial", $data5, "Pulso", 0, 25, 0.5, 60, 180, 10);
            temperature_view("temperature");
            ?>
            <br>

            <?php $form_generator->button_submit_reset($id); ?>
        </div>
    </div>
</div>