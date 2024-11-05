<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php
            $form_generator = new MY_Form(lang('Add Drugs to Stock'));
            $form_generator->form_open_current_url();
            $form_generator->input(lang('Drug Name'), 'drug_name', $drug_name, lang('Drug Name'), 'disabled');
            $form_generator->input(lang('Existing Stock'), 'existing_stock', $default_existing_stock, $current_drug_count, 'disabled');
            echo '<div class="all" id="existingStockBatch">';
            $form_generator->input(lang('Existing Stock Batch'), 'existing_stock_batch', $default_existing_stock_batch, lang('Existing Stock Batch'), 'disabled');
            echo '</div>';
            $form_generator->dropdown(lang('Movement'), 'mov', $option = array('Entries' => lang('Entries'), 'Negative Adjustment' => lang('Negative Adjustment'), 'Positive Adjustment' => lang('Positive Adjustment'), 'Waste' => lang('Waste'), 'Consumption' => lang('Consumption')), lang('Movement'), '');
            echo '<div class="all" id="comefrom">';
            $form_generator->input('*' . lang('Come From'), 'come_from', $default_come_from, lang('Come From'));
            echo '</div>';
            echo '<div  id="entries">';
            $form_generator->input(lang('Quantity'), 'quantity', $default_quantity, lang('Quantity'));
            echo '</div>';
            echo '<div class="all" id="dest">';
            $form_generator->input('*' . lang('Destination'), 'destination', $defautl_destination, lang('Destination'));
            echo '</div>';
            echo '<div class="all" id="drugs_id">';
            $form_generator->input(lang('Batch'), 'drug_id',  $drug_id, '', 'hide');
            echo '</div>';
            echo '<div class="all" id="BatchRetrieve">';
            $form_generator->dropdown(lang('Batch'), 'lotes', $dropdown_lotes, $default_lotes, 'onchange="fetchBatchDeadline(this.value);"');
            $form_generator->input_date_only_future(lang('Batch Deadline'), 'batch_deadlines', $default_batch_deadline, 'YYYY / MM / DD', 'readonly');
            echo '</div>';
            echo '<div class="all" id="BatchEntries">';
            $form_generator->input(lang('Batch'), 'lote',  $default_lote, lang('Batch'));
            $form_generator->input_date_only_future(lang('Batch Deadline'), 'batch_deadline', $default_batch_deadline, 'YYYY / MM / DD', '');
            echo '</div>';
            $form_generator->input('*' . lang('Document Number'), 'doc_no', $default_doc_no, lang('Document Number'));
            $form_generator->input(lang('Signature'), 'signature', $default_signature, lang('Signature'));
            $form_generator->button_submit_reset();
            $form_generator->form_close();
            ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
    function showHideElements(elementsToShow, elementsToHide) {
        $('div[class="all"]').hide();
        elementsToShow.forEach(function(id) {
            $('div[id="' + id + '"]').show();
        });
        elementsToHide.forEach(function(id) {
            $('div[id="' + id + '"]').hide();
        });
    }

    function handleMovementChange(selectedOption) {
        switch (selectedOption) {
            case 'Entries':
                showHideElements(['entries', 'comefrom', 'BatchEntries'], ['BatchRetrieve']);
                break;
            case 'Waste':
                showHideElements(['exit', 'BatchRetrieve', 'existingStockBatch'], ['BatchEntries']);
                break;
            case 'Consumption':
                showHideElements(['consumption', 'BatchRetrieve', 'existingStockBatch'], ['BatchEntries']);
                break;
            case 'Negative Adjustment':
                showHideElements(['NegativeAdjs', 'dest', 'BatchRetrieve', 'existingStockBatch'], ['BatchEntries']);
                break;
            case 'Positive Adjustment':
                showHideElements(['positiveAdjs', 'comefrom', 'BatchEntries'], ['BatchRetrieve']);
                break;
        }
    }

    // Inicializar os elementos com base no valor selecionado
    handleMovementChange($('#mov').val());

    $('#mov').change(function() {
        var selectedOption = $(this).val();
        // Limpar os campos antes de fazer a requisição AJAX
        $('#batch_deadlines').val('');
        $('#lotes').val('');
        handleMovementChange(selectedOption);
    });
});


    function fetchBatchDeadline(batch) {
        var drug_id = $('#drug_id').val();
        $.ajax({
            url: "<?php echo base_url('index.php/drug_stock/get_batch_details'); ?>",
            type: "POST",
            data: {
                batch: batch,
                drug_id: drug_id
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#batch_deadlines').val(response.batch_deadline);
                    $('#existing_stock_batch').val(response.existing_stock);
                } else {
                    console.log('Batch details not found');
                    $('#batch_deadlines, #existing_stock_batch').val('');
                }
            },
            error: function() {
                alert('Error fetching batch deadline.');
            }
        });
    }
</script>