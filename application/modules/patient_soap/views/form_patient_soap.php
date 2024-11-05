<div class="container-fluid">
    <div class="row">

        <div class="col-md-2 ">

            <?php
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $ref_id, $pid, $visit_info);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/admission', $admission, $ref_id); //runs the available left menu for preferance 
                    break;
                case 'OPD':
                    echo Modules::run('leftmenu/opd', $ref_id, $pid, $opd_visits_info, $is_discharged);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }
            ?>
        </div>
        <div class="col-md-8">
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
            $form_generator = new MY_Form('Paciente SOAP');
            echo form_error();
            $form_generator->form_open_current_url();
            $form_generator->text_area(lang('Subjective'), 'subjective', $default_subjective);
            $form_generator->text_area(lang('Objective'), 'objective', $default_objective);
            $form_generator->text_area(lang('Assessment'), 'assessment', $default_assessment);
            $form_generator->text_area(lang('Plan'), 'plan', $default_plan);
            //            $form_generator->password('*Second password', 'password2', '', '');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>