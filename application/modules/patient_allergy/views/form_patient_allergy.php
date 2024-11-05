<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div> 
        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form(lang('Allergy'));
            $form_generator->form_open_current_url();

            $form_generator->input('*'. lang('Name'), 'name', $default_name, lang('Name of allergy'));
            $form_generator->dropdown(lang('Status'), 'status', array('Passado' => lang('Past'), 'Actual' => lang('Current')), $default_status);
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->input(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));

            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>


</div>