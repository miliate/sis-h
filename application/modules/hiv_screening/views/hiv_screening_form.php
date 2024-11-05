<div class="container-fluid">
    <div class="row">
        <!-- Menu à esquerda -->
        <div class="col-md-2">
            <?php
            echo Modules::run('leftmenu/patient', $PID); 
            ?>
        </div>

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <b><?php echo "<i class='fa fa-group'></i> " . lang('Active List') . " - PNCT"; ?></b>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-body">
                    <?php 
                    $form_generator = new MY_Form(); 
                    $form_generator->get_pnct_forms_menu($PID); 
                    ?>
                </div>
            </div>

            <!-- Formulário de Rastreio de HIV -->
            <div class="panel">
                <div class="panel-body">
                    <form id="hiv_screening_form" role="form" action="<?php echo site_url('hiv_screening/create'); ?>" method="post">
                        <input type="hidden" name="PID" value="<?php echo $PID; ?>" />

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="test_result"><?php echo lang('Test Result'); ?></label>
                                <select class="form-control form-control-sm" name="test_result" id="test_result">
                                    <option value="Positivo"><?php echo lang('Positive'); ?></option>
                                    <option value="Negativo"><?php echo lang('Negative'); ?></option>
                                </select>
                                <?php echo form_error('test_result', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="test_date"><?php echo lang('Test Date'); ?></label>
                                <input type="date" class="form-control form-control-sm" name="test_date" id="test_date" value="<?php echo set_value('test_date'); ?>" />
                                <?php echo form_error('test_date', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-4">
                                <div class="form-check" style="margin-top: 25px">
                                    <input class="form-check-input" type="checkbox" id="current_tarv_checked" name="is_tarv_started" value="1" />
                                    <label class="form-check-label" for="current_tarv_checked">
                                        <?php echo lang('Currently on TARV'); ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="tarv_fields" style="display: none;">
                            <div class="form-group col-md-3">
                                <label for="NIDTARV"><?php echo lang('NID TARV'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="NIDTARV" id="NIDTARV" placeholder="_______/____/___"  value="<?php echo set_value('NIDTARV'); ?>" />
                                <?php echo form_error('NIDTARV', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="tarv_start_date"><?php echo lang('TARV Start Date'); ?></label>
                                <input type="date" class="form-control form-control-sm" name="tarv_start_date" id="tarv_start_date" value="<?php echo set_value('tarv_start_date'); ?>" />
                                <?php echo form_error('tarv_start_date', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="current_tarv"><?php echo lang('Current TARV'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="current_tarv" id="current_tarv" value="<?php echo set_value('current_tarv'); ?>" />
                                <?php echo form_error('current_tarv', '<div class="text-danger">', '</div>'); ?>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="tpc"><?php echo lang('TPC'); ?></label>
                                <select class="form-control form-control-sm" name="tpc" id="tpc">
                                    <option value="Nao"><?php echo lang('No'); ?></option>
                                    <option value="Sim"><?php echo lang('Yes'); ?></option>
                                    <option value="Nao pode"><?php echo lang('Not Applicable'); ?></option>
                                </select>
                                <?php echo form_error('tpc', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>

                        <!-- Campo adicional para o tipo de TPC -->
                        <div class="row" id="tpc_fields" style="display: none;">
                            <div class="form-group col-md-6">
                                <label for="type_tpc"><?php echo lang('Treatment'); ?></label>
                                <input type="text" class="form-control form-control-sm" name="type_tpc" id="type_tpc" value="<?php echo set_value('type_tpc'); ?>" />
                                <?php echo form_error('type_tpc', '<div class="text-danger">', '</div>'); ?>
                            </div>
                        </div>

                        <div class="row text-center" style="margin-top: 25px;">
                            <button type="submit" class="btn btn-primary"><?php echo lang('Save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Rastreios de HIV -->
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('HIV Screening Records'); ?></div>
                <div class="panel-body">
                    <div style="height: 200px; overflow-y: auto;">
                        <table class="table table-striped input-sm" id="table_hiv_screening">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo lang('Test Date'); ?></th>
                                    <th><?php echo lang('Test Result'); ?></th>
                                    <th><?php echo lang('Currently on TARV'); ?></th>
                                    <th><?php echo lang('NID TARV'); ?></th>
                                    <th><?php echo lang('TARV Start Date'); ?></th>
                                    <th><?php echo lang('Current TARV'); ?></th>
                                    <th><?php echo lang('TPC'); ?></th>
                                    <th><?php echo lang('Treatment'); ?></th>
                                    <th><?php echo lang('Actions'); ?></th>
                                </tr>
                            </thead>
                            <tbody id="tbody_hiv_screening">
                                <?php if (!empty($screenings)): ?>
                                    <?php foreach ($screenings as $index => $screening): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td><?php echo $screening['test_date']; ?></td>
                                            <td><?php echo $screening['test_result']; ?></td>
                                            <td><?php echo $screening['is_tarv_started'] == 1 ? 'Sim' : 'Não'; ?></td>
                                            <td><?php echo $screening['NIDTARV']; ?></td>
                                            <td><?php echo $screening['tarv_start_date']; ?></td>
                                            <td><?php echo $screening['current_tarv']; ?></td> 
                                            <td><?php echo $screening['tpc']; ?></td>
                                            <td><?php echo $screening['type_tpc']; ?></td>
                                            <td align="center">
                                                <a href="<?php echo site_url('hiv_screening/invalidate/' . $screening['id']); ?>" class="btn btn-warning btn-sm" onclick="return confirm('<?php echo lang('Are you sure you want to invalidate this screening?'); ?>');"><?php echo lang('Invalidate'); ?></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9"><?php echo lang('No screenings found'); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        function updateTarvFields() {
            if ($('#current_tarv_checked').is(':checked')) {
                $('#tarv_fields').show();
            } else {
                $('#tarv_fields').hide();
            }
        }

        function updateTpcFields() {
            if ($('#tpc').val() === 'Sim') {
                $('#tpc_fields').show();
            } else {
                $('#tpc_fields').hide();
            }
        }

        // Inicializar a lógica ao carregar a página
        updateTarvFields();
        updateTpcFields();

        // Listeners para detectar mudanças
        $('#current_tarv_checked').change(updateTarvFields);
        $('#tpc').change(updateTpcFields);

        // Limpa datas inválidas
        $('#table_hiv_screening tbody tr').each(function() {
            $(this).find('td').each(function() {
                var text = $(this).text();
                if (text === '0000-00-00') {
                    $(this).text('');
                }
            });
        });
    });
</script>

<style>
    #table_hiv_screening thead th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
        border-bottom: 2px solid #ddd;
    }

    #table_hiv_screening {
        border-collapse: collapse;
        width: 100%;
    }

    #table_hiv_screening tbody tr td {
        white-space: nowrap;
    }
</style>
