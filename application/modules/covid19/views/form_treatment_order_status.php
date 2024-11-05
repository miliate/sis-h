<div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <?php
                echo Modules::run('patient/banner', $pid);
                ?>
                <?php
                $form_generator = new MY_Form(lang('Update Treatment Order'));
                $form_generator->form_open_current_url();
                $form_generator->input(lang('Treatment'), 'treatment', $default_treatment, '', 'readonly');
                $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
                $form_generator->dropdown(lang('Status'), 'status', array('Pending' => 'Pending', 'Done' => 'Done'), $default_status);
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>

<?php
echo Modules::run('template/footer');
?>