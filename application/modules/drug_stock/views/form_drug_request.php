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
    <span id="message_text"><?php echo  lang('Please enter the request code');?></span>
</div>
<div id="message4" class="alert alert-danger" style="display:none;">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <span id="message_text"><?php echo lang('Error fetching drug details. Please try again.'); ?></span>
</div>

<form action="<?php echo site_url('drug_stock/dispense_drug'); ?>" id="requisition_form" method="post" role="form" class="form-horizontal" style="padding-top: 10px;">
    <?php echo validation_errors(); ?>

    <div class="panel-body">
        <div class="form-group row">
            <div class="col-sm-4 d-flex align-items-center">
                <label class="control-label col-sm-3"><?php echo lang('Request Code'); ?></label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="request_code" id="request_code" value="<?php echo $request_code; ?>" readonly>
                </div>
            </div>
            
            <div class="col-sm-4 d-flex align-items-center">
                <label class="control-label col-sm-3"><?php echo lang('Request Type'); ?></label>
                <div class="col-sm-7">
                    <select class="form-control" name="request_type" id="request_type">
                        <option value="normal"><?php echo lang('Normal Request'); ?></option>
                        <option value="emergency"><?php echo lang('Emergency Request'); ?></option>
                    </select>
                </div>
            </div>
            
            <div class="col-sm-4 text-right">
                <a href="<?php echo site_url('drug_stock/show_request'); ?>" class="btn btn-success" style="margin-top: 0;">
                    <?php echo lang('Show Request'); ?>
                </a>
            </div>
        </div>
    </div>
    
    <div class="panel panel-primary" id="requisition_panel" style="display:none;">
        <div class="panel-heading"><?php echo lang('Request') ?></div>
        <div class="panel-body">
            <div style="height: 300px; overflow-y: auto;">
                <table class="table input-sm" id="table_drug">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th><?php echo lang('National Form Code') ?></th>
                            <th width="400px"><?php echo lang('Name') ?></th>
                            <th><?php echo lang('Dosage') ?></th>
                            <th><?php echo lang('Pharmaceutical Form') ?></th>
                            <th><?php echo lang('Existing Stock') ?></th>
                            <th><?php echo lang('Requested Quantity') ?></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_drug">
                        <tr>
                            <td colspan="8"><?php echo lang('No items found'); ?></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8">
                                <div style="display: flex; justify-content: space-between;">
                                    <label style="flex: 1;">
                                        <?php
                                        echo lang('Pharmacy Technician') . ': ';
                                        $name = $this->session->userdata('name');
                                        $othername = $this->session->userdata('othername');
                                        echo $name . ' ' . $othername;
                                        ?>
                                    </label>
                                    <label style="flex: 1; text-align: center;">
                                        <?php 
                                        $current_date = date("d/m/Y"); 
                                        echo $current_date; 
                                        ?>
                                    </label>
                                    <label style="flex: 1; text-align: right;">
                                        <?php
                                        $day = (int)date("d");
                                        if ($day >= 21 || $day <= 5) {
                                            $quinzena = lang('First Fortnight');
                                        } else {
                                            $quinzena = lang('Second Fortnight');
                                        }
                                        echo $quinzena;
                                        ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Form Actions -->
            <div class="form-group" style="text-align: center">
                <button type="submit" class="btn btn-primary" ><?php echo lang('Save'); ?></button>
            </div>
        </div>
    </div>
</form>


<div class="panel with-nav-tabs panel-info">
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

