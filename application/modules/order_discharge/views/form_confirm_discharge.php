<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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
            ?>
            <?php
            $form_generator = new MY_Form('Confirm Discharge');
            $form_generator->form_open_current_url();
            $form_generator->input(lang('Date and time of discharge'), 'date', $default_date, '', 'readonly');
            $form_generator->input(lang('Outcome'), 'out_come', $default_out_come, '', 'disabled');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
            $form_generator->input(lang('Status'), 'status', $default_status, '', 'disabled');
            $form_generator->checkbox_confirm(lang('Confirm'), 'confirm', $default_confirm);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>