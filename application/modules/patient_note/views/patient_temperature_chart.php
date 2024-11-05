<html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

<div class="col-md-6">

    <canvas id="myChart" class="canva"></canvas>
</div>
<?php $formattedDates = array_map(function ($date) {
    return date('d/m H:i', strtotime($date));
}, array_column($patient_exams, 'CreateDate'));

?>

<script>
    const xValues = <?php echo json_encode($formattedDates); ?>;
    const yValues = <?php echo json_encode(array_column($patient_exams, 'Temperature')); ?>;


    new Chart("myChart", {
        type: "line",
        data: {
            labels: xValues,
            datasets: [{
                label: 'Temperatura °C',
                fill: false,
                lineTension: 0,
                backgroundColor: 'rgba(75, 192, 192, 1)',
                borderColor: "rgba(255, 99, 71, 1)",
                data: yValues,

            }]
        },
        options: {
            legend: {
                display: true
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 30,
                        max: 40
                    }
                }],
            }
        }
    });
</script>

<style>
    canvas {
        width: 100% !important;
        height: auto !important;

    }
</style>