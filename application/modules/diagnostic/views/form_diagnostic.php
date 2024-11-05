<div class="container-fluid">

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php

            $form_generator = new MY_Form(lang('Diagnostic'));
            $form_generator->form_open_current_url();
            $form_generator->input(lang('Diagnostic Name'), 'name', $default_name, lang('Name'));
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
            $form_generator->dropdown('Active', 'active', array('1' => 'Yes', 0 => 'No'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();

            ?>
        </div>
    </div>

</div>