
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
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

            $form_generator = new MY_Form('Processo de Admissão');
            $form_generator->form_open_current_url();
            $form_generator->input('Data e Hora de Admissão', 'date_time', $default_time, '', '');
            $form_generator->input(lang('Complaint'), 'complaint', $default_complaint, '', 'readonly');
            $form_generator->input('Enfermaria', 'ward', $default_ward, '', 'readonly');
            if ($ugid != 21 || $ugid != 27 ) {
                $form_generator->input(lang('Doctor'), 'doctor', $default_doctor, '', 'readonly');
            }
            $form_generator->dropdown('Nº do Quarto', 'room_no', $default_room_no, '');
            $form_generator->dropdown('Nº da Cama', 'bed_no', $default_bed_no, '');
            $form_generator->text_area(lang('Remarks'), 'remarks', $default_remarks, 'Remarks');
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    // change Department
    // $("#service").change(function () {
    //     service_id = $("#service").val();
    //     $.ajax({
    //         url: "<?php echo base_url() ?>index.php/doctor/get_dropdown_doctors/" + service_id,
    //         type: "post"
    //     }).done(function (response) {
    //         response = JSON.parse(response);
    //         var html = '';
    //         for (var i = 0; i < response.length; i++) {
    //             console.log(response[i]);
    //             html += '<option value="' + response[i].id + '">' + response[i].Name + '</option>';
    //             if (i == 0) {
    //                 doctor = response[i].id;
    //             }
    //         }
    //         $("#doctor").html(html);

    //     }).fail(function () {
    //         alert('Error');
    //     });
    // });

    $(document).ready(function () {
        cost_val = $("#patient_costs").val();
        if (cost_val=='NULL'||cost_val>0) {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
            console.log(cost_val);
        } else {
            $(':input[name="reason_nopay"]').show();
            $('label[for="reason_nopay"]').show();
        }
    });

    $('#patient_costs').change(function () {
        cost_val = $("#patient_costs").val();
        if (cost_val==0) {
            $(':input[name="reason_nopay"]').show();
            $(':input[name="reason_nopay"]').attr("required", "true");
            $('label[for="reason_nopay"]').show();
        } else {
            $(':input[name="reason_nopay"]').hide();
            $('label[for="reason_nopay"]').hide();
        }
    });

    $(document).ready(function() {
        $('#room_no').change(function() {
            var room_id = parseInt($(this).val());
            $('#bed_no').empty(); 
            $.ajax({
                url: "<?php echo base_url() ?>index.php/admission/get_dropdown_beds/" + room_id,
                type: 'GET',
                data: { 
                    room: room_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.beds) {
                        $('#bed_no').append('<option value=""></option>');
                        $.each(data.beds, function(bid, bedno) {
                            $('#bed_no').append('<option value="' + bid + '">' + bedno + '</option>');
                        });
                    } else {
                        $('#bed_no').append('<option value="">No beds available</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error: ' + error);
                }
            });
        });
    });

</script>
