<div class="row">
    <div class="col-md-2">
        <?php echo Modules::run('leftmenu/report'); ?>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"><?php echo lang('Admission Report by Diagnoses, Gender, and Age Group') ?></h4>
                <hr>
                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px; display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <span id="message-text"><?php echo lang ('Missing dates or age range')?>.</span>
                </div>
                <form id="filterForm" class="form-inline" style="margin-top: 20px;">
                    <div class="form-group col-md-4">
                        <label for="start_date" class="control-label"><?php echo lang('Start Date') ?>:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="end_date" class="control-label"><?php echo lang('End Date') ?>:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <div class="form-group col-md-8" id="ageGroupContainer">
                        <label for="age_group" class="control-label"><?php echo lang ('Age Ranges')?>:</label>
                        <div class="input-group">
                            <input type="text" class="form-control age-group" id="age_group_input" name="age_groups[]" placeholder="Ex: 0-11 meses" >
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-success" id="addAgeGroup"><?php echo lang ('Add')?></button>
                            </span>
                        </div>
                        <div id="ageGroupsList" style="margin-top: 10px;">
                           
                        </div>
                    </div>

                    <div class="form-group col-md-12" style="margin-top: 10px;">
                        <button type="submit" class="btn btn-primary" id="btn-generate"><?php echo lang('Filter') ?></button>
                    </div>
                </form>

                <div id="loading" style="display: none;"><?php echo lang ('Loading')?>...</div>

                <div id="table-container" class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table table-responsive table-striped" id="reportTable" style="display: none;">
                            <thead>
                                <tr>
                                    <th><?php echo lang('Address_Street') ?></th>
                                    <th><?php echo lang('Diagnosis') ?></th>
                                  
                                    <th class="text-center">Total M</th>
                                    <th class="text-center">Total F</th>
                                    <th class="text-center">Total Geral</th>
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
    $(document).ready(function () {
        
        $('#addAgeGroup').click(function () {
            let ageGroup = $('#age_group_input').val().trim();

            if (ageGroup !== '') {
                let newAgeGroup = `
                    <div class="input-group" style="margin-top: 5px;">
                        <input type="text" class="form-control" value="${ageGroup}" disabled>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-danger removeAgeGroup">Remover</button>
                        </span>
                    </div>`;
                $('#ageGroupsList').append(newAgeGroup);
                $('#age_group_input').val('');
            }
        });

        
        $(document).on('click', '.removeAgeGroup', function () {
            $(this).closest('.input-group').remove();
        });

       
        $('#filterForm').on('submit', function (event) {
            event.preventDefault();

            let startDate = $('#start_date').val().trim();
            let endDate = $('#end_date').val().trim();
            let ageGroups = [];

           
            $('#ageGroupsList input').each(function () {
                ageGroups.push($(this).val());
            });

            
            $('#message').fadeOut();
            if (startDate === '' || endDate === '' || ageGroups.length === 0) {
                $('#message-text').text('Faltam datas ou faixa etária.');
                $('#message').fadeIn();
                return;
            }

            
            if (new Date(startDate) > new Date(endDate)) {
                $('#message-text').text('A data de início deve ser anterior à data de término.');
                $('#message').fadeIn();
                return;
            }

           
            $('#reportTable tbody').empty();
            $('#reportTable').hide(); 
            $('#loading').show(); /

            $.ajax({
                url: "<?php echo site_url('report/getDiagnosissurveillanceReportData'); ?>",
                method: "POST",
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    age_groups: ageGroups
                },
                dataType: "json",
                success: function (response) {
                    $('#loading').hide(); 
                    $('#reportTable').show(); 
                    $('#reportTable tbody').empty(); 

                    if (response.length > 0) {
                        console.log(response);
                        
                       
                        let headerRow = $('#reportTable thead tr');
                        ageGroups.forEach(function (ageGroup) {
                            
                            headerRow.find('th:nth-last-child(3)').before(`<th class="text-center">${ageGroup} (M)</th>`);
                            
                            headerRow.find('th:nth-last-child(3)').before(`<th class="text-center">${ageGroup} (F)</th>`);
                        });

                        $.each(response, function (index, row) {
                            let newRow = `
                                <tr>
                                    <td>${row.Address_Street}</td>
                                    <td>${row.Diagnostico}</td>`;
                            
                           
                            ageGroups.forEach(function (ageGroup) {
                                newRow += `<td class="text-center">${row[ageGroup + '_M'] || 0}</td>`; 
                                newRow += `<td class="text-center">${row[ageGroup + '_F'] || 0}</td>`; 
                            });

                            newRow += `
                                   
                                    <td class="text-center">${row.Total_M}</td>
                                    <td class="text-center">${row.Total_F}</td>
                                    <td class="text-center">${row.Total_Geral}</td>
                                </tr>`;
                            $('#reportTable tbody').append(newRow);
                        });
                    } else {
                        let noDataRow = `<tr><td colspan="5" class="text-center">Nenhum dado encontrado</td></tr>`;
                        $('#reportTable tbody').append(noDataRow);
                    }
                },
                error: function () {
                    $('#loading').hide(); 
                    $('#message-text').text('Ocorreu um erro ao buscar os dados. Tente novamente.');
                    $('#message').fadeIn();
                }
            });
        });
    });
</script>
