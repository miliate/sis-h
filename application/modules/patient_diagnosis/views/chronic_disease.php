<?php

function get_diagnosis_search_component($selectId, $default_diagnosis = '')
{
    echo
        '<div id="error_message_' . $selectId . '" style="display:none" class="alert alert-danger"> ' . lang('search_minlength_message') . '</div>
        <div id="result_message_' . $selectId . '" style="display:none" class="alert alert-warning"> ' . lang('no_result_message') . '</div>
        <div id="diagnosis_section_' . $selectId . '" class="form-group">
            <label for="diagnosis" class="col-sm-2 control-label">' . lang('Direct Diagnosis') . '</label>
            <div class="col-sm-8">
                <input id="text_search_' . $selectId . '" minlength="3" class="form-control" />
            </div>
            <div class="col-sm-2">
                <button id="btn_search_' . $selectId . '" class="btn btn-primary btn-sm"> ' . lang('Search') . ' </button>
            </div>
        </div>';

    // Inicialmente o dropdown estará oculto
    echo 
    '<div id="div_select_' . $selectId . '" class="form-group" style="display:none">
        <label for="diagnosis" class="col-sm-2 control-label">Selecione o Diagnóstico</label>
        <div class="col-sm-8">
            <select id="' . $selectId . '" name="' . $selectId . '[]" multiple="multiple" class="form-control input-sm">';

    // Se houver um diagnóstico padrão, preencher o dropdown
    if (!empty($default_diagnosis)) {
        echo '<option value="' . $default_diagnosis . '" selected>' . $default_diagnosis . '</option>';
    }

    echo '</select>
        </div>
    </div>';

    // A lista de diagnósticos selecionados também estará oculta inicialmente
    echo '<div id="selected_diagnoses_' . $selectId . '" class="form-group" style="display:none">
        <label for="selected_diagnosis_list" class="col-sm-2 control-label">Diagnósticos Selecionados</label>
        <div class="col-sm-8">
            <ul id="selected_diagnosis_list_' . $selectId . '" class="list-group"></ul>
        </div>
    </div>';

    // Campo oculto para armazenar os diagnósticos selecionados
    echo '<input type="hidden" name="selected_diagnoses" id="selected_diagnoses_input_' . $selectId . '" />';

    includeJavaScript($selectId);
}

function includeJavaScript($selectId)
{
    echo '<script>
    $("#btn_search_' . $selectId . '").click(function(e) {
        e.preventDefault();
        let url = "' . site_url('patient_diagnosis/get_diagnosis') . '";
        let searchParam = $("#text_search_' . $selectId . '").val();
        
        // Validar se o termo de busca tem pelo menos 3 caracteres
        if (searchParam.length < 3) {
            $("#error_message_' . $selectId . '").fadeIn();
            return;
        }
        
        // Ocultar o dropdown e limpar mensagens de erro
        $("#div_select_' . $selectId . '").fadeOut();
        $("#result_message_' . $selectId . '").fadeOut();
        $("#error_message_' . $selectId . '").fadeOut();
        
        $.getJSON(`${url}/${searchParam}`, function(data) {
            
            // Verificar se não há resultados
            if (data.length == 0) {
                $("#result_message_' . $selectId . '").fadeIn();
                return;
            }

            // Exibir o dropdown e a lista de diagnósticos selecionados
            $("#div_select_' . $selectId . '").fadeIn();
            $("#selected_diagnoses_' . $selectId . '").fadeIn();

            let dropdown = $("#' . $selectId . '");
            dropdown.empty(); // Limpar as opções anteriores

            // Adicionar novas opções ao dropdown
            $.each(data, function(index, value) {
                if (dropdown.find("option[value=\'" + value.ICDID + "\']").length === 0) {
                    dropdown.append($("<option>").val(value.ICDID).text(value.Code + " - " + value.Name));
                }
            });

            $("#result_message_' . $selectId . '").fadeOut();
        });
    });

    // Quando um diagnóstico é selecionado
    $("#' . $selectId . '").change(function() {
        let selectedOption = $("#' . $selectId . ' option:selected");
        let diagnosisText = selectedOption.text();
        let diagnosisValue = selectedOption.val();

        // Adicionar à lista de diagnósticos selecionados
        if (diagnosisValue) {
            $("#selected_diagnosis_list_' . $selectId . '").append(
                \'<li class="list-group-item" data-value="\' + diagnosisValue + \'" style="height: 20px; padding: 0; display: flex; align-items: center; justify-content: space-between; font-size: 12px; overflow: hidden; line-height: 20px; border-bottom: 2px solid #ccc;">\' +
                \'<span style="padding-left: 12px;">\' + diagnosisText + \'</span> <button class="btn btn-danger btn-sm remove-diagnosis" style="height: 20px; padding: 0 5px; font-size: 10px;">X</button></li>\'            
            );
            // Limpar a pesquisa
            $("#text_search_' . $selectId . '").val("");
            $("#' . $selectId . '").val("").change(); // Reset the select dropdown
        }
    });

    // Remover um diagnóstico selecionado
    $("#selected_diagnosis_list_' . $selectId . '").on("click", ".remove-diagnosis", function() {
        $(this).parent().remove();
    });

    // Atualizar o campo oculto ao submeter o formulário
    $("form").submit(function() {
        let selectedDiagnoses = [];

        $("#selected_diagnosis_list_' . $selectId . ' li").each(function() {
            selectedDiagnoses.push($(this).data("value"));
        });

        $("#selected_diagnoses_input_' . $selectId . '").val(selectedDiagnoses.join(","));
    });
    </script>';
}
?>
