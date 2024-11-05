<div class="container-fluid" style="margin-top: 55px;">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Radiology Group'));
            $form_generator->form_open_current_url();
            $form_generator->input('*'.lang('Group Name'), 'name', $default_name, lang('Name'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>