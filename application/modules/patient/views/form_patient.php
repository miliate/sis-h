<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-1">
            <?php
            $form_generator = new MY_Form(lang('New Patient'));
            $form_generator->form_open_current_url();

            /*
if($default_department==2)
            {*/
            $form_generator->input_inline_checkbox(lang('Old PID'), 'pid2', $default_pid2, 'ex. XXXXXX/AA', lang('it does not have'), $default_pid2_checked);
            //    }


            $title_dropdown = array(
                '' => '--',
                lang('Mr.') => lang('Mr.'),
                lang('Mrs.') => lang('Mrs.'),
                lang('Baby') => lang('Baby'),
                'IN' => 'IN',
            );

            $civil_status = array(
                lang('Single') => lang('Single'),
                lang('Married') => lang('Married'),
                lang('Divorced') => lang('Divorced'),
                lang('Widow') => lang('Widow'),
                lang('Unknown') => lang('Unknown'),
            );

            $form_generator->dropdown('*' . lang('Title'), 'patient_title', $title_dropdown, $default_title);
            $form_generator->dropdown('*' . lang('Gender'), 'gender', array('M' => 'M', 'F' => 'F', 'I' => 'Indeterminado'), $default_gender);
            $form_generator->input('*' . lang('First Name'), 'firstname', $default_firstname, lang('Firstname of patient'));
            $form_generator->input('*' . lang('Surname'), 'name', $default_name, lang('Surname of patient'));
            $form_generator->input(lang('Father\'s name'), 'father_name', $default_father_name, lang('Father\'s name of patient'));
            $form_generator->input(lang('Mother\'s name'), 'mother_name', $default_mother_name, lang('Mother\'s name of patient'));
            $form_generator->dropdown(lang('Civil Status'), 'civil_status', $civil_status, $default_civil_status);
            $form_generator->dropdown(lang('Country of origin'), 'who_national_id', $dropdown_countries, $default_country);

            echo '<div id="birthplace">';
            $form_generator->dropdown(lang('Province'), 'province_birth', $dropdown_provinces, $default_province_birth);
            $form_generator->dropdown(lang('District'), 'district_birth', $district_birth, $default_district_birth);
            echo '</div>';

            // $form_generator->input('*' . lang('Nationality'), 'who_national_id', $default_firstname, lang('Nationality'));
            $form_generator->dropdown('*' . lang('ID Type'), 'type_id', $dropdown_id_type, $default_id_type);
            $form_generator->input(lang('ID Number'), 'bi_id', $default_bi_id, 'ex. 123456789', '', $default_bi_id_checked);
            $form_generator->input_inline_checkbox(lang('NUIT ID'), 'nuit_id', $default_nuit_id, 'ex. 123456789', lang('it does not have'), $default_nuit_id_checked);
            //  $form_generator->input(lang('Other Name'), 'other_name', $default_other_name, lang('Name of patient'));

            echo '<div class="form-group">';
            echo '    <label class="col-sm-2 control-label" for="date_of_birth">' . '*' . lang('Date of Birth') . '</label>';
            echo '    <div class="col-sm-10">';
            echo '        <div class="input-group">';
            echo '            <input type="date" class="form-control input-sm" name="date_of_birth" id="date_of_birth" placeholder="Your Placeholder" max="' . date('Y-m-d') . '" min="' . date('Y-m-d', strtotime('-150 years')) . '" value="' . set_value('date_of_birth', $default_date_of_birth) . '">';
            echo '            <span class="input-group-addon">';
            echo '                <input type="hidden" name="date_of_birth_checkbox" value="0">';
            echo '                <input type="checkbox" name="date_of_birth_checkbox" id="date_of_birth_checkbox" value="1" checked>';
            echo '                ' . lang('do not know');
            echo '            </span>';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';


            echo '<div class="form-group" id="age_input_group" name="idade" style="display: none;">';
            echo '    <label class="col-sm-2 control-label" for="date_year date_month date_day">' . lang('Age') . ':</label>';
            echo '    <div class="col-sm-10">';
            echo '        <div class="input-group">';
            echo '            <!-- Year input -->';
            echo '            <span class="input-group-addon">' . lang('Year') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="date_year" id="date_year" placeholder="YYYY" value="' . set_value('date_year', $default_year) . '">';
            echo '            <!-- Month input -->';
            echo '            <span class="input-group-addon">' . lang('Month') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="date_month" id="date_month" placeholder="MM" value="' . set_value('date_month', $default_month) . '">';
            echo '            <!-- Day input -->';
            echo '            <span class="input-group-addon">' . lang('Day') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="date_day" id="date_day" placeholder="DD" value="' . set_value('date_day', $default_day) . '">';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';

            echo '<div class="form-group" id="age_input_group" name="idade_referida" style="display: none;">';
            echo '    <label class="col-sm-2 control-label" for="birth_year_referred birth_month_referred birth_day_referred">' . lang('Referred Age') . '</label>';
            echo '    <div class="col-sm-10">';
            echo '        <div class="input-group">';
            echo '            <!-- Year input -->';
            echo '            <span class="input-group-addon">' . lang('Year') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="birth_year_referred" id="birth_year_referred" placeholder="YYYY" min="0" max="150" value="' . set_value('birth_year_referred', $default_year_referred) . '">';
            echo '            <!-- Month input -->';
            echo '            <span class="input-group-addon">' . lang('Month') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="birth_month_referred" id="birth_month_referred" placeholder="MM" min="0" max="12" value="' . set_value('birth_month_referred', $default_month_referred) . '">';
            echo '            <!-- Day input -->';
            echo '            <span class="input-group-addon">' . lang('Day') . '</span>';
            echo '            <input type="number" class="form-control input-sm" name="birth_day_referred" id="birth_day_referred" placeholder="DD" min="0" max="31" value="' . set_value('birth_day_referred', $default_day_referred) . '">';
            echo '        </div>';
            echo '    </div>';
            echo '</div>';

            ?>

            <hr>

            <?php

            $form_generator->input_inline_checkbox(lang('Address'), 'address_id', $default_address_id, 'Bairro,Rua/Avenida,Quarteirao,Casa', lang('it does not have'), $default_address_id_checked);
            echo '<div id="address">';
            $form_generator->dropdown(lang('Province'), 'province', $dropdown_provinces, $default_province);
            $form_generator->dropdown(lang('District'), 'district', $dropdown_district, $default_district);
            $form_generator->dropdown(lang('Health Unit'), 'health_unit', $dropdown_health_unit, $default_health_unit);
            $form_generator->input(lang('Telephone'), 'telephone', $default_telephone, lang('Telephone Number'));

            echo '</div>';

            echo '<hr>';


            $form_generator->dropdown(lang('State Employee'), 'gov_emp', array('N' => lang('No'), 'Q' => lang('Yes')), $default_gov_emp);
            $form_generator->input_inline_checkbox('Caderneta AMM', 'health_care_id', $default_health_care_id, lang('Medication Medical Assistance Booklet Number'), lang('it does not have'), $default_health_care_id_checked);
            $form_generator->input(lang('Profession'), 'profession', $default_profession, lang('Profession'));
            $form_generator->input(lang('Working place'), 'working_place', $default_working_place, lang('Working place of patient'));
            //            $form_generator->dropdown_reason_1('Hospitalization reason', 'reason', $reason_options, $default_reason, 'style="width:250px;" onchange="CheckReason(this.value)";');
            //            $form_generator->input(lang('Entry Time'), 'entry_time', $default_entry_time, 'Entry Time');

            $form_generator->dropdown(lang('Entry Point'), 'entry_department', $dropdown_department, $default_department);
            $form_generator->dropdown(lang('Entry Service'), 'entry_service', $dropdown_service, $default_service);
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, lang('Any Remarks'));


            ?>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="submit" value="" class="btn btn-primary"> Proximo</button>
                    <button type="button" value="" class="btn btn-warning" onclick="window.history.back();">Cancelar
                    </button>
                    <button type="reset" value="" class="btn btn-success"><i class="fa fa-refresh"></i> Refresh</button>
                </div>
            </div>
            <?php
            $form_generator->form_close();
            ?>
        </div>
        <div class="col-md-3">
            <div class="panel panel-danger" id="patient-suggestion">
                <div class="panel-heading text-center"><?= lang('Similar patient'); ?></div>
                <table class="table table-bordered">
                    <tbody id="similar-patients"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#patient_title").change(function() {
            if($(this).val() === "Sra.") {
                $("#gender").val("F");
            } else if($(this).val() === "Sr.") {
                $("#gender").val("M");
            }
        });


        if($("#date_of_birth_checkbox").prop("checked")) {
            $(":input[name='date_of_birth_checkbox']").prop("checked", false);
        }

        if($('#date_of_birth').val()) {
            calc_date();
        }

        // if ($('#birth_year_referred').val()) {
        //     alert("Ola mundo");
        //     $(":input[name='date_of_birth_checkbox']").prop("checked", true);
        //     $("div[name='idade_referida']").show();
        // }

        $("#date_of_birth_checkbox").change(function() {
            if($(this).prop("checked")) {
                $(":input[name='date_of_birth']").prop("disabled", true);
                $(":input[name='date_year']").prop("disabled", true);
                $(":input[name='date_month']").prop("disabled", true);
                $(":input[name='date_day']").prop("disabled", true);
            } else {
                $(":input[name='date_of_birth']").prop("disabled", false);
                $(":input[name='date_year']").prop("disabled", false);
                $(":input[name='date_month']").prop("disabled", false);
                $(":input[name='date_day']").prop("disabled", false);
            }
        });

        $("#date_of_birth_checkbox").change(function() {
            if($(this).prop("checked")) {
                $("div[name='idade_referida']").show();
                $("div[name='idade']").hide();
                $("input[name='date_of_birth']").val(null);
            } else {
                $("div[name='idade_referida']").hide();
            }
        });

        $('input[name="date_of_birth"]').change(function() {
            calc_date();
        });

        function calc_date() {
            var data = new Date();
            var ano_actual = data.getFullYear();
            var mes_actual = data.getMonth() + 1;
            var dia_actual = data.getDate();

            var date = new Date($('input[name="date_of_birth"]').val());
            var ano = date.getFullYear();
            var mes = date.getMonth() + 1;
            var dia = date.getDate();

            var idade_ano = ano_actual - ano;
            var idade_mes = mes_actual - mes;
            var idade_dia = dia_actual - dia;
            var yearAge = ano_actual - ano;

            if(mes_actual >= mes) {
                var monthAge = mes_actual - mes;
            } else {
                yearAge--;
                var monthAge = 12 + mes_actual - mes;
            }

            if(dia_actual >= dia) {
                var dateAge = dia_actual - dia;
            } else {
                monthAge--;
                var dateAge = 31 + dia_actual - dia;
                if(monthAge < 0) {
                    monthAge = 11;
                    yearAge--;
                }
            }

            age = {
                years: yearAge,
                months: monthAge,
                days: dateAge
            };
            $(":input[name='date_year']").val(age.years);
            $(":input[name='date_month']").val(age.months);
            $(":input[name='date_day']").val(age.days);
            $(":input[name='date_year']").prop("disabled", true);
            $(":input[name='date_month']").prop("disabled", true);
            $(":input[name='date_day']").prop("disabled", true);
            $("div[name='idade']").show();
        }

        $(":input[name='birth_year_referred']").change(function() {
            $(":input[name='birth_month_referred']").val(0);
            $(":input[name='birth_day_referred']").val(0);
        });

        // Listen for changes on the age_year input field
        $('input[name="age_year"]').on('change', function() {
            // Get the input value (age in years)
            var age = $(this).val();

            // Check if the input value is a number and greater than zero
            if($.isNumeric(age) && age > 0) {
                // Calculate the date of birth
                var currentDate = new Date();
                var birthYear = currentDate.getFullYear() - year;
                var birthMonth = currentDate.getMonth() + 1 - month; // JavaScript months are 0-11
                var birthDay = currentDate.getDate() - day;

                if(birthMonth <= 0) {
                    birthMonth += 12;
                    birthYear -= 1;
                }

                if(birthDay <= 0) {
                    var previousMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
                    birthDay += previousMonth.getDate();
                    birthMonth -= 1;
                    if(birthMonth <= 0) {
                        birthMonth += 12;
                        birthYear -= 1;
                    }
                }

                var dateOfBirth = birthYear + '-' + (birthMonth < 10 ? '0' + birthMonth : birthMonth) + '-' + (birthDay < 10 ? '0' + birthDay : birthDay);

                $('input[name="date_of_birth"]').val(dateOfBirth).prop('disabled', true);
            } else {
                $('input[name="date_of_birth"]').val('').prop('disabled', false);
            }
        });


        // Listen for changes on the date_of_birth input field
        $('input[name="date_of_birth"]').on('change', function() {
            // Check if a date is selected
            if($(this).val() !== '') {
                // Disable the age_year input field
                $('input[name="age_year"]').prop('disabled', true);
                $('input[name="age_month"]').prop('disabled', true);
                $('input[name="age_day"]').prop('disabled', true);
            } else {
                // Enable the age_year input field if no date is selected
                $('input[name="age_year"]').prop('disabled', false);
                $('input[name="age_month"]').prop('disabled', false);
                $('input[name="age_day"]').prop('disabled', false);
            }
        });

        $('#name, #firstname').on('blur', function() {
            var surname = $('#name').val().trim();
            var firstname = $('#firstname').val().trim();
            if(surname.length == 0 || firstname.length == 0) {
                return;
            }
            $.ajax({
                url: '<?php echo site_url("patient/find_similar_patient_by_name") ?>',
                method: 'GET',
                dataType: 'json',
                data: {
                    term1: surname,
                    term2: firstname
                },
                success: function(response) {
                    displaySimilarPatients(response);
                },
                /*error: function(xhr, status, error) {
                    console.error(error);
                }*/
            });
            // } else {
            //     $('#similar-patients').empty();
            // }
        });

        $('#telephone').on('blur', function() {
            var telephone = $('#telephone').val().trim();

            $.ajax({
                url: '<?php echo site_url("patient/find_similar_patient_by_telephone") ?>',
                method: 'GET',
                dataType: 'json',
                data: {
                    term1: telephone
                },
                success: function(response) {
                    displaySimilarPatients(response);
                },
            });

        });

        $('#bi_id').on('blur', function() {
            var bi = $('#bi_id').val().trim();

            $.ajax({
                url: '<?php echo site_url("patient/find_similar_patient_by_bi_id") ?>',
                method: 'GET',
                dataType: 'json',
                data: {
                    term1: bi
                },
                success: function(response) {
                    displaySimilarPatients(response);
                },
            });

        });

        function displaySimilarPatients(patients) {
            $('#similar-patients').empty();
            if(patients.length > 0) {
                var html = '<ul>';
                patients.forEach(function(patient) {
                    html += '<li>' +
                        '<a href="<?php echo site_url("patient/view/") ?>/' + patient.id + '">' +
                        patient.name + ' | ' + patient.id + '<br>' +
                        patient.gender + ' | ' + patient.birthday +
                        '</a>' +
                        '</li>';
                });
                html += '</ul>';
                $('#similar-patients').html(html);
            }
        }

        if($('#birth_year_referred').val()) {
            $(":input[name='date_of_birth_checkbox']").prop("checked", true);
            $("div[name='idade_referida']").show();
        }
    });
