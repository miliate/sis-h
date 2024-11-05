<?php
function lineGraph($key, &$data, $title, $minX, $maxX, $intervalX, $minY, $maxY, $intervalY) {

    $chartId = $key . '_chart';
    $dataId = $key . '_data';

    $scripts = <<<HTML
    <script type="text/javascript">
        $(function () {
            var chartype = {
                type: 'scatter',
                margin: [35, 25, 30, 80],
                events: {
                    click: function (e) {
                        // find the clicked values and the series
                        var x = Math.round(e.xAxis[0].value / $intervalX) * $intervalX,
                            y = Math.round(e.yAxis[0].value),
                            series = this.series[0];
                        series.addPoint([x, y]);
                        document.getElementById("$dataId").value = series.data.map(function (p) {
                            return p.x + ',' + p.y;
                        }).join(';');
                    }
                }
            }
            var chartitle = {
                text: '$title',
                style: {
                    fontSize: '24px'
                }
            }
            var chartxaxis = {
                gridLineWidth: 1,
                min: $minX,
                max: $maxX,
                zoomEnabled: false,
                startOnTick: false,
                tickInterval: $intervalX,
                tickWidth: 0,
                labels: {
                    enabled:false,
                },
            }
            var chartyaxis = {
                title: {
                    text: ''
                },
                min: $minY,
                max: $maxY,
                zoomEnabled: false,
                startOnTick: false,
                endOnTick: false,
                tickInterval: $intervalY,
            }
            var chartplotoptions = {
                series: {
                    name: "$title",
                    lineWidth: 1,
                    point: {
                        events: {
                            'click': function () {
                                if (this.series.data.length > 0) {
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
            }
            var chartlegend = {
                enabled: false
            }
            var chartexporting = {
                enabled: false
            }
            var chartseries = [{
                data: [
                ]
            }]
            Highcharts.chart('$chartId', {
                chart: chartype,
                title: chartitle,
                xAxis: chartxaxis,
                yAxis: chartyaxis,
                plotOptions: chartplotoptions,
                legend: chartlegend,
                exporting: chartexporting,
                series: chartseries
            });
        });
    </script>
    <input type="hidden" id="$dataId" name="$key"/>
HTML;

    $chart = <<<HTML
    <div id="$chartId" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
HTML;

    echo $chart;
    echo $scripts;
    $data = "<script>document.write(($dataId).join(';'))</script>";

};
