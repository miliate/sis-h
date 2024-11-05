<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Drug Frequency');
            $form_generator->form_open_current_url();
            $form_generator->input('*Frequency', 'Frequency', $default_Frequency, 'Frequency');
            $form_generator->input('*Factor', 'Factor', $default_Factor, 'Factor');
            $form_generator->dropdown('Active', 'Active', array('1' => 'Yes', '0' => 'No'), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>