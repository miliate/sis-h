<div class="row">
    <div class="col-md-2">
    <?php echo Modules::run('leftmenu/report'); ?>
    </div>
    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="text-center"><?php echo lang('Medical Report by Gender and Age Group') ?></h4>
                <hr>
                <div id="message" class="alert alert-warning" style="margin-top: 20px; margin-bottom: 20px; display: none;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                </div>

                <form id="filterForm" class="form-inline" style="margin-top: 20px;">
                    <div class="form-group">
                        <label for="start_date">Data Inicial:</label>
                        <input type="date" id="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="end_date">Data Final:</label>
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

                <!-- Divs dos retângulos com os resumos -->
                <div class="row" style="margin-top: 20px;"> 
                    <div class="col-md-4">
                        <div class="alert alert-info text-center">
                            <strong>Total de Pacientes Diagnósticos:</strong>
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
                        <canvas id="genderAgeChart" width="400" height="200"></canvas>
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
    let genderAgeChart;

    function drawChart(labels, maleData, femaleData, chartType) {
        if (genderAgeChart) {
            genderAgeChart.destroy();
        }

        // Prepare data by setting values to null if they are 0
        const filteredMaleData = maleData.map(value => value === 0 ? null : value);
        const filteredFemaleData = femaleData.map(value => value === 0 ? null : value);

        const ctx = document.getElementById('genderAgeChart').getContext('2d');
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
                                return value > 0 ? value : ''; // Remove os zeros do eixo Y
                            }
                        }
                    },
                    x: {
                        display: true // Show x-axis if needed
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
                            return value > 0 ? value : ''; //caso contrario mostre valor
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        };

        genderAgeChart = new Chart(ctx, chartOptions);
    }

    $('#filterForm').on('submit', function(event) {
        event.preventDefault();

        let startDate = $('#start_date').val().trim();
        let endDate = $('#end_date').val().trim();
        let chartType = $('#chartType').val();

        $('#message').fadeOut();

        // Validação de datas
        if (startDate === '' || endDate === '') {
            $('#message').fadeIn().text('Por favor, insira ambas as datas.');
            return;
        }
        
        // Verificação de intervalo de datas
        if (new Date(startDate) > new Date(endDate)) {
            $('#message').fadeIn().text('A data inicial não pode ser maior que a data final.');
            return;
        }

        $('#message').fadeIn().text('Carregando dados...');

        $.ajax({
            url: `<?php echo site_url('report/getObservationgraphycReportData'); ?>/${startDate}/${endDate}`,
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
                            labels.push(item.Diagnostico);
                            maleData.push(item.Total_M);
                            femaleData.push(item.Total_F);

                            // Convertendo para número antes de somar
                            totalMasculino += Number(item.Total_M);
                            totalFeminino += Number(item.Total_F);
                        }
                    });
                    totalPacientes = totalMasculino + totalFeminino;

                    // Atualizar os retângulos com os dados cumulativos
                    $('#totalPacientes').text(totalPacientes);
                    $('#totalMasculino').text(totalMasculino);
                    $('#totalFeminino').text(totalFeminino);

                    drawChart(labels, maleData, femaleData, chartType);
                    $('#message').fadeOut(); // Esconde a mensagem de carregando
                } else {
                    $('#message').fadeIn().text('Nenhum dado encontrado para este período.');
                }
            },
            error: function() {
                $('#message').fadeIn().text('Erro ao carregar os dados. Por favor, tente novamente.');
            }
        });
    });
});
</script>
