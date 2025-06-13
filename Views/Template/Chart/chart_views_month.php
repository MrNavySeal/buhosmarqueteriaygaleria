<?php
    $arrVisitas = $data['info'];
?>
<script>
    Highcharts.chart('monthChartViews', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Visitas del mes de <?=$arrVisitas['month']." ".$arrVisitas['year']?>'
        },
        subtitle: {
            text: `Visitas: <?=$arrVisitas['total']?>`
        },
        xAxis: {
            categories: [
                <?php
                    
                    for ($i=0; $i < count($arrVisitas['data']) ; $i++) { 
                        echo $arrVisitas['data'][$i]['day'].",";
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
            name: 'Visitas',
            data: [
                <?php
                    
                    for ($i=0; $i < count($arrVisitas['data']) ; $i++) { 
                        echo $arrVisitas['data'][$i]['total'].",";
                    }
                ?>
            ]
        },]
    });
</script>
