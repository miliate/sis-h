<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <?php
                $form_generator = new MY_Form(lang('Injection'));
                $form_generator->form_open_current_url();
                $form_generator->input('*' . lang('Injection Name'), 'name', $default_name, lang('Injection Name'));
                $form_generator->input('*' . lang('Injection Dosage'), 'dosage', $default_dosage, lang('Injection Dosage'));
                $form_generator->input(lang('Injection Route'), 'route', $default_route, lang('Injection Route'));
                $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
                $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>
</div>