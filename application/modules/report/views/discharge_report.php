<div class="row">
    <div class="col-md-2">
    <?php echo Modules::run('leftmenu/report'); ?>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"><?php echo lang ('Outcome Report by Gender and Age Group')?></h4>
                <hr>
                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px; display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               
                </div>
                <form id="filterForm" class="form-inline" style="margin-top: 20px;">
                    <div class="form-group col-md-4">
                        <label for="start_date" class="control-label"><?php echo lang ('Start Date')?>:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="end_date" class="control-label"><?php echo lang ('End Date')?>:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                    <div class="form-group col-md-3">
                        <button type="submit" class="btn btn-primary" id="btn-generate"><?php echo lang ('Filter')?></button>
                    </div>
                    <div class="form-group col-md-2" style="margin-top: 10px;">
                            <div class="dropdown" style="display: inline-block;">
                            <button class="btn btn-danger dropdown-toggle" type="button" id="downloadDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Download
                            </button>
                            <div class="dropdown-menu" aria-labelledby="downloadDropdown">
                                <button class="dropdown-item" id="downloadExcel">Download Excel</button>
                                <button class="dropdown-item" id="downloadCSV">Download CSV</button>
                                <button class="dropdown-item" id="downloadPDF">Download PDF</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div id="table-container" class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <table class="table table-responsive table-stripped" id="reportTable">
                            <thead>
                            <tr>
                                    <th rowspan="2"><?php echo lang ('Diagnoses')?></th>
                                    <th colspan="5" class="text-center">M</th>
                                    <th colspan="5" class="text-center">F</th>
                                    <th colspan="2" class="text-center">Total</th>
                                    <th class="text-center"></th>
                                </tr>
                                <tr>
                                    <th>0-14</th>
                                    <th>15-24</th>
                                    <th>25-49</th>
                                    <th>50-59</th>
                                    <th>60+</th>
                                    <th>0-14</th>
                                    <th>15-24</th>
                                    <th>25-49</th>
                                    <th>50-59</th>
                                    <th>60+</th>
                                    <th>M</th>
                                    <th>F</th>
                                    <th class="text-center"><?php echo lang('Grand Total')?></th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>

<script>
    $(document).ready(function() {
        $('#filterForm').on('submit', function(event) {
            event.preventDefault();

            let startDate = $('#start_date').val().trim();
            let endDate = $('#end_date').val().trim();
            let period = $('#period').val();

            $('#message').fadeOut();

            if (startDate === '' || endDate === '') {
                $('#message').fadeIn();
                return;
            }

            $.ajax({
                url: `<?php echo site_url('report/getDischargeReportData'); ?>/${startDate}/${endDate}/${period}`,
                method: "GET",
                dataType: "json",
                success: function(response) {
                    $('#reportTable tbody').empty();

                    if (response.length > 0) {
                        $.each(response, function(index, item) {
                            let row = `
                                <tr>
                                    <td>${item.Motivo}</td>
                                    <td>${item.M_0_14}</td>
                                    <td>${item.M_15_24}</td>
                                    <td>${item.M_25_49}</td>
                                    <td>${item.M_50_59}</td>
                                    <td>${item.M_60_plus}</td>
                                    <td>${item.F_0_14}</td>
                                    <td>${item.F_15_24}</td>
                                    <td>${item.F_25_49}</td>
                                    <td>${item.F_50_59}</td>
                                    <td>${item.F_60_plus}</td>
                                    <td>${item.Total_M}</td>
                                    <td>${item.Total_F}</td>
                                    <td style="text-align: center;">${item.Total_Geral}</td>
                                </tr>
                            `;
                            $('#reportTable tbody').append(row);
                        });
                    } else {
                        $('#reportTable tbody').append('<tr><td colspan="13" class="text-center">Nenhum dado encontrado</td></tr>');
                    }
                },
                error: function() {
                    alert(lang ('Error retrieving data. Please try again.'));
                }
            });
        });
        
        $('#downloadExcel').click(function() {
            let table = document.getElementById('reportTable');
            let wb = XLSX.utils.table_to_book(table, { sheet: "Relatorio" });
            XLSX.writeFile(wb, 'relatorio.xlsx');
        });

        $('#downloadCSV').click(function() {
            let table = document.getElementById('reportTable');
            let csv = [];
            let rows = table.querySelectorAll('tr');

            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll('td, th');

                
                for (let j = 0; j < cols.length; j++) {
                    
                    row.push('"' + cols[j].innerText + '"');
                }

                csv.push(row.join(','));
            }

            let csvContent = csv.join('\n');

            if (!csvContent) {
                alert('Erro ao gerar o CSV');
        return;
        }
            let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            let link = document.createElement('a');
            let url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', 'relatorio.csv');
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
});

       
        $('#downloadPDF').click(function() {
            let { jsPDF } = window.jspdf;
            let doc = new jsPDF();
            let elementHTML = $('#reportTable').html();
            let specialElementHandlers = {
                '#ignorePDF': function (element, renderer) {
                    return true;
                }
            };

            doc.fromHTML(elementHTML, 15, 15, {
                'width': 170,
                'elementHandlers': specialElementHandlers
            });

            doc.save('relatorio.pdf');
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