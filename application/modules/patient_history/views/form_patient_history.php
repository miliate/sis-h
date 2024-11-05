<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php echo Modules::run('leftmenu/patient', $pid, $ref_id); ?>
        </div>
        <div class="col-md-8 col-md-offset-1">
            <?php echo Modules::run('patient/banner', $pid); ?>

            <?php
            $form_generator = new MY_Form(lang('Current Illness History'));
            $form_generator->form_open_current_url();
            include dirname(__FILE__) . '/../../patient_diagnosis/views/chronic_disease.php';
            ?>

            <?php
            $form_generator->input('*' . lang('Main Complaint'), 'complaint', $default_complaint);
            $form_generator->text_area('*' . lang('Current Illness History'), 'history_of_complaint', $default_history_of_complaint);
            echo '<hr>';
            echo '<h5><strong>' . lang('Pathological History') . '</strong></h5>';
            echo '<br>';


            if (!$is_edit) {
                get_diagnosis_search_component("chronic_diseases", $default_chronic_diseases);
            } else {
                echo '<div class="form-group">';
                echo '<label class="col-sm-2 control-label">' . lang('Direct Diagnosis') . '</label>';
                echo '<div class="col-sm-10">';
                echo '<div class="well well-sm" style="background: white">';
                if (is_array($default_chronic_diseases)) {
                    echo '<p class="form-control-static">' . implode(', ', $default_chronic_diseases) . '</p>';
                } else {
                    echo '<p class="form-control-static">' . $default_chronic_diseases . '</p>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            
            
            $form_generator->input(lang('Previous Diseases'), 'previous_diseases', $default_previous_diseases);

            $form_generator->input(lang('Allergy to Medication'), 'drug_select', $default_drug_select);

            $form_generator->input(lang('Allergy to Food'), 'allergy_food', $default_allergy_food);
            $form_generator->input(lang('Other Allergies'), 'other_allergies', $default_other_allergies);

            $form_generator->text_area(lang('Family History'), 'family_history', $default_family_history);
           
            echo '<br>';
            $form_generator->text_area('*' .lang('Diet History'), 'dietary_history', $default_dietary_history);
            echo '<hr>';


            echo '<h5><strong>' . lang('Psychosocial History') . '</strong></h5>';
            echo '<br>';
            $form_generator->text_area(lang('Alcohol Habits'), 'alcohol_habits', $default_alcohol_habits);
            $form_generator->input(lang('Smoking Habits'), 'smoking_habits', $default_smoking_habits);
            $form_generator->input(lang('Travel History'), 'travel_history', $default_travel_history);

            echo '<div id="menstrual_history_section">';
            echo '<hr>';

            echo '<h5><strong>' . lang('Menstrual History') . '</strong></h5>';
            echo '<br>';

            echo '<div class="form-group">
                    <div class="col-sm-6">';

            if (!$is_edit) {
                echo '<label class="col-sm-4 control-label" for="menarche">' . lang('Menarche') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="input-group">';
                echo '        <input type="number" class="form-control input-sm" name="menarche" id="menarche" placeholder="' . lang('Age') . '" min="0" max="100" value="' . $default_menarche . '">';
                echo '        <span class="input-group-addon">';
                echo '            <input type="hidden" name="menarche_checkbox" value="0">';
                echo '            <input type="checkbox" name="menarche_checkbox" id="menarche_checkbox" value="1" checked>';
                echo '            ' . lang('No');
                echo '        </span>';
                echo '    </div>';
                echo '</div>';
            } else {
                echo '<label class="col-sm-4 control-label">' . lang('Menarche') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="well well-sm" style="background: white">';
                echo '        <p class="form-control-static">' . $default_menarche . '</p>';
                echo '    </div>';
                echo '</div>';
            }

            echo '</div>
                
                <div class="col-sm-6">';

            if (!$is_edit) {
                echo '<label class="col-sm-4 control-label" for="menopause">' . lang('Menopause') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="input-group">';
                echo '        <input type="number" class="form-control input-sm" name="menopause" id="menopause" placeholder="' . lang('Age') . '" min="0" max="100" value="' . $default_menopause . '">';
                echo '        <span class="input-group-addon">';
                echo '            <input type="hidden" name="menopause_checkbox" value="0">';
                echo '            <input type="checkbox" name="menopause_checkbox" id="menopause_checkbox" value="1" checked>';
                echo '            ' . lang('No');
                echo '        </span>';
                echo '    </div>';
                echo '</div>';
            } else {
                echo '<label class="col-sm-4 control-label">' . lang('Menopause') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="well well-sm" style="background: white">';
                echo '        <p class="form-control-static">' . $default_menopause . '</p>';
                echo '    </div>';
                echo '</div>';
            }

            echo '</div>
            </div>';




            echo '<div class="form-group">
            <div class="col-sm-6">';

            if (!$is_edit) {
                echo '<label class="col-sm-4 control-label" for="second_menstruation_date">' . lang('Date of Second Last Menstruation') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="input-group">';
                echo '        <input type="date" class="form-control input-sm" name="second_menstruation_date" id="second_menstruation_date" placeholder="Insert Second Menstruation Date" max="' . date('Y-m-d') . '" min="' . date('Y-m-d', strtotime('-150 years')) . '" value="' . $default_second_menstruation_date . '">';
                echo '        <span class="input-group-addon">';
                echo '            <input type="hidden" name="second_menstruation_date_checkbox" value="0">';
                echo '            <input type="checkbox" name="second_menstruation_date_checkbox" id="second_menstruation_date_checkbox" value="1" checked>';
                echo '            ' . lang('No');
                echo '        </span>';
                echo '    </div>';
                echo '</div>';
            } else {
                echo '<label class="col-sm-4 control-label">' . lang('Date of Second Last Menstruation') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="well well-sm" style="background: white">';
                echo '        <p class="form-control-static">' . $default_second_menstruation_date . '</p>';
                echo '    </div>';
                echo '</div>';
            }

            echo '</div>
            
            <div class="col-sm-6">';

            if (!$is_edit) {
                echo '<label class="col-sm-4 control-label" for="date_last_menstruation">' . lang('Date of Last Menstruation') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="input-group">';
                echo '        <input type="date" class="form-control input-sm" name="date_last_menstruation" id="date_last_menstruation" placeholder="Insert Date of Last Menstruation" max="' . date('Y-m-d') . '" min="' . date('Y-m-d', strtotime('-150 years')) . '" value="' . $default_date_last_menstruation . '">';
                echo '        <span class="input-group-addon">';
                echo '            <input type="hidden" name="date_last_menstruation_checkbox" value="0">';
                echo '            <input type="checkbox" name="date_last_menstruation_checkbox" id="date_last_menstruation_checkbox" value="1" checked>';
                echo '            ' . lang('No');
                echo '        </span>';
                echo '    </div>';
                echo '</div>';
            } else {
                echo '<label class="col-sm-4 control-label">' . lang('Date of Last Menstruation') . '</label>';
                echo '<div class="col-sm-8">';
                echo '    <div class="well well-sm" style="background: white">';
                echo '        <p class="form-control-static">' . $default_date_last_menstruation . '</p>';
                echo '    </div>';
                echo '</div>';
            }

            echo '</div>
            </div>';
            $form_generator->input(lang('Cycle Periodicity'), 'cycle_periodicity', $default_cycle_periodicity);
            $form_generator->input(lang('Flow Characteristics'), 'flow_characteristics', $default_flow_characteristics);
            echo '</div>';
            echo '<hr>';

            echo '<h5><strong>' . lang('System Review') . '</strong></h5>';
            echo '<br>';
            $form_generator->input(lang('General Complaints'), 'general_complaints', $default_general_complaints);

            $form_generator->input(lang('Respiratory and Cardiovascular'), 'respiratory_cardiovascular', $default_respiratory_cardiovascular);

            $form_generator->input(lang('Gastrointestinal'), 'gastrointestinal', $default_gastrointestinal);

            $form_generator->input(lang('Genitourinary'), 'genitourinary', $default_genitourinary);

            $form_generator->input(lang('Nervous System'), 'nervous_system', $default_nervous_system);

            $form_generator->input(lang('Hematolymphopoietic System'), 'hematolymphopoietic_system', $default_hematolymphopoietic_system);

            $form_generator->input(lang('Osteo-muscular-System'), 'osteo-mio-articular', $default_osteo_mio_articular);

            $form_generator->input(lang('Endocrine System'), 'endocrine_system', $default_endocrine_system);

            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks);
            $form_generator->input(lang('Doctor Name'), 'doctor', $default_doctor, '', 'readonly');


            $form_generator->dropdown(lang('Active'), 'active', array('1' => lang('Yes'), '0' => lang('No')), $default_active);

            if (!$is_edit) {
                $form_generator->button_submit_reset($id);
            } else {
                // Botões de submit e reset
                $form_generator->button_back();
            }
            // Fechamento do formulário
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
<script language="javascript">
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
            '#allergy_food',
            '#other_allergies',
            '#family_history',
            '#alcohol_habits',
            '#smoking_habits',
            '#travel_history',
            '#previous_diseases',
            '#complaint',
            '#cycle_periodicity',
            '#flow_characteristics',
            '#general_complaints',
            '#respiratory_cardiovascular',
            '#gastrointestinal',
            '#genitourinary',
            '#nervous_system',
            '#hematolymphopoietic_system',
            '#osteo-mio-articular',
            '#endocrine_system',
            '#doctor', // Doctor Name
            '#active' // Active dropdown
        ];

        var textAreaSelectors = [
            '#history_of_complaint',
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

<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        var gender = "<?php echo $gender; ?>"; // Gender value from the controller
        // Toggle the menstrual history section based on gender
        var menstrualHistorySection = document.getElementById('menstrual_history_section');
        menstrualHistorySection.style.display = (gender === 'F') ? 'block' : 'none';
    });

    function split(val) {
        return val.split(/,\s*/);
    }

    function extractLast(term) {
        return split(term).pop();
    }

    $(document).ready(function() {
        // Function to toggle input field's disabled state based on checkbox status
        function toggleInput(checkboxSelector, inputSelector) {
            $(inputSelector).prop("disabled", $(checkboxSelector).prop("checked"));
        }

        // Initialize states based on checkbox status
        toggleInput("#date_last_menstruation_checkbox", ":input[name='date_last_menstruation']");
        toggleInput("#second_menstruation_date_checkbox", ":input[name='second_menstruation_date']");
        toggleInput("#menopause_checkbox", ":input[name='menopause']");
        toggleInput("#menarche_checkbox", ":input[name='menarche']");

        // Attach change event handlers to checkboxes
        $("#date_last_menstruation_checkbox").change(function() {
            toggleInput(this, ":input[name='date_last_menstruation']");
        });
        $("#second_menstruation_date_checkbox").change(function() {
            toggleInput(this, ":input[name='second_menstruation_date']");
        });
        $("#menarche_checkbox").change(function() {
            toggleInput(this, ":input[name='menarche']");
        });
        $("#menopause_checkbox").change(function() {
            toggleInput(this, ":input[name='menopause']");
        });

        // Initialize the drug select dropdown with select2
        $("#drug_select").select2({
            width: '100%'
        });

        // Autocomplete functionality for the 'other_complaint' input
        $('#other_complaint').bind('keydown', function(event) {
            if (event.keyCode === $.ui.keyCode.TAB && $(this).data('autocomplete').menu.active) {
                event.preventDefault();
            }
        }).autocomplete({
            minLength: 2,
            focus: function() {
                return false;
            },
            select: function(event, ui) {
                var terms = split(this.value);
                terms.pop(); // remove the current input
                terms.push(ui.item.value); // add the selected item
                terms.push(''); // add placeholder to get comma-and-space at the end
                this.value = terms.join(',');
                return false;
            },
            source: function(request, response) {
                response($.ui.autocomplete.filter(data, extractLast(request.term)));
            }
        });
    });
</script>