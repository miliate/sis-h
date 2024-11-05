<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            echo Modules::run('leftmenu/patient', $id); //runs the available left menu for preferance
            ?>
        </div>
        <div class="col-md-10 ">
            <?php
            echo Modules::run('patient/banner_full', $id);
            ?>
            <!--            --><?php //if (has_permission('patient_all_history', 'view')) { ?>

            <!--            --><?php //} ?>
            <div id="contact_person" style='padding: 5px;'><?php echo $contact_person; ?></div>
        </div>
    </div>
</div>

<script>
    $("#add_to_active_list").click(function () {
        event.preventDefault();
        $.getJSON("<?php echo base_url() . 'index.php/active_list/is_in_active_list/' . $id ?>", function (data) {
            if (data.is_in_active_list) {
                alert('This patient on active list');
            } else {
                window.location.href = $("#add_to_active_list").attr("href");
            }
        })
    })
</script>