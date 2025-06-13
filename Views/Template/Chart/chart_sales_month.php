<?php 
    $ingresos = $data['dataingresos'];
    $costos = $data['datacostos'];
    $gastos = $data['datagastos'];
    $resultadoMensual = $ingresos['total'] -($costos['total']+$gastos['total']);
?>
<script>
    Highcharts.chart('monthChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Gráfico de <?=$ingresos['month']." ".$ingresos['year']?>'
        },
        subtitle: {
            text: `Ingresos: <?=formatNum($ingresos['total'])?> - Costos: <?=formatNum($costos['total'])?> - Gastos: <?=formatNum($gastos['total'])?><br>
                   Neto: <?=formatNum($resultadoMensual)?>`
        },
        xAxis: {
            categories: [
                <?php
                    
                    for ($i=0; $i < count($ingresos['sales']) ; $i++) { 
                        echo $ingresos['sales'][$i]['day'].",";
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
            name: 'Ingresos',
            data: [
                <?php
                    
                    for ($i=0; $i < count($ingresos['sales']) ; $i++) { 
                        echo $ingresos['sales'][$i]['total'].",";
                    }
                ?>
            ]
        },
        {
            name: 'Costos',
            data: [
                <?php
                    
                    for ($i=0; $i < count($costos['costos']) ; $i++) { 
                        echo $costos['costos'][$i]['total'].",";
                    }
                ?>
            ]
        },
        {
            name: 'Gastos',
            data: [
                <?php
                    
                    for ($i=0; $i < count($gastos['gastos']) ; $i++) { 
                        echo $gastos['gastos'][$i]['total'].",";
                    }
                ?>
            ]
        }]
    });
</script>