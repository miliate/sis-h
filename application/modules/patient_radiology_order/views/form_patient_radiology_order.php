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
        <div class="col-md-8 col-md-offset-1">
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <?php echo lang('Radiology Order') ?>
                </div>
                <div class="panel-body">
                    <form action="" method="post" role="form" class="form-horizontal">
                        <?php
                        $form_generator = new MY_Form('');
                        echo form_error('radiology');
                        if ($radiology_groups) {
                            foreach ($radiology_groups as $group_name => $radiology_group) {
                                $radiology_options = array();
                                foreach ($radiology_group as $radiology) {
                                    $radiology_options[$radiology->radiology_id] = $radiology->name;
                                }
                                $form_generator->checkboxes($group_name, 'radiology', $radiology_options, array());
                            }

                            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
                            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks);
                            $form_generator->input_date_future(lang('Exam Date'), 'exam_date', $default_exam_date, lang('Exam scheduling'));
                            $form_generator->button_submit_reset();
                            $form_generator->form_close();
                        } else {
                            echo '<div id="message1" class="alert alert-danger">';
                            echo '    <button type="button" class="close" data-dismiss="alert" onclick="window.location.href=\'';
                            echo base_url('index.php/emergency_visit/view/' . $ref_id);
                            echo '\'">&times;</button>';
                            echo '    <span id="message_text">' . lang('No Tests Available') . '</span>';
                            echo "<br>";
                            echo '    <span id="message_text">' . lang('Register the tests in the database') . '</span>';
                            echo '</div>';
                        }
                        //                        $form_generator->dropdown('Oder Confirmation Doctor', 'order_confirm_user', Modules::run('order_confirmation/get_doctor'), $this->session->userdata('uid'));
                        //                        $form_generator->password('Order Confirmation Password', 'order_confirm_password');
                        ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>