<?php 
//dep($data['salesMonth']);exit;
    headerAdmin($data);
    $orders = $data['orders'];
    $products = $data['products'];
    $costos=$data['resumenMensual']['costos']['total'];
    $ingresos = $data['resumenMensual']['ingresos']['total'];

    $ingresosAnual = $data['resumenAnual']['total'];
    $costosAnual = $data['resumenAnual']['costos'];
    $resultadoAnual = $ingresosAnual-$costosAnual;
    $resultadoMensual = $ingresos -$costos;

    $dataAnual = $data['resumenAnual']['data'];
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="row">
            <!--
            <div class="col-sm-6 col-lg-3">
                <div class="card mb-4 text-white bg-primary">
                    <div class="card-body pb-0 d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fs-4 fw-semibold">26K
                            <div>Users</div>
                        </div>
                    </div>
                </div>
            </div>-->
            <?php if($_SESSION['userData']['roleid'] != 2 && $_SESSION['permitsModule']['r']){?>
            <div class="col-md-3">
                <div class="card mb-4 position-relative" style="--cui-card-cap-bg: #321fdb">
                    <div class="card-header position-relative d-flex justify-content-center align-items-center">
                        <svg class="icon icon-3xl text-white my-4">
                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-user"></use>
                        </svg>
                        
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <div class="fs-5 fw-semibold"><?=$data['totalUsers']?></div>
                            <div class="text-uppercase text-medium-emphasis small">Usuarios</div>
                        </div>
                    </div>
                    <a href="<?=base_url();?>/usuarios/usuarios" class="position-absolute w-100 h-100"></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 position-relative" style="--cui-card-cap-bg: #3399ff">
                    <div class="card-header position-relative d-flex justify-content-center align-items-center">
                        <svg class="icon icon-3xl text-white my-4">
                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-user-follow"></use>
                        </svg>
                        
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <div class="fs-5 fw-semibold"><?=$data['totalCustomers']?></div>
                            <div class="text-uppercase text-medium-emphasis small">Clientes</div>
                        </div>
                    </div>
                    <a href="<?=base_url();?>/clientes" class="position-absolute w-100 h-100"></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4 position-relative" style="--cui-card-cap-bg: #f9b115">
                    <div class="card-header position-relative d-flex justify-content-center align-items-center">
                        <svg class="icon icon-3xl text-white my-4">
                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-dollar"></use>
                        </svg>
                        
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <div class="fs-5 fw-semibold"><?=$data['totalSales']?></div>
                            <div class="text-uppercase text-medium-emphasis small">Ventas brutas</div>
                        </div>
                    </div>
                    <a href="<?=base_url();?>/contabilidad" class="position-absolute w-100 h-100"></a>
                </div>
            </div>
            <?php }?>
            <div class="col-md-3">
                <div class="card mb-4 position-relative" style="--cui-card-cap-bg: #e55353">
                    <div class="card-header position-relative d-flex justify-content-center align-items-center">
                        <svg class="icon icon-3xl text-white my-4">
                            <use xlink:href="<?=media()?>/coreui/coreui-free-bootstrap-admin-template/dist/vendors/@coreui/icons/svg/free.svg#cil-money"></use>
                        </svg>
                        
                    </div>
                    <div class="card-body row text-center">
                        <div class="col">
                            <div class="fs-5 fw-semibold"><?=$data['totalOrders']?></div>
                            <div class="text-uppercase text-medium-emphasis small">Pedidos</div>
                        </div>
                    </div>
                    <a href="<?=base_url();?>/pedidos" class="position-absolute w-100 h-100"></a>
                </div>
            </div>
        </div>
        <?php if($_SESSION['userData']['roleid'] != 2 && $_SESSION['permitsModule']['r']){?>
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
                <div class="d-flex justify-content-end mb-3">
                    <div class="d-flex align-items-center">
                        <input type="number" name="contabilidadAnio" id="sYear" placeholder="Año" min="2000" max="9999">
                        <button class="btn btn-sm btn-primary" id="btnContabilidadAnio"><i class="fas fa-search"></i></button>
                    </div>
                </div>
                <figure class="highcharts-figure"><div id="yearChart"></div></figure>
            </div>
        </div>
        <?php }?>
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <h4 class="mb-4">Últimos pedidos</h4>
                        <table class="table items">
                            <thead>
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Estado de pago</th>
                                <th scope="col">Estado de pedido</th>
                                <th scope="col">Monto</th>
                                <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(count($orders)){
                                        foreach ($orders as $order) {
                                            $status="";
                                            $statusOrder="";
                                            if($order['status'] =="pendent"){
                                                $status = '<span class="badge bg-warning text-white">pendiente</span>';
                                            }else{
                                                $status = '<span class="badge bg-success text-white">aprobado</span>';
                                            }
                                            if($order['statusorder'] =="confirmado"){
                                                $statusOrder = '<span class="badge bg-dark text-white">confirmado</span>';
                                            }else if($order['statusorder'] =="en preparacion"){
                                                $statusOrder = '<span class="badge bg-warning text-white">en preparacion</span>';
                                            }else if($order['statusorder'] =="preparado"){
                                                $statusOrder = '<span class="badge bg-info text-white">preparado</span>';
                                            }else if($order['statusorder'] =="entregado"){
                                                $statusOrder = '<span class="badge bg-success text-white">entregado</span>';
                                            }
                                ?>
                                <tr>
                                    <td data-label="#"><?=$order['idorder']?></td>
                                    <td data-label="Nombres:"><?=$order['name']?></td>
                                    <td data-label="Estado de pago:"><?=$status?></td>
                                    <td data-label="Estado de pedido:"><?=$statusOrder?></td>
                                    <td data-label="Monto:"><?=formatNum($order['amount'],false)?></td>
                                    <td class="text-center"><a href="<?=base_url()."/pedidos/pedido/".$order['idorder']?>" class="text-dark"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                <?php } }else{?>
                                <tr>
                                    <td colspan="5" class="text-center">No hay datos</td>
                                </tr>  
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <h4 class="mb-4">Últimos productos</h4>
                        <table class="table items">
                            <thead>
                                <tr>
                                    <th scope="col">Producto</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Descuento</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if(count($products)){
                                        foreach ($products as $product) {
                                ?>
                                <tr>
                                    <td class="text-center"><?=$product['name']?></td>
                                    <td data-label="Precio:"><?=formatNum($product['price'],false)?></td>
                                    <td data-label="Descuento:"><?=$product['discount']?>%</td>
                                    <td class="text-center"><a href="<?=base_url()."/tienda/producto/".$product['route']?>" target="_blank" class="text-dark"><i class="fas fa-eye"></i></a></td>
                                </tr>
                                <?php } }else{?>
                                <tr>
                                    <td colspan="4" class="text-center">No hay datos</td>
                                </tr>  
                                <?php }?>
                            </tbody>
                        </table>
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
        }]
    });
</script> 
