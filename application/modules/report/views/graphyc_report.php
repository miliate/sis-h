<div class="row">
    <div class="col-md-2">
    <?php echo Modules::run('leftmenu/report'); ?>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"><?php echo lang('Selecione o Relatório') ?></h4>
                <hr>
                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px; display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <form id="filterForm" class="form-inline" style="margin-top: 20px;">
                    <div class="form-group">
                        <label for="reportType"><?php echo lang ('Type of Report')?>:</label>
                        <select id="reportType" class="form-control">
                            <option value="admission"><?php echo lang ('Admission')?></option>
                            <option value="observation"><?php echo lang ('Medical Observation')?></option>
                            <option value="discharge"><?php echo lang ('Medical Discharge')?></option>
                            <option value="diagnosis"><?php echo lang ('Diagnosis')?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="start_date"><?php echo lang ('Start Date')?>:</label>
                        <input type="date" id="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date"><?php echo lang ('End Date')?>:</label>
                        <input type="date" id="end_date" class="form-control" required>
                        </div>
                    <div class="form-group">
                        <label for="chartType">Tipo de Gráfico:</label>
                        <select id="chartType" class="form-control">
                            <option value="bar">Gráfico de Barras</option>
                            <option value="pie">Gráfico de Pizza</option>
                            <option value="line">Gráfico de Linhas</option>
                            <option value="radar">Gráfico de Radar</option>
                            <option value="histogram">Histograma</option>
                            <option value="column">Gráfico de Colunas</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </form>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-4">
                        <div class="alert alert-info text-center">
                            <strong>Total de Pacientes:</strong>
                            <span id="totalPacientes">0</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert" style="background-color: #3c763d; color: white; text-align: center;">
                            <strong>Total Masculino:</strong>
                            <span id="totalMasculino">0</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="alert alert-danger text-center">
                            <strong>Total Feminino:</strong>
                            <span id="totalFeminino">0</span>
                        </div>
                    </div>
                </div>

                <div id="chart-container" class="row" style="margin-top: 20px;">
                    <div class="col-md-12">
                        <canvas id="reportChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
$(document).ready(function() {
    let reportChart;

    function drawChart(labels, maleData, femaleData, chartType) {
        if (reportChart) {
            reportChart.destroy();
        }

        const filteredMaleData = maleData.map(value => value === 0 ? null : value);
        const filteredFemaleData = femaleData.map(value => value === 0 ? null : value);

        const ctx = document.getElementById('reportChart').getContext('2d');
        // reportChart = new Chart(ctx, {
        const chartOptions = { 
            type: (chartType === 'column') ? 'bar' : (chartType === 'histogram' ? 'bar' : chartType),
            data: {
                labels: labels,
                datasets: [{
                    label: 'Masculino',
                    data: filteredMaleData,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Feminino',
                    data: filteredFemaleData,
                    backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    },
                    x: {
                        display: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    datalabels: {
                        anchor: 'center',
                        align: 'center',
                        color: 'black',
                        formatter: function(value) {
                            return value > 0 ? value : '';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        };
        reportChart = new Chart(ctx, chartOptions);
    }

    $('#filterForm').on('submit', function(event) {
        event.preventDefault();

        let reportType = $('#reportType').val();
        console.log(reportType);
        let startDate = $('#start_date').val().trim();
        let endDate = $('#end_date').val().trim();
        let chartType = $('#chartType').val();

        $('#message').fadeOut();

        if (startDate === '' || endDate === '') {
            $('#message').fadeIn().text('Por favor, insira ambas as datas.');
            return;
        }

        if (new Date(startDate) > new Date(endDate)) {
            $('#message').fadeIn().text('A data inicial não pode ser maior que a data final.');
            return;
        }

        $('#message').fadeIn().text('Carregando dados...');

        $.ajax({
            url: `<?php echo site_url('report/getReportgraphycreportData'); ?>/${reportType}/${startDate}/${endDate}`,
            method: "GET",
            dataType: "json",
            success: function(response) {
                let totalPacientes = 0;
                let totalMasculino = 0;
                let totalFeminino = 0;

                let labels = [];
                let maleData = [];
                let femaleData = [];

                if (response.length > 0) {
                    $.each(response, function(index, item) {
                        if (item.Total_M > 0 || item.Total_F > 0) {
                            labels.push(item.Motivo);
                            maleData.push(item.Total_M);
                            femaleData.push(item.Total_F);
                        }
                        totalPacientes += Number(item.Total_M) + Number(item.Total_F);
                        totalMasculino += Number(item.Total_M);
                        totalFeminino += Number(item.Total_F);
                    });
                }

                $('#totalPacientes').text(totalPacientes);
                $('#totalMasculino').text(totalMasculino);
                $('#totalFeminino').text(totalFeminino);

                drawChart(labels, maleData, femaleData, chartType);
                $('#message').fadeOut();
            }
        });
    });
});
</script>
