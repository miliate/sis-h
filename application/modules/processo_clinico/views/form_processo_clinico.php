<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form('Abrir Processo');
            $form_generator->form_open_current_url();
            $form_generator->input('Data Entrada', 'data_entrada', $default_data, '');
            $form_generator->input('Expede', 'expede', $default_expede, '');
            $form_generator->input('Recebe', 'recebe', $default_recebe, '');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Sim', '0' => 'N&atilde;o'), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
