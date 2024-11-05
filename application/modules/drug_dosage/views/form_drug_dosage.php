<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Drugs Dosage');
            $form_generator->form_open_current_url();
            $form_generator->input('*'.lang('Dosage'), 'Dosage', $default_Dosage, lang('Dosage'));
            $form_generator->input('*'.lang('Factor'), 'Factor', $default_Factor, lang('Factor'));
            $form_generator->input(lang('Type'), 'Type', $default_Type, lang('Type'));
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
