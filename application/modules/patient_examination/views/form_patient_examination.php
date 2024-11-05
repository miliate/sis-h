<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div> 
        <div class="col-md-8 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $pid);
            ?>
            <?php
            $form_generator = new MY_Form(lang('Patient Examination'));
            $form_generator->form_open_current_url();
            if (!$is_edit) {
                $form_generator->input_date_and_time(lang('Examination Date'), 'examination_date', $default_exam_date);
            }
            $eyeOpeningOptions = array(
                '1' => '1 - Nenhuma',
                '2' => '2 - Ao estímulo de pressão',
                '3' => '3 - Ao estímulo sonoro',
                '4' => '4 - Espontânea',
            );

            $verbalResponseOptions = array(
                '1' => '1 - Nenhuma',
                '2' => '2 - Verbaliza sons',
                '3' => '3 - Verbaliza palavras soltas',
                '4' => '4 - Confusa',
                '5' => '5 - Orientada',
            );

            $motorResponseOptions = array(
                '1' => '1 - Nenhuma',
                '2' => '2 - Extensão anormal',
                '3' => '3 - Flexão anormal',
                '4' => '4 - Flexão normal',
                '5' => '5 - Localiza estímulo',
                '6' => '6 - Obedece comandos',
            );

            $estadoGeralOptions = array(
                'Bom' => 'Bom',
                'Satisfatório' => 'Satisfatório',
                'Moderado' => 'Moderado',
                'Mau' => 'Mau',
                'Grave' => 'Grave'
            );

            $biotipoOptions = array(
                'Ectomorfo' => 'Ectomorfo',
                'Mesomorfo' => 'Mesomorfo',
                'Endomorfo' => 'Endomorfo'
            );

            $form_generator->input(lang('Level of Consciousness'), 'glasgow_score', '', '', 'readonly', 'form-control-static');

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->dropdown(lang('General Condition'), 'general_status', $estadoGeralOptions, $default_general_status, 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->dropdown(lang('Verbal Response'), 'verbal_response', $verbalResponseOptions, $default_verbal_response, 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input_number(lang('Weight in KG'), 'weight', $default_weight, 'eg. 50', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->dropdown(lang('Eye Opening'), 'eye_opening', $eyeOpeningOptions, $default_eye_opening, 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input_number(lang('Height in M'), 'height', $default_height, 'eg. 1.70', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->dropdown(lang('Motor Response'), 'motor_response', $motorResponseOptions, $default_motor_response, 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('IMC'), 'imc', $default_imc, ' Kg/m²', 'style="margin-left: 15%;" readonly');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->dropdown(lang('Biotype'), 'biotipo', $biotipoOptions, $default_biotype, 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<hr>';
            echo '<p style="font-weight: bold;">' . lang('Vital Signs') . '</p>';
            $form_generator->input(lang('Temperature in *C'), 'temperature', $default_temperature);

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input_number(lang('Heart rate'), 'heart_rate', $default_heart_rate, '', 'style="margin-left: 15%;"');
            echo '</div>';

            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input_number(lang('Respiratory frequency'), 'respiratory_frequency', $default_respiratory_frequency, '', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<hr>';
            echo '<p style="font-weight: bold;">' . lang('Blood Pressure') . '</p>';

            $form_generator->input(lang('Systolic'), 'sys_bp', $default_sys_bp);
            $form_generator->input(lang('Diastolic'), 'diast_bp', $default_diast_bp);

            $options = array(
                'radial' => 'Radial',
                'braquial' => 'Braquial',
                'carotideo' => 'Carotídeo',
                'popliteo' => 'Poplíteo',
                'femural' => 'Femural',
                'pedioso' => 'Pedioso',
            );
            $form_generator->dropdown(lang('pulse'), 'pulse', $options, $default_pulse);
            
            $pulse_characteristics = array(
                'fino' => 'Fino',
                'cheio' => 'Cheio',
            );
            $form_generator->dropdown(lang('Pulse Characteristics'), 'pulse_characteristics', $pulse_characteristics, $default_pulse_characteristics);

            $form_generator->input_number(lang('Pulse Value'), 'pulse_value', $default_pulse_value);

            echo '<hr>';
            echo '<p style=" font-weight: bold;">' . lang('Regional Physical Examination') . '</p>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Skin'), 'skin', $default_skin, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Mucous'), 'mucous', $default_mucous, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Body hair'), 'body_hair', $default_body_hair, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Nails'), 'nails', $default_nails, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Skull'), 'skull', $default_skull, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Hair'), 'hair', $default_hair, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Paranasal Sinuses'), 'paranasal_sinuses', $default_paranasal_sinuses, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Eyes'), 'eyes', $default_eyes, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Ears'), 'ears', $default_ears, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Nose'), 'nose', $default_nose, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Mouth'), 'mouth', $default_mouth, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Neck'), 'neck', $default_neck, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Thorax'), 'thorax', $default_thorax, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Respiratory Examination'), 'respiratory_exam', $default_respiratory_exam, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            echo '<div class="row">';
            echo '<div class="col-sm-1"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Cardiovascular examination'), 'cardiovascular_exam', $default_cardiovascular_exam, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '<div class="col-sm-2"></div>';
            echo '<div class="col-md-4">';
            $form_generator->input(lang('Abdomen'), 'abdomen', $default_abdomen, '(' . lang('Comment') . ')', 'style="margin-left: 15%;"');
            echo '</div>';
            echo '</div>';

            $form_generator->input(lang('Lower limbs'), 'lower_limbs', $default_lower_limbs, '(' . lang('Comment') . ')');

            echo '<hr>';
            $form_generator->input(lang('Neurological Examination'), 'neurological_exams', $default_neurological_exams, '(' . lang('Comment') . ')'); //precisa ser movido pra cima

            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks);
            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);

            $form_generator->password('*' . lang('Second password'), 'password2', '', '');

            if (!$is_edit) {
                $form_generator->button_submit_reset($id);
            } else {
                // Botões de submit e reset
                $form_generator->button_back();
            }
            $form_generator->form_close();
            ?>
        </div>
    </div>

