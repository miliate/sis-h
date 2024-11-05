<div class="row">
    <div class="col-md-2 ">
        <?php
        echo Modules::run('leftmenu/preference');
        ?>
    </div>
    <div class="col-md-10 ">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php $form_generator = new MY_Form(lang('drug_stock'));

                $form_generator->get_pharmacy_manager_reports_menu();

                ?>
                <h4><?= lang('Medication Entry Report') ?></h4>
                <hr>
                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px;display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <?= lang('Dates Missing Message') ?>

                </div>
                <div class="form-inline" style="margin-top: 20px;">
                    <div class="form-group col-md-4">
                        <label class="control-label"><?= lang('Start Date') ?>:</label>

                        <input type="date" class="form-control" name="startDate" id="startDate" />


                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label"><?= lang('End Date') ?>:</label>
                        <input type="date" class="form-control" name="endDate" id="endDate" />
                    </div>
                    <button type="" class="btn btn-default" id="btn-generate"><?= lang('Generate') ?></button>


                </div>

                <div id="table-container" class="row" style=" display: none;">
                    <div class="col-lg-12 col-md-12">
                        <div class="row">
                            <div class="form-inline" style="margin: 15px;margin-top: 30px;">
                                <div class="input-group col-md-4">
                                    <input type="text" id="filter" class="form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-primary" id="btn-filter">
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-12" style="overflow:auto; height: 660px;">
                        <table class="table table-responsive table-stripped" id="tb-report">
                            <thead>
                                <tr>
                                    <th>
                                        <?= lang('FNM') ?>
                                    </th>
                                    <th> <?= lang('Drug Name') ?></th>
                                    <th><?= lang('Pharmaceutical Form') ?></th>
                                    <th><?= lang('Dosage') ?></th>
                                    <th><?= lang('Entries') ?></th>
                                    <th><?= lang('Positive Adjustment') ?></th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $('#btn-generate').click(function() {
        let startDate = $('#startDate').val().trim();
        let endDate = $('#endDate').val().trim();

        $('#message').fadeOut();

        if (startDate === '' || endDate === '') {
            $('#message').fadeIn();
            return;

        }

        $('#table-container').fadeIn();

        $.get(`getDrugEntries/${startDate}/${endDate}`, function(data) {

            $('#tb-report tbody').empty();

            $.each(data, function(k, record) {

                $("#tb-report").find('tbody')
                    .append($('<tr>')
                        .append($('<td>').append(record.fnm))
                        .append($('<td>').append(record.name))
                        .append($('<td>').append(record.form))
                        .append($('<td>').append(record.dosage))
                        .append($('<td>').append(record.entries))
                        .append($('<td>').append(record.adjustments))

                    );
            });
        })
    });


    $('#btn-filter').click(function() {
        var value = $('#filter').val().toLowerCase();
        var hasVisibleRow = false;

        $('#tb-report tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            if ($(this).is(':visible')) {
                hasVisibleRow = true;
            }
        });
    });
</script>
<style>
    thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
    }
</style>