<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            if ($ref_type == 'EMR') {
                echo Modules::run('leftmenu/emr', $ref_id, $pid, $visit_info);
            } else if ($ref_type == 'OPD') {
                echo Modules::run('leftmenu/opd', $ref_id, $pid, $opd_visits_info, $is_discharged);
            } else {
                echo 'wrong department';
            }

            ?>
        </div>
        <div class="col-md-10 ">
            <?php
            echo Modules::run('patient/banner', $pid);
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('emergency_visit/info', $ref_id);
                    break;
                case 'OPD':
                    echo Modules::run('opd_visit/info', $ref_id);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }

            $form_generator = new MY_Form(lang('Refer to admission'));
            $form_generator->form_open_current_url();
            $form_generator->input(lang('Refer Time'), 'refer_time', $default_time, '', 'readonly');
            $form_generator->input(lang('Complaint'), 'complaint', $default_complaint, '', 'readonly');
            $form_generator->dropdown('Enfermaria', 'ward', $ward_option);
            //  $form_generator->diagnosis(lang('Adm Diagnosis'), 'adm_diagnosis', $default_adm_diagnosis);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
            $form_generator->dropdown(lang('Active'), 'active', array('1' => 'Yes', '0' => 'No'), $default_active);
            $form_generator->input(lang('Doctor'), 'order_confirm_user', $this->session->userdata('name'), $this->session->userdata('name'));
            $form_generator->password(lang('Confirmation Password'), 'order_confirm_password');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#order_confirm_user").prop("disabled", true);
    });
</script>