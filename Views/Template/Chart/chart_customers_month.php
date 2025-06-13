<?php
    $arrData = $data['info'];
?>
<script>
    Highcharts.chart('monthChartCustomers', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Clientes del mes de <?=$arrData['month']." ".$arrData['year']?>'
        },
        subtitle: {
            text: `Clientes: <?=$arrData['total']?>`
        },
        xAxis: {
            categories: [
                <?php
                    
                    for ($i=0; $i < count($arrData['data']) ; $i++) { 
                        echo $arrData['data'][$i]['day'].",";
                    }
                ?>
            ]
        },
        yAxis: {
            title: {
                text: ''
            }
        },
        plotOptions: {
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: false
            }
        },
        series: [{
            name: 'Clientes',
            data: [
                <?php
                    
                    for ($i=0; $i < count($arrData['data']) ; $i++) { 
                        echo $arrData['data'][$i]['total'].",";
                    }
                ?>
            ]
        },]
    });
</script>
