<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 ">
            <?php
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $ref_id, $pid, $visit_info);
                    break;
                case 'ADM':
                    echo Modules::run('leftmenu/admission', $admission, $pid);  
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

            // Display the patient banner using the patient ID
            echo Modules::run('patient/banner', $pid);

            // Display information based on the reference type
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
                    <?= lang("Create Lab Test Order"); ?>
                </div>
                <div class="panel-body">
                    <form action="" method="post" role="form" class="form-horizontal">
                        <?php
                        // Form generator instance
                        $form_generator = new MY_Form('');
                        // Display validation errors if any
                        echo validation_errors();
                        ?>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group">
                                            <label class="input-sm" for="priority"><?php echo lang('Priority') ?></label>
                                            <select class="form-control input-sm" name="priority" id="priority">
                                                <option value="Normal"><?php echo lang('Normal') ?></option>
                                                <option value="Urgent"><?php echo lang('Urgent') ?></option>
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-group">
                                            <label class="input-sm" for="test_group"><?php echo lang('Lab Test Group') ?></label>
                                            <select class="form-control input-sm" name="test_group" id="test_group" onchange="show_available_test();">
                                                <?php
                                                // Populate lab test group dropdown
                                                foreach ($lab_test_group as $id => $name) {
                                                    echo '<option value="' . $id . '">' . ($name) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </td>
                                    <td></td>
                                    <td>
                                        <div class="form-group">
                                            <label class="input-sm" for="exam_date"><?php echo lang('Exam Date') ?></label>
                                            <?php

                                            $form_generator = new MY_Form('');
                                            $form_generator->input_date_future_1('', 'exam_date', $default_exam_date, lang('Exam scheduling'));
                                            $form_generator->form_close();
                                            ?>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <div id="lab_block" style="display: block">
                            <div class="panel panel-default" style="margin-bottom: 0px">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= lang("Available Test"); ?></h3>
                                </div>
                            </div>
                            <div id="lab_cont"></div>
                        </div>

                        <div id="selected_tests_block" style="display: none">
                            <div class="panel panel-default" style="margin-bottom: 0px">
                                <div class="panel-heading">
                                    <h3 class="panel-title"><?= lang("Selected Tests"); ?></h3>
                                </div>
                            </div>
                            <div id="selected_tests_cont">
                                <table class="table table-condensed table-hover">
                                    <thead>
                                        <tr>
                                            <th><?php echo lang('Select') ?></th>
                                            <th><?php echo lang('Name') ?></th>
                                            <th><?php echo lang('Group') ?></th>
                                            <th><?php echo lang('Department') ?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected_tests_body"></tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label style="color:red;"><?php echo lang('Second Password'); ?>: </label>
                                <input type="password" name="password2" class="form-control">
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" id="btn_by_group" class="btn btn-primary btn-sm"><?php echo lang('Save') ?></button>
                                <button class="btn btn-warning btn-sm" type="button" onclick="window.history.back()"><?php echo lang('Cancel') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function show_available_test() {
        var lab_test_group_id = $("#test_group").val();
        $("#lab_cont").html('');

        var request = $.ajax({
            url: "<?php echo base_url(); ?>index.php/patient_lab_order/get_lab_test_by_group/" + lab_test_group_id,
            type: "get"
        });


        request.done(function(response, textStatus, jqXHR) {
            var data = JSON.parse(response);
            if (data.length > 0) {
                $("#lab_block").show();
                    var html = '<table class="table table-condensed table-hover">';
                    html += '<tr>' +
                        '<td><b><?php echo lang('Select') ?></b></td>' +
                        '<td><b><?php echo lang('Name') ?></b></td>' +
                        '<td><b><?php echo lang('Group') ?></b></td>' +
                        '<td><b><?php echo lang('Department') ?></b></td>' +
                        '</tr>';

                    for (var i = 0; i < data.length; i++) {
                        html += '<tr>';
                        html += '<td><input type="checkbox" value="' + data[i]["LABID"] + '" name="lab_test[]" onclick="select_test(this)"></td>';
                        html += '<td>' + data[i]["Name"] + '</td>';
                        html += '<td>' + data[i]["Group"] + '</td>';
                        html += '<td>' + data[i]["Department"] + '</td>';
                        html += '</tr>';
                    }
                    html += '</table>';
                    $("#lab_cont").html(html);
            } else {
                $("#lab_block").show();
            }
        });
    }

    function select_test(checkbox) {
        var row = $(checkbox).closest('tr').clone();
        var test_id = $(checkbox).val();

        if (checkbox.checked) {
            $("#selected_tests_body").append(row);
        } else {
            $("#selected_tests_body").find('input[value="' + test_id + '"]').closest('tr').remove();
        }

        if ($("#selected_tests_body").children().length > 0) {
            $("#selected_tests_block").show();
        } else {
            $("#selected_tests_block").hide();
        }
    }
</script>