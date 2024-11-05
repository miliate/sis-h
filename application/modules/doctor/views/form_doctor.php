<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Doctor'));
            $form_generator->form_open_current_url();
            $form_generator->input('*' . lang('Name'), 'Name', $default_Name, lang('Name'));
            $form_generator->dropdown('*' . lang('Specialty'), 'Especialidade', $dropdown_Especialidade, $default_Especialidade);
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>