<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form('Complaints');
            $form_generator->form_open_current_url();
            $form_generator->input('*Name', 'Name', $default_Name, 'Name');
            $form_generator->input('*ICPC Code', 'ICPCCode', $default_ICPCCode, 'ICPCCode');
            $form_generator->input('ICD Code', 'ICDCode', $default_ICDCode, 'ICDCode');
            $form_generator->text_area('Remarks', 'Remarks', $default_Remarks, 'Remarks');
            $form_generator->dropdown('isNotify', 'isNotify', array('1' => 'Yes', '0' => 'No'), $default_isNotify);
            $form_generator->dropdown('Active', 'Active', array('1' => 'Yes', '0' => 'No'), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>