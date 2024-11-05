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

    // Display the dropdown by default, with or without a selected diagnosis
    echo 
    '<div id="div_select_' . $selectId . '" class="form-group">
        <label for="diagnosis" class="col-sm-2 control-label">Selecione o Diagnóstico</label>
        <div class="col-sm-8">
            <select id="' . $selectId . '" name="' . $selectId . '" class="form-control input-sm">';

    // Check if default_diagnosis is not null or empty and prefill the dropdown
    if (!empty($default_diagnosis)) {
        echo '<option value="' . $default_diagnosis . '" selected>' . $default_diagnosis . '</option>';
    }

    echo '</select>
        </div>
    </div>';

    includeJavaScript($selectId);
}

function includeJavaScript($selectId)
{
    echo '<script>
    $("#btn_search_' . $selectId . '").click(function(e) {
        e.preventDefault();
        let url = "' . site_url('patient_diagnosis/get_diagnosis') . '";
        let searchParam = $("#text_search_' . $selectId . '").val();
        
        // Validate if the search term is at least 3 characters long
        if (searchParam.length < 3) {
            $("#error_message_' . $selectId . '").fadeIn();
            return;
        }
        
        $("#div_select_' . $selectId . '").fadeOut();
        
        $.getJSON(`${url}/${searchParam}`, function(data) {
            $("#error_message_' . $selectId . '").fadeOut();
            
            // Check if no results were returned
            if (data.length == 0) {
                $("#result_message_' . $selectId . '").fadeIn();
                return;
            }
            
            // Populate the dropdown with the new results
            $("#' . $selectId . '").fadeIn().empty().focus();
            
            // Append each diagnosis to the dropdown, directly with the results
            $.each(data, function(index, value) {
                $("#' . $selectId . '").append($("<option>").val(value.ICDID).text(value.Code + " - " + value.Name));
            });
            
            $("#div_select_' . $selectId . '").fadeIn();
            $("#result_message_' . $selectId . '").fadeOut();
        });
    });
    </script>';
}