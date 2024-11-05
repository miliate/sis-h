<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('User Group'));
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Name'), 'name', $default_name, lang('Name'));
            $form_generator->dropdown('*' . lang('Department'), 'dep_id', $option_department, $default_dep_id);
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->input(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
            $form_generator->input(lang('Main Menu'), 'main_menu', $default_main_menu, lang('eg. search'));
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>