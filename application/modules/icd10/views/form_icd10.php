<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-info">
                <?php
                $form_generator = new MY_Form(lang('ICD10'));
                $form_generator->form_open_current_url();
                $form_generator->input('*'.lang('Code'), 'Code', $default_code, lang('Code'));
                $form_generator->input('*'.lang('Name'), 'Name', $default_name, lang('Name'));
                $form_generator->dropdown(lang('isNotify'), 'isNotify', array('1' => lang('Yes'), '0' => lang('No')), lang('isNotify'));
                $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
                $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
                $form_generator->input('*' . lang('Code'), 'Code', $default_code, lang('Code'));
                $form_generator->input('*' . lang('Name'), 'Name', $default_name, lang('Name'));
                $form_generator->dropdown(lang('isNotify'), 'isNotify', array('1' => lang('Yes'), '0' => lang('No'), lang('isNotify')));
                $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Remarks'));
                $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No'), $default_active));
                $form_generator->button_submit_reset();
                $form_generator->form_close();
                ?>
            </div>
        </div>
    </div>