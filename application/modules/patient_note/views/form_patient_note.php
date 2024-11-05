<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <?php $form_generator = new MY_Form(lang('Patient Note'));
            $form_generator->get_nursing_notes_tab($pid, $ref_id);

            $form_generator = new MY_Form(lang('Patient Note'));
            $user_group_name = $this->session->userdata('user_group_name');
            $user_department = $this->session->userdata('department');
            if ((strpos(strtolower($user_group_name), 'doctor') !== false) && ($user_department == "ADM")) {
                $form_generator->get_nursing_note_menu($pid, $ref_id);
            }
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator->form_open_current_url();

            $form_generator->input_date_and_time(lang('Date and Time'), 'datetime', $default_datetime, '', '', 'type="datetime-local"');
            $form_generator->input(lang('User Data'), 'user_data', $user_data['title'] . ' ' . $user_data['name'] . ' ' . $user_data['other_name'], '', 'readonly');
            $form_generator->input(lang('Type'), 'type', $default_type, '', 'readonly');
            $form_generator->text_area('*' . lang('Note'), 'note', $default_note, lang('Patient Complaint Note'));
            echo '<hr>';
            echo '<p style="font-weight: bold;">' . lang('Vital Signs') . '</p>';
            $form_generator->input_number('*' . lang('Temperature in *C'), 'temperature', $default_temperature, '');
            $form_generator->input_number('*' . lang('Heart rate'), 'heart_rate', $default_heart_rate);
            $form_generator->input_number('*' . lang('Respiratory frequency'), 'respiratory_frequency', $default_respiratory_frequency);
            $form_generator->input_number(lang('Oxygen Saturation (%)'), 'oxygen_saturation', $default_oxygen_saturation);
            echo '<hr>';
            echo '<p style="font-weight: bold;">' . lang('Blood pressure') . '</p>';
            $form_generator->input_number(lang('Systolic'), 'sys_bp', $default_sys_bp, '');
            $form_generator->input_number(lang('Diastolic'), 'diast_bp', $default_diast_bp, '');
            $options = array(
                'radial' => 'Radial',
                'braquial' => 'Braquial',
                'carotideo' => 'Carotídeo',
                'popliteo' => 'Poplíteo',
                'femural' => 'Femural',
                'pedioso' => 'Pedioso',
            );
            $form_generator->dropdown(lang('Pulse'), 'pulse', $options, $default_pulse);
            $form_generator->input_number(lang('Pulse Value'), 'pulse_value', $default_pulse_value);
            $pulse_characteristics = array(
                'fino' => 'Fino',
                'cheio' => 'Cheio',
            );
            $form_generator->dropdown(lang('Pulse Characteristics'), 'pulse_characteristics', $pulse_characteristics, $default_pulse_characteristics);
            $form_generator->text_area(lang('Note'), 'vital_signs_note', $default_vital_signs_note, lang('Vital Signs Note'));
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);
            echo '<hr>';

            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>