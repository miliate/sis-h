<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
            <?php 
            switch ($ref_type) {
                case 'EMR':
                    echo Modules::run('leftmenu/emr', $ref_id, $PID, $visits_info);
                    break;
                case 'ADM':
                     echo Modules::run('leftmenu/admission', $visits_info, $ref_id); 
                    break;
                case 'OPD':
                    echo Modules::run('leftmenu/opd', $ref_id, $PID, $visits_info, $is_discharged);
                    break;
                default:
                    echo 'wrong department';
                    break;
            }
            ?>
        </div>
        <div class="col-md-10">
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

            <div id="message1" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('Please insert all data'); ?></span>
                </div> 

                <div id="message2" class="alert alert-danger" style="display:none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="message_text"><?php echo lang('Please insert valid numbers'); ?></span>
                </div> 
 

            
             <div class="panel panel-default">
                    <div class="panel-heading">
                    <?= lang('Prescription') ?>
                    </div>                  
                        <table class="table input-xs">
                            <tbody>
                                <tr class="">
                                    <th>#</th>
                                    <th width="160px"><small><?php echo lang('Name') ?></small></th>
                                    <th width="140px"><small><?php echo lang('Route Administration') ?></small></th>
                                    <th width="100px"><small><?php echo lang('Dose') ?></small></th>
                                    <th width="90px"><small><?php echo lang('Posology') ?></small></th>
                                    <th width="200px"><small><?php echo lang('Duration of Treatment') ?></small></th>
                                    <th width="200px"><small><?php echo lang('Quantity prescribed') ?></small></th>
                                    <th></th>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td><?php echo Modules::run('drug/view_select_drug') ?></td>
                                    <td><?php echo Modules::run('drug/view_route_administration') ?></td>
                                    <td><?php echo '<input type="text" name="dose" id="dose" class="form-control">' ?></td>
                                    <td><?php echo Modules::run('drug/view_select_frequency') ?></td>
                                    <td><?php echo '<input type="number" name="tempo_total" id="tempo_total" class="form-control">' ?></td>
                                    <td><?php echo '<input type="number" name="dose_total" id="dose_total" class="form-control"> ' ?></td>
                                    <td align="center" style="vertical-align: middle;">
                                        <button type="button" class="btn btn-info btn-sm" id="add_drug_button" onclick="add_drug()">
                                            <span class="glyphicon glyphicon-plus-sign"></span>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <div id="prescription_section" style="display: none;">
                    <!--div class="panel-heading"><?= lang('Prescription') ?></div-->                   
                    <form action="" method="post" role="form" class="form-horizontal" style="padding: 10px;">
                        <?php
                        echo validation_errors();
                        $form_generator = new MY_Form();
                        ?>               
                        <div class="panel panel-default">
                        <div class="panel-heading ">
                             <label><?php echo lang('Name') . ': ' . $Name .str_repeat(' ', 6). lang('PID') . ': ' . $PID; ?>
                             </label>                    
                            <!--label><?php echo lang('PID') . ': ' . $PID; ?></label-->
                    </div>
                        <table class="table input-sm" id="table_drug">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th><small><?php echo lang('FNM') ?></th>
                                    <th width="250px"><small><?php echo lang('Name') ?></small></th>
                                    <th width="200px"><small><?php echo lang('Route Administration') ?></small></th>
                                    <th width="30px"><small><?php echo lang('Dose') ?></small></th>
                                    <th  width="180px"><small><?php echo lang('Posology') ?></small></th>
                                    <th width="180px" ><small><?php echo lang('Duration of Treatment') ?></small></th>
                                    <th width="200px"><small><?php echo lang('Quantity prescribed') ?></small></th>
                                    <th></th>
                                </tr>
                            </tbody>
                        </table>
                     </div>
                    <br>

                        <div>
                            <div class ="col-md-12">   
                                <label for="prescription_obs"><?php echo lang('Prescription Observations'); ?></label>
                                <textarea name="prescription_obs" id="prescription_obs" class="form-control" style="width: 100%;"></textarea>
                                <br>
                            </div>
                            <div class ="col-md-12">  
                                 <label>
                                      <?php
                                            echo lang('The Clinic') . ': ';
                                            $name = $this->session->userdata('name');
                                            $othername = $this->session->userdata('othername');
                                            echo $name . ' ' . $othername;
                                         ?>
                                </label>
                                        <br>
                                        <label for=""><?php echo date("d/m/Y"); ?></label></div> 


                        <div class="form-group" style="text-align: right; margin-right: 10px">

                        <button type="button" class="btn btn-warning" onclick="window.history.back()"><?php echo lang('Back') ?></button>
                            <button type="submit" class="btn btn-primary"><?php echo lang('Save') ?></button>
                        </div>

                        </div>

                     
                    </form>
                </div>
              
            </div>
        </div>
    </div>
