

<?php 
    $arrData = $data['data'];
?>
<script>
    Highcharts.chart('yearChartCustomers', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Clientes del año <?=$arrData[0]['year']?>'
        },
        subtitle: {
            text: `clientes: <?=$data['total']?>`
        },
        xAxis: {
            categories: [
                <?php
                        for ($i=0; $i < count($arrData) ; $i++) { 
                            echo '"'.$arrData[$i]['month'].'",';
                        }    
                ?>
            ],
            title: {
            text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
            text: 'Clientes',
            align: 'high'
            },
            labels: {
            overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ``
        },
        plotOptions: {
            bar: {
            dataLabels: {
                enabled: true
            }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
            shadow: true
        },
        credits: {
            enabled: false
        },
        series: [{
            name: 'Clientes',
            data: [
                <?php
                    for ($i=0; $i < count($arrData) ; $i++) { 
                        echo '["'.$arrData[$i]['month'].'"'.",".''.$arrData[$i]['total'].'],';
                    }    
                ?>
            ],
        }]
    });
</script>