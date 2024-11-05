<?php
/**
 * Created by PhpStorm.
 * User: kivegun
 */
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient_pathological_anatomy/info', $pa_id);

            $form_generator = new MY_Form(lang('Cervico-Varginal Cytology Test Result'));
            $form_generator->form_open_current_url();
            $analysis_description_options = array(
                '0' => 'Primeira análise',
                '1' => 'Repetição',
                '2' => 'Investigação'
            );
            $analysis_scrubbed_from = array(
                '0' => 'Exocervix',
                '1' => 'Vagina',
                '2' => 'Endocervix'
            );
            $analysis_sample_taken_by = array(
                '0' => 'Espátula Ayres',
                '1' => 'Cervix Brush',
                '2' => 'Outro'
            );
            $analysis_research_required = array(
                '0' => 'Rotina',
                '1' => 'Hormonal'
            );

            $form_generator->radio(lang('Analysis Description'), 'analysis_description', $analysis_description_options, $default_analysis_description);
            $form_generator->radio(lang('Scrubbed from'), 'scrubbed_from', $analysis_scrubbed_from, $default_scrubbed_from);
            $form_generator->radio(lang('Sample Taken by'), 'sample_taken_by', $analysis_sample_taken_by, $default_sample_taken_by, '', $default_sample_taken_by_info);
            $form_generator->radio(lang('Research Required'), 'research_required', $analysis_research_required, $default_research_required);

            $form_generator->input(lang('Pregnancy'), 'pregnancy', $default_pregnancy, '');
            $form_generator->input(lang('Parity'), 'parity', $default_parity, '');
            $form_generator->dropdown(lang('Are You actually Pregnant?'), 'pregnant', array('1' => 'Yes', '0' => 'No'), $default_pregnant);
            $form_generator->dropdown(lang('Menopause Phase'), 'menopause_phase', array('1' => 'Yes', '0' => 'No'), $default_menopause_phase);
            $form_generator->input(lang('Menopause Phase Info'), 'menopause_phase_info', $default_menopause_phase_info, '');
            $form_generator->input_date(lang('Menstrual Period'), 'menstrual_period', $default_menstrual_period, lang('Date of Last Menstrual Period'));
            $form_generator->dropdown(lang('Smoker'), 'smoker', array('1' => 'Yes', '0' => 'No'), $default_smoker);

            $form_generator->checkboxes_cv(lang('Cervix Appearance'), 'cervix_appearance', $group1_options, $checked1, $default_value);
            $form_generator->checkboxes_cv(lang('Infections With'), 'infections_with', $group2_options, $checked2, $default_value);
            $form_generator->checkboxes_cv(lang('Contraception'), 'contraception', $group3_options, $checked3, $default_value);
            $form_generator->checkboxes_cv(lang('Previous Treatment'), 'previous_treatment', $group4_options, $checked4, $default_value);

            $form_generator->dropdown(lang('Hormone Replacement Therapy'), 'hormone_replacement_therapy', array('1' => 'Yes', '0' => 'No'), $default_hormone_replacement_therapy);
            $form_generator->text_area(lang('Clinical Diagnosis'), 'clinical_diagnosis', $default_clinical_diagnosis, '');

            $form_generator->dropdown(lang('Have Previous Analysis?'), 'previous_PA', array('1' => 'Yes', '0' => 'No'), $default_previous_PA);
            $form_generator->input(lang('Result'), 'result', $default_result, '');

            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();

            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('input[type="checkbox"][value="Contraception_Other"]').click(function(){
            if($(this).prop("checked") == true){
                $('#contraception_other_info').attr('style', 'display: block;');
            }
            else if($(this).prop("checked") == false){
                $('#contraception_other_info').attr('style', 'display: none;');
            }
        });
        $('input[type="checkbox"][value="Tratamento_Anterior_Other"]').click(function(){
            if($(this).prop("checked") == true){
                $('#tratamento_anterior_other_info').attr('style', 'display: block;');
            }
            else if($(this).prop("checked") == false){
                $('#tratamento_anterior_other_info').attr('style', 'display: none;');
            }
        });
        $('input[type="radio"][name="sample_taken_by"][value="2"]').change(function(){
            if($(this).prop("checked") == true){
                $('#sample_taken_by_info').attr('style', 'display: block;');
            }
        });
        $('input[type="radio"][name="sample_taken_by"]').change(function(){
            if($(this).val() !== '2'){
                $('#sample_taken_by_info').attr('style', 'display: none;');
            }
        });
    });
    $(window).load(function() {
        if($('input[type="checkbox"][value="Contraception_Other"]').prop("checked") == true){
            $('#contraception_other_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Contraception_Other"]').prop("checked") == false){
            $('#contraception_other_info').attr('style', 'display: none;');
        }

        if($('input[type="checkbox"][value="Tratamento_Anterior_Other"]').prop("checked") == true){
            $('#tratamento_anterior_other_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Tratamento_Anterior_Other"]').prop("checked") == false){
            $('#tratamento_anterior_other_info').attr('style', 'display: none;');
        }

        if($('input[type="radio"][name="sample_taken_by"][value="2"]').prop("checked") == true){
            $('#sample_taken_by_info').attr('style', 'display: block;');
        }
        else if($('input[type="radio"][name="sample_taken_by"][value="2"]').prop("checked") == false){
            $('#sample_taken_by_info').attr('style', 'display: none;');
        }
    });

</script>


