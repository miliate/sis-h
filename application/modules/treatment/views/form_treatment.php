<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Nursing Interventions'));
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Name'), 'name', $default_name, lang('Name'));
            $treatment_type_option = array(
                'Treatment' => lang('Treatment'),
                'Procedure' => lang('Procedure'),
                'Care' => lang('Care'),
            );
            $form_generator->dropdown(lang('Type'), 'type', $treatment_type_option, $default_type);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>