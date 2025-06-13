

<?php 
    $arrData = $data['data'];
?>
<script>
    Highcharts.chart('yearChartViews', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Visitas del año <?=$arrData[0]['year']?>'
        },
        subtitle: {
            text: `Visitas: <?=$data['total']?>`
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
            text: 'Visitas',
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
            name: 'Visitas',
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