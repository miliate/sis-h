<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Drug'));
            $form_generator->form_open_current_url();

            $form_generator->input('*' . lang('Name'), 'name', $default_name, '');
            $form_generator->input('*' . lang('Group'), 'group', $default_group, lang('Group'));
            //            $form_generator->input('Sub Group', 'sub_group', $default_sub_group, 'sub_group');
            //            $form_generator->input('Formulation', 'formulation', $default_formulation, 'formulation');
            //            $form_generator->input('Dose', 'dose', $default_dose, 'dose');
            //            $form_generator->input('Default Number', 'default_num', $default_default_num, 'default_num');
            //            $form_generator->input('Default Timing', 'default_timing', $default_default_timing, 'default_timing');
            //            $form_generator->input('Count', 'count', $default_count, 'count');
            $form_generator->input(lang('National Form Code'), 'national_code', $default_national_code, lang('National Form Code'));

            // $form_generator->input('Sub Group', 'sub_group', $default_sub_group, 'sub_group');

            $form_generator->dropdown(lang('Pharmaceutical Form'), 'pharmaceutical_form', $default_pharmaceutical_form, lang('Pharmaceutical Form'));


            $form_generator->input(lang('Dosage'), 'dosage', $default_dosage, lang('dosage'));

            $form_generator->input(lang('Presentation'), 'presentation', $default_presentation, lang('Presentation'));

            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>