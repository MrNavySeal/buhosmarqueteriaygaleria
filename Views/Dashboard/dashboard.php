<?php 
//dep($data['salesMonth']);exit;
    headerPage($data);
    $orders = $data['orders'];
    $productos = $data['products'];
    $costos=$data['resumenMensual']['costos']['total'];
    $ingresos = $data['resumenMensual']['ingresos']['total'];

    $ingresosAnual = $data['resumenAnual']['total'];
    $costosAnual = $data['resumenAnual']['costos'];
    $resultadoAnual = $ingresosAnual-$costosAnual;
    $resultadoMensual = $ingresos -$costos;

    $dataAnual = $data['resumenAnual']['data'];
?>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <div class="row">
                <?php if($_SESSION['userData']['roleid'] != 2 && $_SESSION['permitsModule']['r']){?>
                <div class="col-md-3">
                    <div class="card mb-4 position-relative">
                        <div style="font-size:5rem; color:#fff" class="p-5 card-header bg-primary position-relative d-flex justify-content-center align-items-center">
                            <i class="fas fa-users"></i>
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
                    <div class="card mb-4 position-relative">
                        <div style="font-size:5rem; color:#fff" class="p-5 card-header bg-info position-relative d-flex justify-content-center align-items-center">
                            <i class="fas fa-user"></i>
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
                    <div class="card mb-4 position-relative">
                        <div style="font-size:5rem; color:#fff" class="p-5 card-header bg-success position-relative d-flex justify-content-center align-items-center">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div class="card-body row text-center">
                            <div class="col">
                                <div class="fs-5 fw-semibold"><?=$data['totalSales']?></div>
                                <div class="text-uppercase text-medium-emphasis small">Ventas</div>
                            </div>
                        </div>
                        <a href="#" class="position-absolute w-100 h-100"></a>
                    </div>
                </div>
                <?php }?>
                <div class="col-md-3">
                    <div class="card mb-4 position-relative">
                        <div style="font-size:5rem; color:#fff" class="p-5 card-header bg-danger position-relative d-flex justify-content-center align-items-center">
                            <i class="fas fa-receipt"></i>
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
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h4 class="mb-4">Productos más recientes</h4>
                        <div class="row">
                            <?php
                                for ($i=0; $i < count($productos) ; $i++) { 
                                    $id = openssl_encrypt($productos[$i]['idproduct'],METHOD,KEY);
                                    $discount = "";
                                    
                                    $reference = $productos[$i]['reference']!="" ? "REF: ".$productos[$i]['reference'] : "";
                                    $variant = $productos[$i]['product_type'] == 2? "Desde " : "";
                                    $price ='</span><span class="current">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                    $favorite="";
                                    if($productos[$i]['favorite']== 0){
                                        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav "><i class="far fa-heart "></i></button>';
                                    }else{
                                        $favorite = '<button type="button" onclick="addWishList(this)" data-id="'.$id.'" class="btn btn-bg-3 btn-fav active"><i class="fas fa-heart text-danger "></i></button>';
                                    }
                                    if($productos[$i]['discount'] > 0 && $productos[$i]['stock'] > 0){
                                        $discount = '<span class="discount">-'.$productos[$i]['discount'].'%</span>';
                                        $price ='<span class="current sale me-2">'.$variant.formatNum($productos[$i]['price']*(1-($productos[$i]['discount']*0.01)),false).'</span><span class="compare">'.$variant.formatNum($productos[$i]['price']).'</span>';
                                    }else if($productos[$i]['stock'] == 0){
                                        $price = '<span class="current sale me-2">Agotado</span>';
                                    }

                            ?>
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card--product">
                                    <div class="card--product-img">
                                        <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>">
                                            <?=$discount?>
                                            <img src="<?=$productos[$i]['url']?>" alt="Cuadros decorativos <?=$productos[$i]['subcategory']?>">
                                        </a>
                                    </div>
                                    <div class="card--product-info">
                                        <h4><a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>"><?=$productos[$i]['name']?></a></h4>
                                        <p class="text-center t-color-3 m-0 fs-6"><?=$reference?></p>
                                        <div class="card--price">
                                            <?=$price?>
                                        </div>
                                        
                                    </div>
                                    <div class="card--product-btns">
                                        <div class="d-flex">
                                            <?=$favorite?>
                                            <?php if($productos[$i]['product_type'] == 1 && $productos[$i]['stock'] > 0){?>
                                            <button type="button" class="btn btn-bg-1" data-id="<?=$id?>" data-topic="2" onclick="addCart(this)"><i class="fas fa-shopping-cart"></i></button>
                                            <?php }else if($productos[$i]['product_type'] == 2){?>
                                            <a href="<?=base_url()."/tienda/producto/".$productos[$i]['route']?>" class="btn btn-bg-1 w-100"><i class="fas fa-exchange-alt"></i></a>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <div class="text-center mt-3">
                            <a href="<?=base_url()?>/tienda" class="btn btn-bg-2">Ver todo</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main> 
<?php footerPage($data)?>     
<script>
    Highcharts.chart('monthChart', {
        chart: {
            type: 'line'
        },
        title: {
            text: 'Gráfico de <?=$data['resumenMensual']['ingresos']['month']." ".$data['resumenMensual']['ingresos']['year']?>'
        },
        subtitle: {
            text: `Ingresos: <?=formatNum($ingresos)?> - Costos: <?=formatNum($costos)?><br>
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
            text: `Ingresos: <?=formatNum($ingresosAnual)?> - Costos: <?=formatNum($costosAnual)?><br>
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
        }]
    });
</script> 
