<div class="row">
    <div class="col-md-2">
        <?php
        echo Modules::run('leftmenu/preference'); //runs the available left menu for preferance
        ?>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php $form_generator = new MY_Form(lang('Drug'));

                $form_generator->get_pharmacy_manager_reports_menu();

                ?>
                <h4><?= lang('Stock inventory'); ?></h4>
                <div class="form-inline" style="margin-top: 20px;">
                    <label for="filterInput" class="mr-2"><?= lang('Search'); ?>:</label>

                    <input type="text" id="filterInput" onkeyup="filterTable()" placeholder="<?= lang('Filter by Drug Name'); ?>" class="form-control">
                </div>
            </div>
            <?php if (!empty($who_drugs)) : ?>
                <div class="col-md-12" style="height: 750px; overflow-y: auto;">
                    <table class="table table-condensed table-striped table-hover" id="drugTable">
                        <thead>
                            <tr>
                                <th><?= lang('FNM') ?></th>
                                <th><?= lang('Drug Name'); ?></th>
                                <th><?= lang('Pharmaceutical Form'); ?></th>
                                <th><?= lang('Dosage'); ?></th>
                                <th><?= lang('In Stock'); ?></th>
                                <th><?= lang('Expiration Date'); ?></th>
                                <th><?= lang('Actions'); ?></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($who_drugs as $drug) : ?>
                                <tr>
                                    <td><?= $drug->fnm ?></td>
                                    <td><?= $drug->name ?></td>
                                    <td><?= $drug->pharmaceutical_form; ?></td>
                                    <td><?= $drug->dosage ?></td>
                                    <td>
                                        <strong id="cell_<?= $drug->wd_id; ?>" class="label <?= ($drug->count > $this->config->item('drug_alert_count')) ? 'label-success' : 'label-warning'; ?>">
                                            <?= $drug->count; ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <a id="inpu__<?= $drug->wd_id; ?>" type="button" class="btn btn-default btn-sm" data-toggle="modal" href="<?= site_url("/report/pdf/loteStock/view/" . $drug->wd_id); ?>" data-target="#drug_stock_2"><i class="fa fa-eye"></i></a>
                                    </td>
                                    <td>
                                        <a id="btn_<?= $drug->wd_id; ?>" type="button" href="<?= site_url("/drug_stock/create/" . $drug->wd_id); ?>" class="btn btn-info btn-sm"> + </a>
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-success btn-sm" data-toggle="modal" href="<?= site_url("/report/pdf/drugStock/view/" . $drug->wd_id); ?>" data-target="#drug_stock_<?= $drug->wd_id; ?>"><?= lang('Stock sheet'); ?> <i class="fa fa-eye"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php foreach ($who_drugs as $drug) : ?>
                    <div class="modal fade" id="drug_stock_<?= $drug->wd_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <?= Modules::run('report/pdf/drugStock', 'view', $drug->wd_id); ?>
                    </div>
                <?php endforeach; ?>
                <div class="modal fade" id="drug_stock_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <?= Modules::run('report/pdf/loteStock', 'view'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script language="javascript">
    function add_stock(drug_id) {
        var add_count = parseInt($(String("#inp_" + drug_id)).val());

        if (add_count) {
            var request = $.ajax({
                url: "<?php echo base_url(); ?>index.php/drug_stock/add_stock/",
                type: "post",
                data: {
                    "drug_id": drug_id,
                    "count": add_count
                }
            });
            request.done(function(response, textStatus, jqXHR) {
                if (response == 'success') {
                    var old_count = $("#cell_" + drug_id).text();
                    $("#cell_" + drug_id).text(parseInt(add_count) + parseInt(old_count));
                    $("#inp_" + drug_id).val("");
                    $("#inpu__" + drug_id).val("");
                }
            });
        }
    }
</script>

<script language="javascript">
    function filterTable() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("filterInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("drugTable");
        tr = table.getElementsByTagName("tr");
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }
</script>
<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
    }
</style>