<script type="text/javascript">
$(document).ready(function() {
    var drug_list = []; // Array para armazenar os dados dos medicamentos

    // Evento ao clicar no botão "Add Drug"
    $("#add_drug_button").click(function() {
        add_drug();
    });

    // Função para adicionar um medicamento à lista
    function add_drug() {
        var dose_total_value = $("#dose_total").val();
        var selected_drug_value = $("#drug_select :selected").val();

        // Validação da quantidade
        if (dose_total_value <= 0 || isNaN(dose_total_value)) {
            $("#message1").fadeIn();
            return;
        }

        // Verifica se o medicamento já está na lista
        var existing_drug = drug_list.find(function(drug) {
            return drug.id === selected_drug_value;
        });

        if (existing_drug) {
            $("#message2").fadeIn();
            return;
        }

        // Requisição AJAX para obter os detalhes do medicamento
        $.ajax({
            url: "<?php echo base_url() ?>index.php/drug_stock/request_drug/" + selected_drug_value,
            type: 'GET',
            success: function(response) {
                // Parse do JSON de resposta
                var drugDetails = JSON.parse(response);

                // Dados do medicamento
                var drug_data = {
                    national_form_code: drugDetails.fnm,
                    id: selected_drug_value,
                    name: drugDetails.name,
                    dosage: drugDetails.dosage,
                    pharmaceutical_form: drugDetails.pharmaceutical_form,
                    existing_stock: drugDetails.stock,
                    requested_quantity: dose_total_value
                };

                drug_list.push(drug_data); // Adiciona o medicamento à lista
                update_table(); // Atualiza a tabela após adicionar

                $("#requisition_panel").fadeIn(); // Mostra o painel de requisição

                $("#dose_total").val(''); // Limpa o campo de quantidade
            },
            error: function(xhr, status, error) {
                $("#message4").fadeIn();
            }
        });
    }

    // Função para atualizar a tabela de medicamentos
    function update_table() {
        var tbody = $("#tbody_drug");
        tbody.empty(); // Limpa o corpo da tabela

        // Ordena os medicamentos por FNM (número nacional)
        drug_list.sort(function(a, b) {
            return a.national_form_code.localeCompare(b.national_form_code);
        });

        // Adiciona os medicamentos à tabela
        for (var i = 0; i < drug_list.length; i++) {
            var drug = drug_list[i];
            var html = '<tr>';
            html += '<td>' + (i + 1) + '</td>'; // Número sequencial
            html += '<td><input type="hidden" name="national_form_code[' + i + ']" value="' + drug.national_form_code + '">' + drug.national_form_code + '</td>'; // Código nacional
            html += '<td><input type="hidden" name="name[' + i + ']" value="' + drug.id + '">' + drug.name + '</td>'; // Nome do medicamento
            html += '<td><input type="hidden" name="dosage[' + i + ']" value="' + drug.dosage + '">' + drug.dosage + '</td>'; // Dosagem do medicamento
            html += '<td><input type="hidden" name="pharmaceutical_form[' + i + ']" value="' + drug.pharmaceutical_form + '">' + drug.pharmaceutical_form + '</td>'; // Forma farmacêutica
            html += '<td><input type="hidden" name="existing_stock[' + i + ']" value="' + drug.existing_stock + '">' + drug.existing_stock + '</td>'; // Estoque existente
            html += '<td><input type="hidden" name="requested_quantity[' + i + ']" value="' + drug.requested_quantity + '">' + drug.requested_quantity + '</td>'; // Quantidade requisitada
            html += '<td align="center"><button class="btn btn-danger btn_delete_drug" data-index="' + i + '" type="button"><?php echo lang('Delete'); ?></button></td>'; // Botão de exclusão
            html += '</tr>';
            tbody.append(html); // Adiciona a linha à tabela
        }

        // Evento ao clicar no botão "Delete"
        $('.btn_delete_drug').click(function() {
            var index = $(this).data('index');
            drug_list.splice(index, 1); // Remove o medicamento da lista
            update_table(); // Atualiza a tabela após remoção

            // Esconde o painel se não houver medicamentos na lista
            if (drug_list.length === 0) {
                $("#requisition_panel").fadeOut();
            }
        });
    }

    // Função para validar e enviar o formulário
    function validate_and_submit() {
        let requestCode = $("#request_code").val().trim();
        let requestType = $("#request_type").val();

        // Validação do código de requisição
        if (requestCode === '') {
            $("#message3").fadeIn();
            return false;
        }

        // Validação se há pelo menos um medicamento adicionado
        if (drug_list.length === 0) {
            alert("<?php echo lang('Please add at least one drug'); ?>");
            return false;
        }

        // Adiciona o valor do campo request_type ao formulário
        $("<input />").attr("type", "hidden")
            .attr("name", "request_type")
            .attr("value", requestType)
            .appendTo("#requisition_form");

        return true;
    }

    // Evento ao submeter o formulário
    $("#requisition_form").submit(function(event) {
        if (!validate_and_submit()) {
            event.preventDefault(); // Impede a submissão padrão do formulário se a validação falhar
        }
    });

});
</script>