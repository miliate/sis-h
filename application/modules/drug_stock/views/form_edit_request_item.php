<?php
$request_codes = $request_details[0]['request_code'];
$request_id = $request_details[0]['request_id'];
$status = $request_details[0]['status'];
?>

<div id="message1" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('Please enter a valid quantity greater than zero'); ?></span>
</div>

<div id="message2" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('This drug has already been added.'); ?></span>
</div>

<div id="message3" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('Please enter the request code'); ?></span>
</div>

<div id="message4" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('Error fetching drug details. Please try again.'); ?></span>
</div>

<div id="message5" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('Please add at least one drug'); ?></span>
</div>

<form action="<?php echo site_url('drug_stock/update_request'); ?>" method="post" id="requisition_form" role="form" class="form-horizontal" style="padding-top: 10px;">
    <?php echo validation_errors(); ?>
    <div class="panel">
        <div class="panel-body">
            <div class="form-group row">
                <div class="col-sm-4">
                    <label class="control-label col-sm-3"><?php echo lang('Request Code'); ?></label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="request_code" id="request_code" value="<?php echo $request_codes; ?>" readonly>
                    </div>
                </div>
                <div class="col-sm-4">
                    <label class="control-label col-sm-4"><?php echo lang('Request Type'); ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="request_type_display" id="request_type_display" value="<?php echo ($request_details[0]['request_type'] == 'normal') ? lang('Normal Request') : lang('Emergency Request'); ?>" readonly>
                        <input type="hidden" name="request_type" id="request_type" value="<?php echo $request_details[0]['request_type']; ?>">
                    </div>
                </div>
                <div class="col-sm-4">
                    <label class="control-label col-sm-4"><?php echo lang('Status'); ?></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="status" id="status" value="<?php echo ($status == 'pending') ? lang('Pending') : lang('Completed'); ?>" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h2 class="panel-title"><?php echo lang('Request Items'); ?></h2>
            </div>

            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo lang('National Form Code'); ?></th>
                        <th><?php echo lang('Name'); ?></th>
                        <th><?php echo lang('Dosage'); ?></th>
                        <th><?php echo lang('Pharmaceutical Form'); ?></th>
                        <th><?php echo lang('Requested Quantity'); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="tbody_drug">
                    <?php foreach ($request_details as $index => $item): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td>
                                <input type="hidden" name="national_form_code[<?php echo $index; ?>]" value="<?php echo $item['fnm']; ?>">
                                <?php echo $item['fnm']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="name[<?php echo $index; ?>]" value="<?php echo $item['name']; ?>">
                                <?php echo $item['name']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="dosage[<?php echo $index; ?>]" value="<?php echo $item['dosage']; ?>">
                                <?php echo $item['dosage']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="pharmaceutical_form[<?php echo $index; ?>]" value="<?php echo $item['pharmaceutical_form']; ?>">
                                <?php echo $item['pharmaceutical_form']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="requested_quantity[<?php echo $index; ?>]" value="<?php echo $item['requested_quantity']; ?>">
                                <?php echo $item['requested_quantity']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="who_drugs_id[<?php echo $index; ?>]" value="<?php echo $item['who_drugs_id']; ?>">
                            </td>
                            <td align="center">
                                <button class="btn btn-danger btn_delete_drug" type="button"><?php echo lang('Delete'); ?></button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-success" id="submit_button"><?php echo lang('Submit'); ?></button>
        </div>

    </div>


    <div id="panel_drugs" class="panel with-nav-tabs panel-info">
        <div class="panel-heading">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab1default" data-toggle="tab"><?php echo lang('All Drugs'); ?></a></li>
            </ul>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div class="tab-pane fade in active" id="tab1default">
                    <table class="table input-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="300px"><?php echo lang('Name') ?></th>
                                <th><?php echo lang('Quantity') ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                                <td><?php echo Modules::run('drug/view_select_drug') ?></td>
                                <td><?php echo '<input type="number" name="dose_total" id="dose_total" class="form-control">' ?></td>
                                <td align="center" style="vertical-align: middle;">
                                    <button type="button" class="btn btn-info" id="add_drug_button">
                                        <span class="glyphicon glyphicon-plus-sign"></span>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $("#drug_select").select2({
                                width: '300px'
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
$(document).ready(function() {
    const form = $('#requisition_form');
    const requestCode = $('#request_code').val().trim();
    const requestType = $('#request_type').val();
    const status = $('#status').val();
    const requestedBy = "<?php echo $request_details[0]['request_create_user']; ?>";
    let items = <?php echo json_encode($request_details); ?>;

    function add_drug() {
        var dose_total_value = $("#dose_total").val();
        var selected_drug_value = $("#drug_select").val();

        // Validação da quantidade
        if (dose_total_value <= 0 || isNaN(dose_total_value)) {
            $("#message1").fadeIn();
            return;
        }

        // Verifica se o medicamento já está na lista
        var existing_drug = items.find(function(drug) {
            return drug.who_drugs_id === selected_drug_value;
        });

        if (existing_drug) {
            $("#message2").fadeIn();
            return;
        }

        $.ajax({
            url: "<?php echo base_url() ?>index.php/drug_stock/request_drug/" + selected_drug_value,
            type: 'GET',
            success: function(response) {
                try {
                    const drugDetails = JSON.parse(response);

                    const drug_data = {
                        request_id: drugDetails.request_id || '',
                        request_code: requestCode,
                        who_drugs_id: selected_drug_value,  
                        fnm: drugDetails.fnm || '',
                        name: drugDetails.name || '',
                        dosage: drugDetails.dosage || '',
                        pharmaceutical_form: drugDetails.pharmaceutical_form || '',
                        request_type: requestType,
                        requested_quantity: dose_total_value,
                    };

                    items.push(drug_data); 
                    update_table(); 
                    $('#dose_total').val(''); 
                } catch (error) {
                    $("#message4").fadeIn();
                }
            }
        });
    }

    function update_table() {
        const tbody = $('#tbody_drug');
        tbody.empty();

        items.forEach((drug, index) => {
            const row = $('<tr></tr>');
            row.append(`
                <td>${index + 1}</td>
                <td><input type="hidden" name="national_form_code[${index}]" value="${drug.fnm || ''}">${drug.fnm || ''}</td>
                <td><input type="hidden" name="name[${index}]" value="${drug.name || ''}">${drug.name || ''}</td>
                <td><input type="hidden" name="dosage[${index}]}" value="${drug.dosage || ''}">${drug.dosage || ''}</td>
                <td><input type="hidden" name="pharmaceutical_form[${index}]" value="${drug.pharmaceutical_form || ''}">${drug.pharmaceutical_form || ''}</td>
                <td><input type="hidden" name="requested_quantity[${index}]" value="${drug.requested_quantity || ''}">${drug.requested_quantity || ''}</td>
                <td><input type="hidden" name="who_drugs_id[${index}]" value="${drug.who_drugs_id || ''}"></td>
                <td align="center">
                    <button class="btn btn-danger btn_delete_drug" type="button"><?php echo lang('Delete'); ?></button>
                </td>
            `);
            tbody.append(row);
        });

        // Ocultar botões de delete se o status não for 'Pending'
        if (status !== '<?php echo lang('Pending'); ?>') {
            $('.btn_delete_drug').hide();
        }

        $('.btn_delete_drug').on('click', function() {
            const index = $(this).closest('tr').index();
            items.splice(index, 1);
            update_table();
        });
    }

    $('#add_drug_button').on('click', add_drug);

    form.on('submit', function(event) {
        event.preventDefault();

        if (requestCode === '') {
            $("#message3").fadeIn();
            return false;
        }

        if (items.length === 0) {
            $("#message5").fadeIn();
            return false;
        }

        var requestId = <?php echo json_encode($request_id); ?>;
        const formData = {
            request_id: requestId,
            request_code: requestCode,
            request_type: requestType,
            status: status,
            requested_by: requestedBy,
            items: items
        };

        $.ajax({
            url: "<?php echo site_url('drug_stock/update_request') ?>",
            type: 'POST',
            data: formData,
            success: function(response) {
                window.location.href = "<?php echo site_url('drug_stock/show_request'); ?>";
            }
        });

        return false;
    });

    // Verificação do status
    if (status !== '<?php echo lang('Pending'); ?>') {
        $('#submit_button').hide();
        $('#panel_drugs').hide();
    }

    update_table();
});
</script>