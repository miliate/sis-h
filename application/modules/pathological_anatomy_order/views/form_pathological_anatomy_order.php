<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
        </div>
        <div class="col-md-8">
            <?php
            echo Modules::run('patient/banner', $pid);
            echo Modules::run('patient_pathological_anatomy/info', $pa_id);

            $form_generator = new MY_Form(lang('Order Pathological Anatomy Test'));
            $form_generator->form_open_current_url();
            $priority_options = array(
                'Normal' => 'Normal',
                'Urgent' => 'Urgent'
            );
            $form_generator->dropdown(lang('Priority'), 'priority', $priority_options, 'Normal');
            $form_generator->dropdown('Sample Type', 'sample_type', $dropdown_sample_type, $default_sample_type);
            $form_generator->dropdown('Doctor in Charge', 'doctor_in_charge', $dropdown_doctor, $default_doctor);
            $form_generator->dropdown('Doctor who Requested', 'doctor_who_requested', $dropdown_doctor, $default_doctor);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            $form_generator->button_submit_reset(0);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