</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to calculate the Glasgow Coma Scale score
        function calculateGlasgow() {
            var eyeOpening = parseInt(document.querySelector("select[name='eye_opening']").value) || 0;
            var verbalResponse = parseInt(document.querySelector("select[name='verbal_response']").value) || 0;
            var motorResponse = parseInt(document.querySelector("select[name='motor_response']").value) || 0;

            var totalScore = eyeOpening + verbalResponse + motorResponse;
            document.getElementById("glasgow_score").value = totalScore;
        }

        // Calculate score initially
        calculateGlasgow();

        // Attach event listeners to dropdowns
        document.querySelector("select[name='eye_opening']").addEventListener("change", calculateGlasgow);
        document.querySelector("select[name='verbal_response']").addEventListener("change", calculateGlasgow);
        document.querySelector("select[name='motor_response']").addEventListener("change", calculateGlasgow);
    });


    document.addEventListener("DOMContentLoaded", function() {
        function calcularIMC() {
            var peso = parseFloat(document.getElementsByName("weight")[0].value);
            var alturaCm = parseFloat(document.getElementsByName("height")[0].value);
            var altura = alturaCm / 100;

            if (!isNaN(peso) && !isNaN(altura) && altura > 0) {
                var imc = peso / (altura * altura);
                document.getElementsByName("imc")[0].value = imc.toFixed(2); 
            } else {
                document.getElementsByName("imc")[0].value = ''; 
            }
        }

        document.querySelectorAll("input[name='weight'], input[name='height']").forEach(function(element) {
            element.addEventListener("input", calcularIMC);
        });
    });



    document.addEventListener("DOMContentLoaded", function() {
        // Retrieve the edit mode status from PHP variable
        var isEdit = <?php echo json_encode($is_edit); ?>;

        // Function to toggle input fields based on edit mode
        function toggleFormElements(selector, disabled) {
            var elements = document.querySelectorAll(selector);
            elements.forEach(function(element) {
                if (element.tagName === 'INPUT') {
                    element.disabled = disabled;
                } else if (element.tagName === 'TEXTAREA') {
                    element.disabled = disabled;
                } else if (element.tagName === 'SELECT') {
                    element.disabled = disabled;
                }
            });
        }

        // List of form field selectors
        var inputSelectors = [
            '#examination_date',
            '#glasgow_score',
            '#eye_opening',
            '#verbal_response',
            '#motor_response',
            '#general_status',
            '#biotipo',
            '#weight',
            '#height',
            '#imc',
            '#temperature',
            '#heart_rate',
            '#respiratory_frequency',
            '#sys_bp',
            '#diast_bp',
            '#pulse',
            '#skin',
            '#mucous',
            '#body_hair',
            '#nails',
            '#skull',
            '#hair',
            '#paranasal_sinuses',
            '#eyes',
            '#ears',
            '#nose',
            '#mouth',
            '#neck',
            '#thorax',
            '#respiratory_exam',
            '#cardiovascular_exam',
            '#abdomen',
            '#lower_limbs',
            '#neurological_exams',
            '#password2',
            '#active',
            '#remarks'
        ];

        var textAreaSelectors = [
            '#remarks'
        ];

        // Apply disabled state to all input fields and text areas
        inputSelectors.forEach(function(selector) {
            toggleFormElements(selector, isEdit);
        });

        textAreaSelectors.forEach(function(selector) {
            toggleFormElements(selector, isEdit);
        });

        var activeDropdown = document.getElementById('active');
        if (activeDropdown) {
            activeDropdown.disabled = isEdit;
        }

    });
</script>