</script>

<script>

    $("#who_national_id").change(function() {
        if($(this).val() === "144") {
            $('#birthplace').show();
        } else {
            $('#birthplace').hide();
            $(':input[name="province_birth"]').val("");
            $(':input[name="district_birth"]').val("");
            $(':input[name="nearby_hospital"]').val("");
        }
    });


    $('#pid2_checkbox').change(function() {
        if(this.checked) {
            $(':input[name="pid2"]').val("");
            $(':input[name="pid2"]').prop('disabled', true);
        } else {
            $(':input[name="pid2"]').prop('disabled', false);
        }
    });


    $('#nuit_id_checkbox').change(function() {
        if(this.checked) {
            $(':input[name="nuit_id"]').val("");
            $(':input[name="nuit_id"]').prop('disabled', true);
        } else {
            $(':input[name="nuit_id"]').prop('disabled', false);
        }
    });

    $('#address_id_checkbox').change(function() {
        if(this.checked) {
            $(':input[name="address_id"]').val("");
            $(':input[name="address_id"]').prop('disabled', true);
        } else {

            $(':input[name="address_id"]').prop('disabled', false);
        }
    });

    $('#health_care_id_checkbox').change(function() {
        if(this.checked) {
            $(':input[name="health_care_id"]').val("");
            $(':input[name="health_care_id"]').prop('disabled', true);
        } else {

            $(':input[name="health_care_id"]').prop('disabled', false);
        }
    });

    $(document).ready(function() {
        // Attach a change event handler to the dropdown
        $('#type_id').change(function() {
            // Get the selected option
            var selectedOption = $(this).find('option:selected');

            // Check if the first option is selected
            if(selectedOption.index() === 0) {
                // Disable the input with id bi_id

                $('#bi_id').prop('disabled', true).val('');
            } else {
                // Enable the input with id bi_id
                $('#bi_id').prop('disabled', false);
            }
        });

        // Trigger the change event on page load to handle the default selection
        $('#type_id').trigger('change');
    });

    // change province
    $("#province").change(function() {
        district_id = $("#province").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_district/" + district_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].district_code + '">' + response[i].name + '</option>';
                if(i == 0) {
                    district_id = response[i].district_code;
                }
            }
            $("#district").html(html);

            //update health unit
            $.ajax({
                url: "<?php echo base_url() ?>index.php/patient/get_health_unit/" + district_id,
                type: "post"
            }).done(function(response) {
                response = JSON.parse(response);
                var html = '';
                for (var i = 0; i < response.length; i++) {
                    html += '<option value="' + response[i].id + '">' + response[i].US + '</option>';
                }
                $("#health_unit").html(html);

            }).fail(function() {
                alert('Error');
            });

        });
    });
    // change district
    $("#district").change(function() {
        district_id = $("#district").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_health_unit/" + district_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].id + '">' + response[i].US + '</option>';
            }
            $("#health_unit").html(html);

        });
    });


    // New logic for province_birth and district_birth
    $("#province_birth").change(function() {
        district_id = $("#province_birth").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_district/" + district_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].district_code + '">' + response[i].name + '</option>';
                if(i == 0) {
                    district_id = response[i].district_code;
                }
            }
            $("#district_birth").html(html);

        });
    });

    $("#district_birth").change(function() {
        district_id = $("#district_birth").val();
        // Assuming there is a similar endpoint to get health units for birth districts, if needed.
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_health_unit/" + district_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].id + '">' + response[i].US + '</option>';
            }
            $("#health_unit_birth").html(html);

        });
    });

    // change Department
    $("#entry_department").change(function() {
        department_id = $("#entry_department").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_dropdown_services/" + department_id,
            type: "post"
        }).done(function(response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                html += '<option value="' + response[i].service_id + '">' + response[i].name + '</option>';
                if(i == 0) {
                    service_id = response[i].service_id;
                }
            }
            $("#entry_service").html(html);

        });
    });
</script>