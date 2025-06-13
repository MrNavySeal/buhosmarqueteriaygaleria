<?php 
//dep($data['salesMonth']);exit;
    headerAdmin($data);
    $orders = $data['orders'];
    $productos = $data['products'];
    $costos=$data['resumenMensual']['costos']['total'];
    $gastos = $data['resumenMensual']['gastos']['total'];
    $ingresos = $data['resumenMensual']['ingresos']['total'];

    $ingresosAnual = $data['resumenAnual']['total'];
    $costosAnual = $data['resumenAnual']['costos'];
    $gastosAnual = $data['resumenAnual']['gastos'];

    $resultadoAnual = $ingresosAnual-($costosAnual+$gastosAnual);
    $resultadoMensual = $ingresos -($costos+$gastos);

    $dataAnual = $data['resumenAnual']['data'];
?>
    
    <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
        <div class="row">
        <?php if($_SESSION['userData']['roleid'] != 2 && $_SESSION['permitsModule']['r']){?>
        <div class="col-md-2">
            <div class="card mb-4 position-relative">
                <div style="font-size:5rem; color:#fff" class="p-5 card-header bg-primary position-relative d-flex justify-content-center align-items-center">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="card-body row text-center">
                    <div class="col">
                        <div class="fs-5 fw-semibold"><?=$data['totalViews']?></div>
                        <div class="text-uppercase text-medium-emphasis small">Visitas</div>
                    </div>
                </div>
                <a href="#" class="position-absolute w-100 h-100"></a>
            </div>
        </div>
        <div class="col-md-2">
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
        <div class="col-md-2">
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
        <h2 class="p-4">Gráficos de ventas</h2>
        <div class="row">
            <div class="col-md-6">
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
            <div class="col-md-6">
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
        </div>
    </div>
    <div class="card mb-4">
        <h2 class="p-4">Gráficos de visitas</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <div class="d-flex align-items-center">
                            <input  class="date-picker visitasMes" name="visitasMes" placeholder="Mes y año" required>
                            <button class="btn btn-sm btn-primary" id="btnVisitasMes"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <figure class="highcharts-figure mb-3 mt-3"><div id="monthChartViews"></div></figure>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <div class="d-flex justify-content-end mb-3">
                        <div class="d-flex align-items-center">
                            <input type="number" name="visitasAnio" id="viewYear" placeholder="Año" min="2000" max="9999">
                            <button class="btn btn-sm btn-primary" id="btnVisitasAnio"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                    <figure class="highcharts-figure"><div id="yearChartViews"></div></figure>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card-body">
                    <figure class="highcharts-figure mb-3 mt-3"><div id="countryChartViews"></div></figure>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card-body">
                    <figure class="highcharts-figure mb-3 mt-3"><div id="pageChartViews"></div></figure>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    <!--
    <div class="card mb-4">
        <div class="card-body overflow-auto">
            <h4 class="mb-4">Últimos pedidos</h4>
            <table class="table">
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
    </div>-->
    
<?php footerAdmin($data)?>     
