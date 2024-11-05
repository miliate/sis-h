<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Patient'));
            $form_generator->form_open_current_url();
            $title_dropdown = array(
                lang('Mr.') => lang('Mr.'),
                lang('Mrs.') => lang('Mrs.'),
                lang('Baby') => lang('Baby'),
            );
            $form_generator->dropdown('*' . lang('Title'), 'title', $title_dropdown, $default_title);
            $form_generator->input('*'.lang('Surname'), 'name', $default_name, lang('Surname of patient'));
            $form_generator->input('*'.lang('First Name'), 'firstname', $default_firstname, lang('Firstname of patient'));
            $form_generator->input(lang('Other Name'), 'other_name', $default_other_name, lang('Name of patient'));
            $form_generator->dropdown('*' . lang('Gender'), 'gender', array('M' => 'M', 'F' => 'F'), $default_gender);
            $form_generator->input_date(lang('Date of Birth'), 'date_of_birth', $default_date_of_birth, lang('Date of Birth'));
            $civil_status = array(
                lang('Single') => lang('Single'),
                lang('Married') => lang('Married'),
                lang('Divorced') => lang('Divorced'),
                lang('Widow') => lang('Widow'),
                lang('Unknown') => lang('Unknown'),
            );
            $form_generator->input(lang('Father\'s name'), 'father_name', $default_father_name, 'Father\'s name of patient');
            $form_generator->input(lang('Mother\'s name'), 'mother_name', $default_mother_name, 'Mother\'s name of patient');
            $form_generator->dropdown(lang('Civil Status'), 'civil_status', $civil_status, $default_civil_status);
            $form_generator->input_inline_checkbox(lang('BI ID'), 'bi_id', $default_bi_id, 'ex. 123456789', 'N&atilde;o tem', $default_bi_id_checked);
            $form_generator->input_inline_checkbox(lang('NUIT ID'), 'nuit_id', $default_nuit_id, 'ex. 123456789', 'N&atilde;o tem', $default_nuit_id_checked);
            $form_generator->dropdown('Quadro do Estado', 'gov_emp', array('N' => 'N&atilde;o','Q' => 'SIM'), $default_gov_emp);
            $form_generator->input_inline_checkbox('Caderneta AMM', 'health_care_id', $default_health_care_id,'No. da Caderneta de Assistencia Medica Medicamentosa', 'N&atilde;o tem', $default_health_care_id_checked);
            $form_generator->input(lang('Profession'), 'profession', $default_profession, lang('Profession'));
            $form_generator->input(lang('Working place'), 'working_place', $default_working_place, lang('Working place of patient'));
            $form_generator->input(lang('Telephone'), 'telephone', $default_telephone, lang('Telephone Number'));

          /*  $reason_options = array(
                'Illness' => 'Illness',
                'Work accident' => 'Work accident',
                'Accident traffic' => 'Accident traffic',
                'Aggression' => 'Aggression',
                'Referred from other hospital' => 'Referred from other hospital',
                'Other accidents' => 'Other accidents',
                'Other reason' => 'Other reason'
            );*/

//            if ($default_reason != 'Illness' && $default_reason != 'Work accident' && $default_reason != 'Accident traffic'
//                && $default_reason != 'Aggression' && $default_reason != 'Other accidents' && $default_reason != 'Referred from other hospital')//other reason
//            {
//                $form_generator->dropdown_reason('Hospitalization reason', 'reason', $reason_options, 'Other reason', 'style="width:250px;" onchange="CheckReason(this.value)";', 'block', $default_reason);
//            }
//            else {
//                $form_generator->dropdown_reason('Hospitalization reason', 'reason', $reason_options, $default_reason, 'style="width:250px;" onchange="CheckReason(this.value)";', 'none');
//            }
//            $form_generator->input(lang('Entry Time'), 'entry_time', $default_entry_time, 'Entry Time');

            $form_generator->dropdown(lang('Province'), 'province', $dropdown_provinces, $default_province);
            $form_generator->dropdown(lang('District'), 'district', $dropdown_district, $default_district);
            $form_generator->dropdown(lang('Health Unit'), 'health_unit', $dropdown_health_unit, $default_health_unit);
            $form_generator->input('*'.lang('Address'), 'address', $default_address, '');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Any Remarks');


            $form_generator->button_submit_reset($id);
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    function CheckReason(val){
        var element=document.getElementById('hos_reason');
        if(val=='Other reason')
            element.style.display='block';
        else
            element.style.display='none';
    }

</script>
<script>
    $('#bi_id_checkbox').change(function () {
        if (this.checked) {
            $(':input[name="bi_id"]').val("");
            $(':input[name="bi_id"]').prop('disabled', true);
        } else {
            $(':input[name="bi_id"]').prop('disabled', false);
        }
    });
    $('#nuit_id_checkbox').change(function () {
        if (this.checked) {
            $(':input[name="nuit_id"]').val("");
            $(':input[name="nuit_id"]').prop('disabled', true);
        } else {
            $(':input[name="nuit_id"]').prop('disabled', false);
        }
    });
    $('#health_care_id_checkbox').change(function () {
        if (this.checked) {
            $(':input[name="health_care_id"]').val("");
            $(':input[name="health_care_id"]').prop('disabled', true);
        } else {

            $(':input[name="health_care_id"]').prop('disabled', false);
        }
    });
    // change province
    $("#province").change(function () {
        district_id = $("#province").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_district/" + district_id,
            type: "post"
        }).done(function (response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                console.log(response[i]);
                html += '<option value="' + response[i].district_code + '">' + response[i].name + '</option>';
                if (i == 0) {
                    district_id = response[i].district_code;
                }
            }
            $("#district").html(html);

            //update health unit
            $.ajax({
                url: "<?php echo base_url() ?>index.php/patient/get_health_unit/" + district_id,
                type: "post"
            }).done(function (response) {
                response = JSON.parse(response);
                var html = '';
                for (var i = 0; i < response.length; i++) {
                    console.log(response[i]);
                    html += '<option value="' + response[i].id + '">' + response[i].US + '</option>';
                }
                $("#health_unit").html(html);

            }).fail(function () {
                alert('Error');
            });

        }).fail(function () {
            alert('Error');
        });
    });
    // change district
    $("#district").change(function () {
        district_id = $("#district").val();
        $.ajax({
            url: "<?php echo base_url() ?>index.php/patient/get_health_unit/" + district_id,
            type: "post"
        }).done(function (response) {
            response = JSON.parse(response);
            var html = '';
            for (var i = 0; i < response.length; i++) {
                console.log(response[i]);
                html += '<option value="' + response[i].id + '">' + response[i].US + '</option>';
            }
            $("#health_unit").html(html);

        }).fail(function () {
            alert('Error');
        });
    });
</script>
