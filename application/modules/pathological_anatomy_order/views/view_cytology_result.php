<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php echo Modules::run('leftmenu/print_cytology', $cytology_id); //runs the available left menu for preferance ?>
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient_pathological_anatomy/info', $pa_id);

            $form_generator = new MY_Form(lang('Cytology Test Result'));
            $form_generator->form_open_current_url();
            $Yes_No_options = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );

            $form_generator->checkboxes_pa(lang('CYTOLOGY OF LIQUIDS'), 'cytology_liquids', $group1_options, $checked1, $default_value, 1, 'onclick="return false;" readonly');
            $form_generator->checkboxes_pa(lang('PAAF'), 'paaf', $group2_options, $checked2, $default_value, 2, 'onclick="return false;" readonly');

            //            $form_generator->input(lang('Ascitic Liquid'), 'ascitic_liquid', $default_ascitic_liquid, '', 'readonly');
            //            $form_generator->input(lang('Pleural Fluid'), 'pleural_fluid', $default_pleural_fluid, '', 'readonly');
            //            $form_generator->input(lang('Washes'), 'washes', $default_washes, '');
            //            $form_generator->input(lang('Washes Info'), 'washes_info', $default_washes_info, '');
            //            $form_generator->input(lang('Pericardial Fluid'), 'pericardial_fluid', $default_pericardial_fluid, '');
            //            $form_generator->input(lang('Urine'), 'urine', $default_urine, '');
            //            $form_generator->input(lang('Expectoration'), 'expectoration', $default_expectoration, '');
            //            $form_generator->dropdown(lang('LCR'), 'LCR', $previous_PA_options, $default_LCR);
            //            $form_generator->input(lang('Others'), 'others_liquid', $default_others_liquid, '');
            //            $form_generator->text_area(lang('Others (specify)'), 'others_liquid_info', $default_others_liquid_info, '');
            //            $form_generator->text_area(lang('Clinical Diagnosis'), 'clinical_diagnosis_liquid', $default_clinical_diagnosis_liquid, '');
            //            $form_generator->text_area(lang('Breast (Lump)'), 'breast', $default_breast, '');
            //            $form_generator->input(lang('Nipple Discharge'), 'nipple_discharge', $default_nipple_discharge, '');
            //            $form_generator->input(lang('Thyroid'), 'thyroid', $default_thyroid, '');
            //            $form_generator->input(lang('Salivary Gland'), 'salivary_gland', $default_salivary_gland, '');
            //            $form_generator->input(lang('Ganglion'), 'ganglion', $default_ganglion, '');
            //            $form_generator->input(lang('Ganglion (specify location)'), 'ganglion_info', $default_ganglion_info, '');
            //            $form_generator->input(lang('Soft Tissues'), 'soft_tissues', $default_soft_tissues, '');
            //            $form_generator->input(lang('Soft tissues (specify location)'), 'soft_tissues_info', $default_ganglion_info, '');
            //            $form_generator->dropdown(lang('Others'), 'others_PAAF', $previous_PA_options, $default_others_PAAF);
            //            $form_generator->input(lang('Others (specify)'), 'others_PAAF_info', $default_others_PAAF_info, '');
            //            $form_generator->text_area(lang('Clinical Information / Diagnosis'), 'clinical_diagnosis_PAAF', $default_clinical_diagnosis_PAAF, '');
            $form_generator->dropdown(lang('Have Previous Analysis?'), 'previous_PA', array('1' => 'Yes', '0' => 'No'), $default_previous_PA, 'disabled');
            $form_generator->input(lang('Result'), 'result', $default_result, '', 'readonly');

            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '', 'readonly');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active, 'disabled');
            $form_generator->form_close();

            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).load(function() {
        if($('input[type="checkbox"][value="Washes"]').prop("checked") == true){
            $('#washes_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Washes"]').prop("checked") == false){
            $('#washes_info').attr('style', 'display: none;');
        }

        if($('input[type="checkbox"][value="Others_Liquid"]').prop("checked") == true){
            $('#others_liquid_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Others_Liquid"]').prop("checked") == false){
            $('#others_liquid_info').attr('style', 'display: none;');
        }

        if($('input[type="checkbox"][value="Ganglion"]').prop("checked") == true){
            $('#ganglion_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Ganglion"]').prop("checked") == false){
            $('#ganglion_info').attr('style', 'display: none;');
        }

        if($('input[type="checkbox"][value="Soft_Tissues"]').prop("checked") == true){
            $('#soft_tissues_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Soft_Tissues"]').prop("checked") == false){
            $('#soft_tissues_info').attr('style', 'display: none;');
        }

        if($('input[type="checkbox"][value="Others_PAAF"]').prop("checked") == true){
            $('#others_paaf_info').attr('style', 'display: block;');
        }
        else if($('input[type="checkbox"][value="Others_PAAF"]').prop("checked") == false){
            $('#others_paaf_info').attr('style', 'display: none;');
        }
    });

</script>