<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <?php echo Modules::run('patient/banner', $pid); ?>
                <?php
                $form_generator = new MY_Form(lang('Patient Injection'));
                $form_generator->form_open_current_url();
                $form_generator->input(lang('Injection'), 'injection', $default_injection, '', 'readonly');
                $form_generator->input(lang('Dosage'), 'dosage', $default_dosage, '', 'readonly');
                $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
                $form_generator->dropdown(lang('Status'), 'status', array('Pending' => 'Pending', 'Done' => 'Done'), $default_status);
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>