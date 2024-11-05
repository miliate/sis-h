<div class="container-fluid">
    <div class="row">

        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>

        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);

            $form_generator = new MY_Form(lang('Contact Person'));
            $form_generator->form_open_current_url();
            $form_generator->checkbox_confirm(lang('it does not have'), 'has_contact', $default_has_contact);
            $form_generator->input('*' . lang('Name'), 'contact_name', $default_contact_name, '');//sinalizando obrigatoriedade

            $kinship_options = array(
                'Pai' => 'Pai',
                'Mãe' => 'Mãe',
                'Avo' => 'Avo',
                'Filho/a' => 'Filho/a',
                'Neto/a' => 'Neto/a',
                'Irmão/ã' => 'Irmão/ã',
                'Esposo/a' => 'Esposo/a',
                'Tio/a' => 'Tio/a',
                'Cunhado/a' => 'Cunhado/a',
                'Concunhado/a' => 'Concunhado/a',
                'Genro' => 'Genro',
                'Sogro/a' => 'Sogro/a',
                'Nora' => 'Nora',
                'Outro' => 'Outro',
            );
            $form_generator->dropdown(lang('Degree of kinship'), 'contact_kinship', $kinship_options, $default_contact_kinship, '');
            $form_generator->input('' . lang('Address'), 'contact_address', $default_contact_address, '');
            $form_generator->input(lang('Working place'), 'contact_working_place', $default_contact_working, '');
            $form_generator->input(lang('Telephone'), 'contact_telephone', $default_contact_telephone, '');
            $form_generator->input('*' . lang('Email'), 'contact_email', $default_contact_email, '');
            $form_generator->button_submit_reset($pid);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>