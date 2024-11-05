<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Lab Test'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown(lang('Department'), 'department', $department_options, $default_department);
            $form_generator->dropdown(lang('Group'), 'group', $group_options, $default_group);
            $form_generator->input('*'.lang('Name'), 'name', $default_name, lang('Name'));
            $form_generator->input(lang('Abrev'), 'Abrev', $default_abrev, lang('Abrev'));
            $form_generator->input(lang('Unit'), 'Unit', $default_unit, lang('Unit'));
            $form_generator->input(lang('Ref Value'), 'ref_value', $default_ref_value, '');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>