<?php 
    //dep( $data['infoAnual']);
    headerAdmin($data);
    $costos=$data['resumenMensual']['costos']['total'];
    $gastos = $data['resumenMensual']['gastos']['total'];
    $ingresos = $data['resumenMensual']['ingresos']['total'];
    $info = $data['info'];
    $infoAnual = $data['infoAnual'];
    $ingresosAnual = $data['resumenAnual']['total'];
    $costosAnual = $data['resumenAnual']['costos'];
    $gastosAnual = $data['resumenAnual']['gastos'];

    $resultadoAnual = $ingresosAnual-($costosAnual+$gastosAnual);
    $resultadoMensual = $ingresos -($costos+$gastos);

    $dataAnual = $data['resumenAnual']['data'];
    $tmonth='<tr><td>Descripción</td><td>Enero</td><td>Febrero</td><td>Marzo</td><td>Abril</td><td>Mayo</td>
    <td>Junio</td><td>Julio</td><td>Agosto</td><td>Septiembre</td><td>Octubre</td>
    <td>Noviembre</td><td>Diciembre</td><td>Monto</td></tr>';
    //month
    $htmlG = '<tr class="bg-color-3"><td  colspan="2" class="text-center fw-bold">Gastos</td></tr>';
    $htmlC = '<tr class="bg-color-3"><td  colspan="2" class="text-center fw-bold">Costos</td></tr>';
    $htmlI = '<tr class="bg-color-3"><td  colspan="2" class="text-center fw-bold">Ingresos</td></tr>';
    //anual
    $htmlGA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Gastos</td></tr>';
    $htmlCA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Costos</td></tr>';
    $htmlIA = '<tr class="bg-color-3"><td colspan="100" class="text-center fw-bold">Ingresos</td></tr>';
    $htmlGA.=$tmonth;
    $htmlCA.=$tmonth;
    $htmlIA.=$tmonth;
    for ($i=0; $i < count($info); $i++) { 
        $month=$infoAnual[$i]['month'];
        $td="";
        $total=0; 
        if($info[$i]['type'] == 1){
            $htmlG.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
            for ($j=0; $j <count($month) ; $j++) { 
                $total +=$month[$j+1];
                $td.= '<td>'.formatNum($month[$j+1]).'</td>';
            }
            $htmlGA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
        }else if($info[$i]['type'] == 2){
            $htmlC.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
            for ($j=0; $j <count($month) ; $j++) { 
                $total +=$month[$j+1];
                $td.= '<td>'.formatNum($month[$j+1]).'</td>';
            }
            $htmlCA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
        }else if($info[$i]['type'] == 3){
            $htmlI.= '<tr><td>'.$info[$i]['name'].'</td><td>'.formatNum($info[$i]['total']).'</td></tr>';
            for ($j=0; $j <count($month) ; $j++) { 
                $total +=$month[$j+1];
                $td.= '<td>'.formatNum($month[$j+1]).'</td>';
            }
            $htmlIA.= '<tr><td>'.$info[$i]['name'].'</td>'.$td.'</td><td>'.formatNum($total).'</td></tr>';
        }
    }
    //month
    $htmlG.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($gastos).'</td></tr>';
    $htmlC.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($costos).'</td></tr>';
    $htmlI.= '<tr><td class="text-end fw-bold">Total</td><td>'.formatNum($ingresos).'</td></tr>';

    //anual
    $htmlGA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($gastosAnual).'</td></tr>';
    $htmlCA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($costosAnual).'</td></tr>';
    $htmlIA.= '<tr><td class="text-end fw-bold" colspan="13">Total</td><td>'.formatNum($ingresosAnual).'</td></tr>';

    $tBody = $htmlC.$htmlG.$htmlI.'<tr class="bg-color-2"><td class="text-end fw-bold">Ingresos - (costos+gastos)</td><td>'.formatNum($resultadoMensual).'</td></tr>';
    $tBodyA = $htmlCA.$htmlGA.$htmlIA.'<tr class="bg-color-2"><td class="text-end fw-bold" colspan="13">Ingresos - (costos+gastos)</td><td>'.formatNum($resultadoAnual).'</td></tr>';
    
?>
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <div class="d-flex align-items-center">
                <input  class="date-picker contabilidadMes" name="contabilidadMes" placeholder="Mes y año" required>
                <button class="btn btn-sm btn-primary" id="btnContabilidadMes"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <figure class="highcharts-figure mb-3 mt-3"><div id="monthChart"></div></figure>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-4 fs-4 text-center" id="titleDetail">Detalle mensual <?=$data['resumenMensual']['ingresos']['month']." ".$data['resumenMensual']['ingresos']['year']?></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Descripción</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody id="tableDetail">
                    <?=$tBody?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-3">
            <div class="d-flex align-items-center">
                <input type="number" name="contabilidadAnio" id="sYear" placeholder="Año" min="2000" max="9999">
                <button class="btn btn-sm btn-primary" id="btnContabilidadAnio"><i class="fas fa-search"></i></button>
            </div>
        </div>
        <figure class="highcharts-figure"><div id="yearChart"></div></figure>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <h4 class="mb-4 fs-4 text-center" id="titleDetailAnual">Detalle anual <?=$dataAnual[0]['year']?></h4>
        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody id="tableDetailAnual">
                    <?=$tBodyA?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>     
<script>
    Highcharts.chart('monthChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Gráfico de <?=$data['resumenMensual']['ingresos']['month']." ".$data['resumenMensual']['ingresos']['year']?>'
        },
        subtitle: {
            text: `Ingresos: <?=formatNum($ingresos)?> - Costos: <?=formatNum($costos)?> - Gastos: <?=formatNum($gastos)?><br>
                   Neto: <?=formatNum($resultadoMensual)?>`
        },
        xAxis: {
            categories: [
                <?php
                    
                    for ($i=0; $i < count($data['resumenMensual']['ingresos']['sales']) ; $i++) { 
                        echo $data['resumenMensual']['ingresos']['sales'][$i]['day'].",";
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
                    
                    for ($i=0; $i < count($data['resumenMensual']['ingresos']['sales']) ; $i++) { 
                        echo $data['resumenMensual']['ingresos']['sales'][$i]['total'].",";
                    }
                ?>
            ]
        },{
            name: 'Costos',
            data: [
                <?php
                    
                    for ($i=0; $i < count($data['resumenMensual']['costos']['costos']) ; $i++) { 
                        echo $data['resumenMensual']['costos']['costos'][$i]['total'].",";
                    }
                ?>
            ]
        },{
            name: 'Gastos',
            data: [
                <?php
                    
                    for ($i=0; $i < count($data['resumenMensual']['gastos']['gastos']) ; $i++) { 
                        echo $data['resumenMensual']['gastos']['gastos'][$i]['total'].",";
                    }
                ?>
            ]
        }]
        
    });
    Highcharts.chart('yearChart', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Gráfico del año <?=$dataAnual[0]['year']?>'
        },
        subtitle: {
            text: `Ingresos: <?=formatNum($ingresosAnual)?> - Costos: <?=formatNum($costosAnual)?> - Gastos: <?=formatNum($gastosAnual)?><br>
                   Neto: <?=formatNum($resultadoAnual)?>`
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
        }, {
            name: 'Gastos',
            data: [
                <?php
                    for ($i=0; $i < count($dataAnual) ; $i++) { 
                        echo '["'.$dataAnual[$i]['month'].'"'.",".''.$dataAnual[$i]['gastos'].'],';
                    }    
                ?>
            ],
        }]
    });
</script> 
