<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php

            if ($this->session->flashdata('error')) {
                echo '<div id="message1" class="alert alert-danger">';
                echo '    <button type="button" class="close" data-dismiss="alert">&times;</button>';
                echo '    <span id="message_text">' . $this->session->flashdata('error') . '</span>';
                echo '</div>';
            }

            $form_generator = new MY_Form(lang('Bed'));
            $form_generator->form_open_current_url();
            $form_generator->dropdown(lang('Wards'), 'wards', $default_ward_names, '');
            $form_generator->dropdown(lang('Room Name'), 'rooms', $default_room_names, '');
            $form_generator->input_number('*' . lang('Add Beds'), 'beds', $defoult_bed, '');
            $form_generator->dropdown(lang('Active'), 'Active', array('1' => lang('Yes'), '0' => lang('No')), $default_Active);
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {

    function loadRooms(ward_name) {
        $('#rooms').empty(); 
        $.ajax({
            url: `ward_beds/get_rooms_dropdown/${ward_name}`,
            type: 'GET',
            data: { ward: ward_name },
            dataType: 'json',
            success: function(data) {
                $('#rooms').empty(); 
                if (data.rooms_names) {
                    $.each(data.rooms_names, function(rid, name) {
                        $('#rooms').append('<option value="' + rid + '">' + name + '</option>');
                    });
                } else {
                    $('#rooms').append('<option value="">No rooms available</option>');
                }
            }
        });
    }

    $('#wards').change(function() {
        var ward_name = $(this).val();
        loadRooms(ward_name);
    });

    var initialWard = $('#wards').val();
    if (initialWard) {
        loadRooms(initialWard);
    }
});

</script>