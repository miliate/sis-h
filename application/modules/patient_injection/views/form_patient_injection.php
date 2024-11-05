<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            switch ($ref_type) {
                case 'OPD':
                    echo Modules::run('leftmenu/opd', $ref_id, $pid, $opd_visits_info, $is_discharged);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/admission', $admission, $ref_id);
                    break;
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $pid, $ref_id, $visit_info);
                    break;
            }
            ?>
        </div>
        <div class="col-md-10">
            <?php
            echo Modules::run('patient/banner', $pid);
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('emergency_visit/info', $ref_id);
                    break;
                case 'ADM':
                    echo Modules::run('admission/info', $ref_id);
                    break;
                case 'OPD':
                    echo Modules::run('opd_visit/info', $ref_id);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }
            $form_generator = new MY_Form(lang('Patient Injection'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown(lang('Injection'), 'injection', $injection_options, $default_injection);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            //            $form_generator->dropdown(lang('Doctor'), 'order_confirm_user', Modules::run('order_confirmation/get_doctor'), $this->session->userdata('uid'));
            $form_generator->password('*' . lang('Second Password'), 'password2', '', '');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>