<?php
function dilatacao_view($key, &$data) {

    $chartId = $key . '_dilatacao';
    $dataId = $key . '_dilatacao_data';

    $scripts = <<<HTML
    <script type="text/javascript">
        $(function () {
            Highcharts.chart('$chartId', {
                tooltip: {
                    formatter: function () {
                        return '<b>' + this.series.name + '</b><br/>' +
                            Highcharts.numberFormat(this.x, 0) + ' hour, ' +
                            Highcharts.numberFormat(this.y, 0);
                    }
                },
                chart: {
                    type: 'scatter',
                    margin: [35, 25, 30, 80],
                    events: {
                        click: function (e) {
                            // find the clicked values and the series
                            var x = Math.round(e.xAxis[0].value),
                                y = Math.round(e.yAxis[0].value),
                                series = this.series[0];
                            series.addPoint([x, y]);
                            document.getElementById("$dataId").value = series.data.map(function (p) {
                                return p.x + ',' + p.y;
                            }).join(';');
                        }
                    }
                },
                title: {
                    text: 'DILATACAO',
                    style: {
                        fontSize: '24px'
                    }
                },
                xAxis: {
                    gridLineWidth: 1,
                    min: 0,
                    max: 25,
                    zoomEnabled: false,
                    startOnTick: false,
                    tickInterval: 1,
                    tickWidth: 0,
                    labels: {
                        enabled:false,
                        format: "{value} min"
                    }
                },
                yAxis: {
                    title: {
                        style: {
                            display: 'none'
                        }
                    },
                    plotLines: [{
                        value: 0,
                        width: 1,
                        color: '#808080'
                    }],
                    min: 0,
                    max: 10,
                    zoomEnabled: false,
                    startOnTick: false,
                    endOnTick: false,
                    tickInterval: 1,
                },
                plotOptions: {
                    series: {
                        name: "DILATACAO",
                        lineWidth: 1,
                        point: {
                            events: {
                                'click': function () {
                                    if (this.series.data.length > 0 && this.series.name === "DILATACAO") {
                                        const x = this.options.x, y = this.options.y;
                                        this.remove();
                                        const origin = document.getElementById("$dataId").value.split(";");
                                        const filtered = origin.filter(function (p) {
                                            return p !== x + ',' + y;
                                        });
                                        document.getElementById("$dataId").value = filtered.join(';');
                                    }
                                }
                            }
                        },
                    }
                },
                legend: {
                    enabled: false
                },
                exporting: {
                    enabled: false
                },
                series: [{
                    data: []
                },{
                    name:"ALERTA",
                    data: [
                        [6, 4],
                        [12, 10],
                    ],
                    enableMouseTracking: false,
                },{
                    name:"ACCAO",
                    data: [
                        [10, 4],
                        [16, 10],
                    ],
                    enableMouseTracking: false,
                }],
            });
        });
    </script>
    <input type="hidden" id="$dataId" name="$key"/>
HTML;

    $time_name = $key . '_time';
    $chart = <<<HTML
    <div id="$chartId" class="chart_view"></div>
    <div class="start_hora">
        <p class="start_hora_title">Start Hora</p>
        <input name="$time_name" id="time-input" class="dd_input" type="time" value="00:00">
    </div>
HTML;

$style = <<<HTML
        <style>
        .dd_input {
            width: 100%;
            outline: none;
        }

        .start_hora_title {
            height: 30px;
            width: 80px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .chart_view {
            min-width: 310px;
            height: 400px;
            margin: 0 auto;
        }

        .start_hora {
            display: flex;
            background-color: #fff;
            padding: 10px 0 10px 0;
        }

        input[type="time"]::-webkit-calendar-picker-indicator {
            /* background: url('https://cdn3.iconfinder.com/data/icons/google-material-design-icons/48/ic_access_time_48px-512.png') no-repeat; */
            background-size: contain;
            width: 24px;
            font-size: 10px;
            height: 24px;
            opacity: 1;
            cursor: pointer;
        }
        </style>
HTML;


    echo $style;
    echo $chart;
    echo $scripts;
    $data = "<script>document.write(($dataId).join(';'))</script>";

};
