<?php
/**
 * Created by PhpStorm.
 * User: kivegun
 * Date: 1/7/21
 * Time: 5:25 PM
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

            $form_generator = new MY_Form(lang('Biopsy Test Result'));
            $form_generator->form_open_current_url();
            $previous_PA_options = array(
                'Yes' => 'Yes',
                'No' => 'No'
            );
            $form_generator->input(lang('Result Status'), 'status', $default_status, '', 'readonly');
            $form_generator->input(lang('Priority'), 'priority', $default_priority, '', 'readonly');
            $form_generator->input(lang('Kind of Product to Analyze'), 'kind_of_product', $default_kind_of_product, '');
            $form_generator->input(lang('Collection Method'), 'collection_method', $default_collection_method, '');
            $form_generator->input(lang('Fixed On'), 'fixed_on', $default_fixed_on, '');
            $form_generator->input(lang('Wound Centre'), 'wound_centre', $default_wound_centre, '');
            $form_generator->input(lang('Exact place on where the fragment was removed'), 'extracted', $default_extracted, '');
            $form_generator->dropdown(lang('Do you have previous PA test?'), 'previous_pa', $previous_PA_options, $default_previous_pa);
            $form_generator->input(lang('If the answer is YES, indicate its previous Sample ID and the Result of Exam'), 'old_result', $default_old_result, '');
            $form_generator->text_area(lang('Result for Macroscopic Exam'), 'macroscopic', $default_macroscopic, '');
            $form_generator->text_area(lang('Result for Microscopic Exam'), 'microscopic', $default_microscopic, '');
            $form_generator->text_area(lang('Result for Pathological Anatomy Diagnosis'), 'pa_diagnosis', $default_pa_diagnosis, '');
            $form_generator->input(lang('Topography'), 'topography', $default_topography, '');
            $form_generator->input(lang('Morphology'), 'morphology', $default_morphology, '');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {
        previous_pa = $("#previous_pa").val();
        if (previous_pa=='Yes') {
            $(':input[name="old_result"]').show();
            $('label[for="old_result"]').show();
//            console.log(cost_val);
        } else {
            $(':input[name="old_result"]').hide();
            $('label[for="old_result"]').hide();
        }
    });

    $('#previous_pa').change(function () {
        previous_pa = $("#previous_pa").val();
        if (previous_pa=='Yes') {
            $(':input[name="old_result"]').show();
            $('label[for="old_result"]').show();
        } else {
            $(':input[name="old_result"]').hide();
            $('label[for="old_result"]').hide();
        }
    });

</script>


