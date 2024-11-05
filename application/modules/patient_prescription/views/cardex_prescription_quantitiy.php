<div class="row">
    <div class="col-md-2">
        <?php echo Modules::run('leftmenu/preference'); ?>
    </div>

    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4><?= lang('Quantity Of Drugs Prescribed In Rooms'); ?></h4>
                <hr>

                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px; display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span id="message-content"></span>
                </div>

                <div class="form-inline" style="margin-top: 20px;">
                    <div class="row w-100">
                        <div class="form-group col-md-4">
                            <label class="control-label"><?= lang('Ward') ?>:</label>
                            <select class="form-control" name="wardID" id="wardID" required>
                                <option value=""><?= lang('Select a Ward') ?></option>
                                <?php foreach ($wards as $ward): ?>
                                    <option value="<?= $ward->WID ?>"><?= $ward->Name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label"><?= lang('Room') ?>:</label>
                            <select class="form-control" name="roomID" id="roomID" required disabled>
                                <option value=""><?= lang('Select a Room') ?></option>
                            </select>
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label"><?= lang('Date') ?>:</label>
                            <input type="date" class="form-control" name="reportDate" id="reportDate" value="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="form-group col-md-2 text-right">
                            <button type="button" class="btn btn-primary" id="btn-generate"><?= lang('Generate') ?></button>
                        </div>
                    </div>
                    <hr>
                </div>

                <div id="table-container" class="row" style="margin-top: 20px; display: none;">
                    <div class="col-lg-12 col-md-12">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?= lang('Drug') ?></th>
                                    <th><?= lang('FNM') ?></th>
                                    <th><?= lang('Dosage') ?></th>
                                    <th><?= lang('Quantity') ?></th>
                                    <th><?= lang('Dispense Quantity') ?></th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Os dados serão inseridos aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Botão Salvar abaixo da tabela -->
                <div class="text-right" id="save-button-container" style="margin-top: 20px; display: none;">
                    <button type="button" class="btn btn-success" id="btn-save"><?= lang('Save') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var messages = {
        wardRequired: "The Ward field is required",
        roomRequired: "The Room field is required",
        dateRequired: "The Date field is required",
        noDataFound: "No data found for the selected room and date",
        errorOccurred: "An error occurred while processing the request",
        saveSuccess: "Data saved successfully!",
        saveError: "Error saving data. Please try again.",
    };

    $('#wardID').change(function() {
        let wardID = $(this).val();
        $('#roomID').prop('disabled', true).empty().append('<option value=""><?= lang('Select a Room') ?></option>');
        $.ajax({
            url: "<?php echo site_url(); ?>/patient_prescription/get_rooms_by_ward/"+wardID,
            type: "get",
            dataType: "json",
            success: function(response) {
                $.each(response.data, function(index, room) {
                    $('#roomID').append(`<option value="${room.RID}">${room.Name}</option>`);
                });
                $('#roomID').prop('disabled', false);
            },
            error: function() {
                $('#message-content').text(messages.errorOccurred);
                $('#message').fadeIn();
            }
        });
    });

    $('#btn-generate').click(function() {
        let wardID = $('#wardID').val();
        let roomID = $('#roomID').val();
        let reportDate = $('#reportDate').val();

        $('#message').fadeOut();
        $('#table-container').fadeOut();
        $('#save-button-container').fadeOut();
        $('#table-body').empty();

        if (!wardID) {
            $('#message-content').text(messages.wardRequired);
            $('#message').fadeIn();
            return;
        }

        if (!roomID) {
            $('#message-content').text(messages.roomRequired);
            $('#message').fadeIn();
            return;
        }

        if (!reportDate) {
            $('#message-content').text(messages.dateRequired);
            $('#message').fadeIn();
            return;
        }

        $.ajax({
            url: "<?= site_url('patient_prescription/cardex_prescription_quantitiy') ?>",
            type: "POST",
            dataType: "json",
            data: { roomID: roomID, reportDate: reportDate },
            success: function(response) {
                if (response.status === 'success') {
                    let data = response.data;
                    if (data.length > 0) {
                        $.each(data, function(index, item) {
                            $('#table-body').append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.DrugName}</td>
                                    <td>${item.PharmaceuticalForm}</td>
                                    <td>${item.DrugDosage}</td>
                                    <td>${item.TotalQuantity}</td>
                                    <td>
                                        <input type="number" name="dispense_quantity_${item.DrugID}" class="form-control dispense-quantity" min="0" max="${item.TotalQuantity}" value="${item.DispendQuantity}" ">
                                    </td>
                                   <td style="display: none;">${item.DrugId}</td>
                                </tr>
                            `);
                        });
                        $('#table-container').fadeIn();
                        $('#save-button-container').fadeIn(); 
                    } else {
                        $('#message-content').text(messages.noDataFound);
                        $('#message').fadeIn();
                    }
                } else {
                    $('#message-content').text(response.message);
                    $('#message').fadeIn();
                }
            },
            error: function() {
                $('#message-content').text(messages.errorOccurred);
                $('#message').fadeIn();
            }
        });
    });

    $('#btn-save').click(function() {
        let wardID = $('#wardID').val();
        let roomID = $('#roomID').val();
        let reportDate = $('#reportDate').val();
        let dataToSave = [];

        $('#table-body tr').each(function() {
            let drugName = $(this).find('td').eq(1).text();
            let drugFNM = $(this).find('td').eq(2).text();
            let drugDosage = $(this).find('td').eq(3).text();
            let totalQuantity = $(this).find('td').eq(4).text();
            let drugId = $(this).find('td').eq(6).text();
            let dispenseQuantity = $(this).find('input.dispense-quantity').val();

            if (dispenseQuantity && dispenseQuantity > 0) {
                dataToSave.push({
                    wardID: wardID,
                    roomID: roomID,
                    reportDate: reportDate,
                    drugName: drugName,
                    drugId: drugId,
                    drugFNM: drugFNM,
                    drugDosage: drugDosage,
                    totalQuantity: totalQuantity,
                    dispenseQuantity: dispenseQuantity
                });
            }
        });

        if (dataToSave.length > 0) {
            $.ajax({
                url: "<?= site_url('patient_prescription/save_cardex_dispensed') ?>",
                type: "POST",
                dataType: "json",
                data: { dispensedData: dataToSave },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#message-content').text(messages.saveSuccess);
                        $('#message').fadeIn();
                    } else {
                        $('#message-content').text(response.message);
                        $('#message').fadeIn();
                    }
                },
                error: function() {
                    $('#message-content').text(messages.saveError);
                    $('#message').fadeIn();
                }
            });
        } else {
            $('#message-content').text(messages.noDataFound);
            $('#message').fadeIn();
        }
    });
</script>
