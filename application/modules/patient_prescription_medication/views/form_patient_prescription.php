<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?php
            echo Modules::run('patient/banner', $PID);
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
                    echo 'Wrong department';
                    break;
            }
            ?>

            <div>
                <div class="panel panel-primary">
                    <div class="panel-heading"><?= lang('Prescription') ?></div>
                    <form action="" method="post" role="form" class="form-horizontal"
                          style="padding-top: 10px;">
                        <?php
                        echo validation_errors();
                        $form_generator = new MY_Form();
                        //                    $form_generator->dropdown('Oder Confirmation Doctor', 'order_confirm_user', Modules::run('order_confirmation/get_doctor'), $this->session->userdata('uid'));
                        //                    $form_generator->password('Order Confirmation Password', 'order_confirm_password');
                        ?>
                        <!-- Table -->
                        <table class="table input-sm" id="table_drug">
                            <tbody>
                            <tr>
                                <th>#</th>
                                <th width="400px"><?php echo lang('Name') ?></th>
                                <th><?php echo lang('Dose') ?></th>
                                <th><?php echo lang('Frequency') ?></th>
                                <th><?php echo lang('Period') ?></th>
                                <th></th>
                            </tr>
                            </tbody>
                            <tbody>
                            </tbody>
                        </table>
                        <div class="form-group" style="text-align: center">
                            <button type="submit"
                                    class="btn btn-primary"><?php echo lang('Save') ?></button>
                            <button type="button" class="btn btn-warning"
                                    onclick="window.history.back()"><?php echo lang('Back') ?></button>
                        </div>
                    </form>
                </div>
                <div class="panel with-nav-tabs panel-info">
                    <div class="panel-heading">
                        <ul class="nav nav-tabs">
                            <li><a href="#tab1default" data-toggle="tab">Todos os Medicamentos</a></li>
                            <li class="active"><a href="#tab2default" data-toggle="tab">Minha Lista de Medicamentos</a></li>
                        </ul>
                    </div>
                    <div class="panel-body">
                        <div class="tab-content">
                            <div class="tab-pane fade " id="tab1default">
                                <table class="table input-sm">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th width="400px"><?php echo lang('Name') ?></th>
                                        <th><?php echo lang('Dose') ?></th>
                                        <th><?php echo lang('Frequency') ?></th>
                                        <th><?php echo lang('Period') ?></th>
                                        <th></th>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td><?php echo Modules::run('drug/view_select_drug') ?></td>
                                        <td><?php  echo Modules::run('drug/view_select_dose') ?>
                                        <?php //echo $form_generator->input(lang('Dose'), 'DoseID', '', ''); ?>
                                    </td>
                                        <td><?php echo Modules::run('drug/view_select_frequency') ?></td>
                                        <td><?php echo Modules::run('drug/view_select_period') ?></td>
                                        <td align="center" style="vertical-align: middle;">
                                            <button type="button" class="btn btn-info" id="add_drug_button"
                                                    onclick="add_drug()">
                                                <span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('Add') ?>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <script type="text/javascript">
                                        $(document).ready(function () {
                                            $("#drug_select").select2({
                                                width: '400px'
                                            });
                                        });
                                    </script>
                                </table>
                            </div>
                            <div class="tab-pane fade in active" id="tab2default">
                                <table class="table input-sm">
                                    <tbody>
                                    <tr>
                                        <th>#</th>
                                        <th width="400px"><?php echo lang('Name') ?></th>
                                        <th><?php echo lang('Dose') ?></th>
                                        <th><?php echo lang('Frequency') ?></th>
                                        <th><?php echo lang('Period') ?></th>
                                        <th></th>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    <tr>
                                        <td></td>
                                        <td><?php echo Modules::run('user_favour_drug/view_select_favour_drug', 'drug_select_2') ?></td>
                                        <td><?php echo Modules::run('drug/view_select_dose', 'dose_select_2') ?></td>
                                        <td><?php echo Modules::run('drug/view_select_frequency', 'frequency_select_2') ?></td>
                                        <td><?php echo Modules::run('drug/view_select_period', 'period_select_2') ?></td>
                                        <td align="center" style="vertical-align: middle;">
                                            <button type="button" class="btn btn-info" id="add_drug_button_2"
                                                    onclick="add_drug_2()">
                                                <span class="glyphicon glyphicon-plus-sign"></span> <?php echo lang('Add') ?>
                                            </button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    var index = 0;

    function add_drug() {
        index++;

        var selected_drug_text = $("#drug_select :selected").text();
        var selected_dose_text = $("#dose_select :selected").text();
        var selected_frequency_text = $("#frequency_select :selected").text();
        var selected_period_text = $("#period_select :selected").text();

        var html = '<tr>';
        html += '<td>' + index + '</td>';
        html += '<td>' + selected_drug_text + '</td>';
        html += '<td>' + selected_dose_text + '</td>';
        html += '<td>' + selected_frequency_text + '</td>';
        html += '<td>' + selected_period_text + '</td>';
        html += '<td align="center">' + '<button class="btn btn-danger btn_delete_drug" type="button"> Deletar</button>' + '</td>';

        html += '<input type="hidden" name="drug_id_selected[' + index + ']" value = "' + $("#drug_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="dose_id_selected[' + index + ']" value = "' + $("#dose_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="frequency_id_selected[' + index + ']" value = "' + $("#frequency_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="period_id_selected[' + index + ']" value = "' + $("#period_select :selected").val() + '">' + '</input>';

        html += '</tr>';

        $("#drug_id").val($("#drug_id").val() + ',' + $("#drug_select :selected").val());

        $('#table_drug > tbody:first-child').append(html);
    }

    function add_drug_2() {
        index++;

        var selected_drug_text = $("#drug_select_2 :selected").text();
        var selected_dose_text = $("#dose_select_2 :selected").text();
        var selected_frequency_text = $("#frequency_select_2 :selected").text();
        var selected_period_text = $("#period_select_2 :selected").text();

        var html = '<tr>';
        html += '<td>' + index + '</td>';
        html += '<td>' + selected_drug_text + '</td>';
        html += '<td>' + selected_dose_text + '</td>';
        html += '<td>' + selected_frequency_text + '</td>';
        html += '<td>' + selected_period_text + '</td>';
        html += '<td align="center">' + '<button class="btn btn-danger btn_delete_drug" type="button"> Deletar</button>' + '</td>';

        html += '<input type="hidden" name="drug_id_selected[' + index + ']" value = "' + $("#drug_select_2 :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="dose_id_selected[' + index + ']" value = "' + $("#dose_select_2 :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="frequency_id_selected[' + index + ']" value = "' + $("#frequency_select_2 :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="period_id_selected[' + index + ']" value = "' + $("#period_select_2 :selected").val() + '">' + '</input>';

        html += '</tr>';

        $("#drug_id").val($("#drug_id").val() + ',' + $("#drug_select_2 :selected").val());

        $('#table_drug > tbody:first-child').append(html);
    }

    $('#table_drug').on('click', '.btn_delete_drug', function () {
        $(this).closest('tr').remove();
    });

</script>
