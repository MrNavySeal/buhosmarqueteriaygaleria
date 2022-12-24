<?php

    if($data['chart']=="month"){
    $ingresos = $data['dataingresos'];
    $costos = $data['datacostos'];

    $resultadoMensual = $ingresos['total'] -$costos['total'];
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
            text: 'Total: <?=formatNum($resultadoMensual)?>'
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
        }]
    });
</script>
<?php }else{
    $dataAnual = $data['data'];
    $ingresosAnual = $data['total'];
    $costosAnual = $data['costos'];
    $resultadoAnual = $ingresosAnual-$costosAnual;
?>
<script>
    Highcharts.chart('yearChart', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Gráfico del año <?=$dataAnual[0]['year']?>'
        },
        subtitle: {
            text: 'Ingresos netos: <?=formatNum($resultadoAnual)?>'
        },
        xAxis: {
            categories: [
                <?php
                        for ($i=0; $i < count($dataAnual) ; $i++) { 
                            echo '"'.$dataAnual[$i]['month'].'",';
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
            text: 'Ingresos y egresos',
            align: 'high'
            },
            labels: {
            overflow: 'justify'
            }
        },
        tooltip: {
            valueSuffix: ` ${MD}`
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
            name: 'Ingresos',
            data: [
                <?php
                    for ($i=0; $i < count($dataAnual) ; $i++) { 
                        echo '["'.$dataAnual[$i]['month'].'"'.",".''.$dataAnual[$i]['sale'].'],';
                    }    
                ?>
            ],
        }, {
            name: 'Costos',
            data: [
                <?php
                    for ($i=0; $i < count($dataAnual) ; $i++) { 
                        echo '["'.$dataAnual[$i]['month'].'"'.",".''.$dataAnual[$i]['costos'].'],';
                    }    
                ?>
            ],
        }]
    });
</script>
<?php }?>