<?php 
headerAdmin($data);
$costos=$data['resumenMensual']['costos']['total'];
$gastos=$data['resumenMensual']['gastos']['total'];
$ingresos = $data['resumenMensual']['ingresos']['total'];

$ingresosAnual = $data['resumenAnual']['total'];
$costosAnual = $data['resumenAnual']['costos'];
$gastosAnual = $data['resumenAnual']['gastos'];
$resultadoAnual = $ingresosAnual-($costosAnual+$gastosAnual);
$resultadoMensual = $ingresos -($costos+$gastos);

$dataAnual = $data['resumenAnual']['data'];


?>

<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active" id="navResumen-tab" data-bs-toggle="tab" data-bs-target="#navResumen" type="button" role="tab" aria-controls="navResumen" aria-selected="true">Resumen</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="navCostos-tab" data-bs-toggle="tab" data-bs-target="#navCostos" type="button" role="tab" aria-controls="navCostos" aria-selected="true">Costos y gastos</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="navOrders-tab" data-bs-toggle="tab" data-bs-target="#navOrders" type="button" role="tab" aria-controls="navOrders" aria-selected="true">Ingresos</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navResumen">
                        <h2 class="text-center mb-3">Resumen</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items mb-3">
                                    <div class="fw-bold" id="txtMensual">Mes de <?=$data['resumenMensual']['ingresos']['month']." ".$data['resumenMensual']['ingresos']['year']?></div>
                                    <div class="d-flex align-items-center">
                                        <input  class="date-picker contabilidadMes" name="contabilidadMes" placeholder="Mes y año" required>
                                        <button class="btn btn-sm btn-primary" id="btnContabilidadMes"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Costos</td>
                                            <td id="costosMes"><?=formatNum($costos)?></td>
                                        </tr>
                                        <tr>
                                            <td>Gastos</td>
                                            <td id="gastosMes"><?=formatNum($gastos)?></td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad bruta</td>
                                            <td id="utilidadBruta"><?=formatNum($ingresos)?></td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad neta</td>
                                            <?php
                                                if($resultadoMensual < 0){
                                            ?>
                                            <td id="utilidadNeta"><span class="text-danger"><?=formatNum($resultadoMensual)?></span></td>
                                            <?php }else{?>
                                            <td id="utilidadNeta"><span class="text-success"><?=formatNum($resultadoMensual)?></span></td>
                                            <?php }?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between align-items mb-3">
                                    <div class="fw-bold" id="txtAnual">Año <?=$data['resumenAnual']['data'][0]['year']?> </div>
                                    <div class="d-flex align-items-center">
                                        <input type="number" name="contabilidadAnio" id="sYear" placeholder="Año" min="2000" max="9999">
                                        <button class="btn btn-sm btn-primary" id="btnContabilidadAnio"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>Costos</td>
                                            <td id="costosAnual"><?=formatNum($costosAnual)?></td>
                                        </tr>
                                        <tr>
                                            <td>Gastos</td>
                                            <td id="gastosAnual"><?=formatNum($gastosAnual)?></td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad bruta</td>
                                            <td id="utilidadBrutaAnual"><?=formatNum($ingresosAnual)?></td>
                                        </tr>
                                        <tr>
                                            <td>Utilidad neta</td>
                                            <?php
                                                if($resultadoAnual < 0){
                                            ?>
                                            <td class="text-danger"><span id="utilidadNetaAnual"><?=formatNum($resultadoAnual)?></span></td>
                                            <?php }else{?>
                                            <td class="text-success"><span id="utilidadNetaAnual"><?=formatNum($resultadoAnual)?></span></td>
                                            <?php }?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <figure class="highcharts-figure mb-3 mt-3"><div id="monthChart"></div></figure>
                        <figure class="highcharts-figure"><div id="yearChart"></div></figure>
                    </div>
                    <div class="tab-pane fade" id="navCostos">
                        <h2 class="text-center">Costos y gastos</h2>
                        <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="tableCostos" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                        <div class="row mb-3">
                            <div class="col-md-6 mt-3">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search" name="search">
                            </div>
                            <div class="col-md-6 mt-3">
                                <div class="row">
                                    <div class="col-md-3 d-flex align-items-center text-end">
                                        <span>Ordenar por: </span>
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control" aria-label="Default select example" id="sortBy" name="sortBy" required>
                                            <option value="1">Más reciente</option>
                                            <option value="2">Más antiguo</option>
                                            <option value="3">Por tipo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="scroll-y">
                            <table class="table text-center items align-middle" id="tableCostos">
                                <thead>
                                    <tr>
                                        <th>NIT</th>
                                        <th>Nombre de empresa</th>
                                        <th>Tipo</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="listItem">
                                    <?=$data['contabilidad']['data']?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navOrders">
                        <h2 class="text-center">Ingresos</h2>
                        <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="tablePedidos" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                        <div class="scroll-y">
                            <table class="table text-center items align-middle" id="tablePedidos">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody id="listItem">
                                    <?=$data['orders']['data']?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
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
            text: 'Ingresos netos: <?=formatNum($resultadoMensual)?>'
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
        },
        {
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

     