</div>

<script language="javascript">
    $(document).ready(function() {
        $("#drug_select").select2({
            width: '300px'
        });
        $("#route_administration_select").select2({
            width: '120px'
        });
        $("#frequency_select").select2({
            width: '120px'
        });

        $("#patient_type_select").select2({
            width: '300px'
        });
        $("#patient_type_display").hide();
    });
    var index = 0;

    function add_drug() {
        index++;

        var patient_type_text = $("#patient_type_select :selected").text();
        $("#patient_type_select").val(patient_type_text);

        var selected_drug_text = $("#drug_select :selected").text();
        var drug_parts = selected_drug_text.split(" ");
        var remaining_parts_selected_drug_text = drug_parts.slice(1, -2).join(" ");
        var fnm_text = drug_parts[0];

        var selected_route_administration_text = $("#route_administration_select :selected").text();
        var input_dose_value = $("#dose").val();
        var selected_frequency_text = $("#frequency_select :selected").text();
        var input_time_total_value = $("#tempo_total").val();
        var input_dose_total_value = $("#dose_total").val();
 
        if (selected_route_administration_text === '' || input_dose_value === '' || selected_frequency_text === '' || input_time_total_value === '' || input_dose_total_value === '') {
            $("#message1").fadeIn();
            return;
        } else  if (input_dose_total_value<=0) {
                $("#message2").fadeIn();
                return;
            } else if (input_time_total_value<=0) {
                $("#message2").fadeIn();
                return;
            }

          
      
        var html = '<tr>';
        html += '<td>' + index + '</td>';
        html += '<td>' + fnm_text + '</td>';
        html += '<td>' + remaining_parts_selected_drug_text + '</td>';
        html += '<td>' + selected_route_administration_text + '</td>';
        html += '<td>' + input_dose_value + '</td>';
        html += '<td>' + selected_frequency_text + '</td>';
        html += '<td>' + input_time_total_value + '</td>';
        html += '<td>' + input_dose_total_value + '</td>';
        html += '<td align="center">' + '<button class="btn btn-danger btn-xs btn_delete_drug" type="button"><span class="glyphicon glyphicon-trash"></span></button>' + '</td>';
        html += '<input type="hidden" name="drug_id_selected[' + index + ']" value = "' + $("#drug_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="route_administration_id_selected[' + index + ']" value = "' + $("#route_administration_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="input_dose_value[' + index + ']" value = "' + $("#dose").val() + '">' + '</input>';
        html += '<input type="hidden" name="frequency_id_selected[' + index + ']" value = "' + $("#frequency_select :selected").val() + '">' + '</input>';
        html += '<input type="hidden" name="input_time_total_value[' + index + ']" value = "' + $("#tempo_total").val() + '">' + '</input>';
        html += '<input type="hidden" name="input_dose_total_value[' + index + ']" value = "' + $("#dose_total").val() + '">' + '</input>';
        html += '</tr>';

        $("#drug_id").val($("#drug_id").val() + ',' + $("#drug_select :selected").val());
        $('#table_drug > tbody').append(html);

        // Clear the input fields
        $("#dose").val("");
        $("#tempo_total").val("");
        $("#dose_total").val("");
        $("#drug_select").val(null).trigger('change');
        $("#route_administration_select").val(null).trigger('change');
        $("#frequency_select").val(null).trigger('change');

        $("#message1").fadeOut();
        $("#message2").fadeOut();
        togglePrescriptionSection();
    }

    // Event delegation for dynamically added buttons
    $(document).on('click', '.btn_delete_drug', function() {
        $(this).closest('tr').remove();
        togglePrescriptionSection();
    });

    function togglePrescriptionSection() {
        var rowCount = $("#table_drug tr").length;
        if (rowCount > 1) { // Row count greater than 1 means there are drugs in the table (excluding the header row)
            $("#prescription_section").show();
        } else {
            $("#prescription_section").hide();
        }
    }
</script>