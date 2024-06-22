<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    .chartWrapper {
        position: relative;
    }

    .chartWrapper>canvas {
        position: absolute;
        left: 0;
        top: 0;
        pointer-events: none;
    }

    .chartAreaWrapper {
        width: 100%;
        overflow-x: scroll;
    }
</style>

<body>
    <?php

    $konek = mysqli_connect("localhost", "root", "", "iot_smartkwh");

    $sql_ID = mysqli_query($konek, "SELECT MAX(ID) FROM grafik");

    $data_ID = mysqli_fetch_array($sql_ID);

    $ID_akhir = $data_ID['MAX(ID)'];
    $ID_awal =  $ID_akhir - 100;

    $tanggal_result = mysqli_query($konek, "SELECT DATE_FORMAT(tanggal, '%H:%i:%s') AS waktu from grafik WHERE ID>='$ID_awal' and ID<='$ID_akhir' ORDER BY ID ASC");
    $supply_result = mysqli_query($konek, "SELECT supply from grafik WHERE ID>='$ID_awal' and ID<='$ID_akhir' ORDER BY ID ASC");
    $demand_result = mysqli_query($konek, "SELECT demand from grafik WHERE ID>='$ID_awal' and ID<='$ID_akhir' ORDER BY ID ASC");

    $tanggal_data = [];
    $supply_data = [];
    $demand_data = [];

    while ($data_tanggal = mysqli_fetch_array($tanggal_result)) {
        $tanggal_data[] = $data_tanggal['waktu'];
    }

    while ($data_supply = mysqli_fetch_array($supply_result)) {
        $supply_data[] = $data_supply['supply'];
    }

    while ($data_demand = mysqli_fetch_array($demand_result)) {
        $demand_data[] = $data_demand['demand'];
    }

    ?>
    <div class="card-body">
        <div class="chartWrapper">
            <div class="chartAreaWrapper">
                <canvas id="myChart" height="500" width="1200"></canvas>
            </div>
            <canvas id="myChartAxis" height="500" width="0"></canvas>
        </div>
        <script type="text/javascript">
            var ctx = document.getElementById('myChart').getContext("2d");
            var data = {
                labels: <?php echo json_encode($tanggal_data); ?>,
                datasets: [{
                        label: "Supply (kWh)",
                        fill: true,
                        backgroundColor: "rgba(52, 231, 43, 0.2)",
                        borderColor: "rgba(52, 231, 43, 1)",
                        lineTension: 0.5,
                        pointRadius: 5,
                        data: <?php echo json_encode($supply_data); ?>
                    },
                    {
                        label: "Demand (kWh)",
                        fill: true,
                        backgroundColor: "rgba(239, 82, 93, 0.2)",
                        borderColor: "rgba(239, 82, 93, 1)",
                        lineTension: 0.5,
                        pointRadius: 5,
                        data: <?php echo json_encode($demand_data); ?>
                    }
                ]
            };

            var option = {
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Waktu (t)'
                        }
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Nilai (kWh)'
                        }
                    }]
                },
                showLines: true,
                animation: {
                    duration: 5
                }
            };


            // var myLineChart = Chart.Line(canvas, {
            //     data: data,
            //     options: option
            // });

            var myLineChart = new Chart(ctx, {
                type: 'line',
                data: data,
                options: option,
                onAnimationComplete: function() {
                    var sourceCanvas = this.chart.ctx.canvas;
                    var copyWidth = this.chart.scales['x-axis-0'].width - 10; // Asumsikan skala x adalah 'x-axis-0'
                    var copyHeight = this.chart.scales['y-axis-0'].height + this.chart.scales['y-axis-0'].top + 10; // Asumsikan skala y adalah 'y-axis-0'
                    var targetCtx = document.getElementById("myChartAxis").getContext("2d");
                    targetCtx.canvas.width = copyWidth;
                    targetCtx.canvas.height = copyHeight;
                    targetCtx.drawImage(sourceCanvas, 0, 0, copyWidth, copyHeight, 0, 0, copyWidth, copyHeight);
                }
            });
        </script>
    </div>
</body>

</html>