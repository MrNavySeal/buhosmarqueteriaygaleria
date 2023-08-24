<?php 
    headerPage($data);
    $order = $data['orderdata'];
    $detail = $data['orderdetail'];
    $total=0;
    $company = $data['company'];
    $subtotal = 0;
    $status="";
    $discount=$order['coupon'];
    $arrAccount =!empty( $order['suscription']) ? json_decode($order['suscription'],true) : "";
    if($order['status'] =="pendent"){
        $status = 'pendiente';
    }else if($order['status'] =="approved"){
        $status = 'aprobado';
    }else if($order['status'] =="canceled"){
        $status = 'cancelado';
    }
?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <div id="orderInfo" class="position-relative overflow-hidden"> 
                    <div class="d-flex justify-content-between flex-wrap mb-3">
                        <div class="mb-3 d-flex flex-wrap align-items-center">
                            <img src="<?=media()."/images/uploads/".$company['logo']?>" class="me-2" style="width=170px;height:170px;" alt="">
                            <div>
                                <p class="m-0 fw-bold"><?=$company['name']?></p>
                                <p class="m-0">Oswaldo Parrado Clavijo</p>
                                <p class="m-0">NIT 17.344.806-8 No responsable de IVA</p>
                                <p class="m-0"><?=$company['addressfull']?></p>
                                <p class="m-0"><?=$company['phone']?> - <?=$company['phones']?></p>
                                <p class="m-0"><?=$company['email']?></p>
                                <p class="m-0"><?=BASE_URL?></p>
                            </div>
                        </div>
                        <div class="text-start">
                            <p class="m-0"><span class="fw-bold">Fecha: </span><?=$order['date']?></p>
                            <p class="m-0"><span class="fw-bold">Factura de venta: </span>#<?=$order['idorder']?></p>
                            <?php if($order['idtransaction'] != ""){?>
                            <p class="m-0"><span class="fw-bold">Transacción: </span><?=$order['idtransaction']?></p>
                            <?php }?>
                            <p class="m-0"><span class="fw-bold">Tipo de pago: </span><?=$order['type']?></p>
                            <p class="m-0"><span class="fw-bold">Estado de pago: </span><?=$status?></p>
                            <p class="m-0"><span class="fw-bold">Estado de pedido: </span><?=$order['statusorder']?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <p class="m-0 mb-2 fw-bold">Cliente</p>
                            <p class="m-0">Nombre: <?=$order['name']?></p>
                            <p class="m-0">CC/NIT: <?=$order['identification']?></p>
                            <p class="m-0">Teléfono: <?=$order['phone']?></p>
                            <p class="m-0">Email: <?=$order['email']?></p>
                            <p class="m-0">Dirección: <?=$order['address']?></p>
                            <p class="m-0 fw-bold mt-3">Notas:</p>
                            <p class="m-0"><?=$order['note']?></p> 
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody class="text-start">
                                <tr class="fw-bold ">
                                    <td>Referencia</td>
                                    <td>Descripcion</td>
                                    <td>Precio</td>
                                    <td>Cantidad</td>
                                    <td>Subtotal</td>
                                </tr>
                                <?php 
                                    if(count($detail) > 0){
                                        foreach ($detail as $product) {
                                            $subtotal+= $product['quantity']*$product['price'];
                                ?>
                                <tr>
                                <td data-label="Referencia: ">
                                    <?=$product['reference']?><br>
                                </td>
                                    <?php
                                        if($product['topic'] == 2 || $product['topic'] == 3){
                                    ?>
                                <td class="text-break text-start">
                                    <?=$product['description']?><br>
                                </td>
                                <?php 
                                    }else{ 
                                        $arrProducts = json_decode($product['description'],true);
                                        $photo = "";
                                        if($arrProducts['photo']!="" && $arrProducts['photo']!="retablo.png"){
                                            $photo = '<img src="'.media()."/images/uploads/".$arrProducts['photo'].'" width="70" height="70"><br>';
                                        }
                                ?>
                                <td class="text-start w100">
                                    <?=$photo?>
                                    <?=$arrProducts['name']?>
                                <?php
                                    $borderStyle = $arrProducts['style'] == "Flotante" ? "marco interno" : "bocel";
                                    $marginStyle = $arrProducts['style'] == "Flotante" || $arrProducts['style'] == "Flotante sin marco interno" ? "fondo" : "paspartú";
                                    $glass = isset($arrProducts['glass']) ? '<li><span class="fw-bold t-color-3">Tipo de vidrio:</span> '.$arrProducts['glass'].'</li>' : "";
                                    $material = isset($arrProducts['material']) ? '<li><span class="fw-bold t-color-3">Material del marco:</span> '.$arrProducts['material'].'</li>' : "";
                                    $colorFrame = isset($arrProducts['colorframe']) ? '<li><span class="fw-bold t-color-3">Color del marco:</span> '.$arrProducts['colorframe'].'</li>' : "";
                                    $margen = $arrProducts['margin'] > 0 ? '<li><span class="fw-bold t-color-3">Medida '.$marginStyle.':</span> '.$arrProducts['margin'].'cm</li>' : "";
                                    $colorMargen = $arrProducts['colormargin'] != "" ? '<li><span class="fw-bold t-color-3">Color del '.$marginStyle.':</span> '.$arrProducts['colormargin'].'</li>' : "";
                                    $colorBorder = $arrProducts['colorborder'] != "" ? '<li><span class="fw-bold t-color-3">Color del '.$borderStyle.':</span> '.$arrProducts['colorborder'].'</li>' : "";
                                    $medidas = $arrProducts['width']."cm X ".$arrProducts['height']."cm";
                                    $medidasMarco = ($arrProducts['width']+($arrProducts['margin']*2))."cm X ".($arrProducts['height']+($arrProducts['margin']*2))."cm"; 
                                ?>
                                <?php if($arrProducts['idType'] == 1 || $arrProducts['idType'] == 3){?>
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                                    <?=$colorFrame?>
                                    <?=$material?>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Estilo de enmarcación:</span> <?=$arrProducts['style']?></li>
                                    <?=$margen?>
                                    <li><span class="fw-bold t-color-3">Medida imagen:</span> <?=$medidas?></li>
                                    <li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
                                    <?=$colorMargen?>
                                    <?=$colorBorder?>
                                    <?=$glass?>
                                </ul>
                                <?php }else if($arrProducts['idType'] == 4){?>
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                                    <?=$colorFrame?>
                                    <?=$material?>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Estilo de enmarcación:</span> <?=$arrProducts['style']?></li>
                                    <?=$margen?>
                                    <li><span class="fw-bold t-color-3">Medida imagen:</span> <?=$medidas?></li>
                                    <li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
                                    <?=$colorMargen?>
                                    <?=$colorBorder?>
                                    <li><span class="fw-bold t-color-3">Bastidor:</span> <?=$arrProducts['glass']?></li>
                                </ul>
                                <?php }else if($arrProducts['idType'] == 5){?>
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                                    <?=$colorFrame?>
                                    <li><span class="fw-bold t-color-3">Material del marco:</span> <?=$arrProducts['material']?></li>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Tipo de espejo:</span> <?=$arrProducts['style']?></li>
                                    <li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
                                </ul>
                                <?php }else if($arrProducts['idType'] == 6){?>
                                    <ul>
                                        <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                                        <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                        <?=$margen?>
                                        <li><span class="fw-bold t-color-3">Medidas del marco:</span> <?=$medidasMarco?></li>
                                        <?=$colorMargen?>
                                    </ul>
                                <?php }else if($arrProducts['idType'] == 8){?>
                                    <ul>
                                        <li><span class="fw-bold t-color-3">Impresión:</span> <?=$arrProducts['style']?></li>
                                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                        <li><span class="fw-bold t-color-3">Color del borde:</span> <?=$arrProducts['colorborder']?></li>
                                    </ul>
                                <?php }else if($arrProducts['idType'] == 9){?>
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts['style']?></li>
                                    <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                </ul>
                                <?php }?>
                                </td>
                                <?php }?>
                                <td data-label="Precio: "><?=formatNum(floor($product['price']),false)?></td>
                                <td data-label="Cantidad: "><?= $product['quantity'] ?></td>
                                <td data-label="Subtotal: "><?= formatNum(floor($product['price']*$product['quantity']),false)?></td>
                                </tr>
                                <?php 		
                                    }
                                } 
                                ?>
                                <?php
                                    if($order['idtransaction']!= ""){

                                ?>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Subtotal:</td>
                                    <td data-label="Subtotal:"><?= formatNum($subtotal,false)?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Descuento:</td>
                                    <td data-label="Descuento"><?= formatNum($discount)?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Envio:</td>
                                    <td data-label="Envio"><?= formatNum($order['shipping'],false)?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-end fw-bold">Total:</td>
                                    <td data-label="Total"><?= formatNum($order['amount'],false)?></td>
                                </tr>
                                <?php } else{ ?>
                                    <tr>
                                        <td colspan="3" class="p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td colspan="2" class="text-center fw-bold">Anticipos realizados</td>
                                                </tr>
                                                <tr>
                                                    <td class="fw-bold">Fecha</td>
                                                    <td class="fw-bold">Anticipo</td>
                                                </tr>

                                                <?php
                                                    if(!empty($arrAccount)){
                                                    $abonoTotal = 0;
                                                    foreach ($arrAccount as $acc) {
                                                        $abonoTotal+= intval($acc['debt']);
                                                        $date = explode("-",$acc['date']);
                                                        $date = $date[2]."/".$date[1]."/".$date[0];
                                                ?>
                                                <tr>
                                                    <td><?=$date?></td>
                                                    <td><?=formatNum($acc['debt'])." (".$acc['type'].")"?></td>
                                                </tr>
                                                <?php }?>
                                                <tr>
                                                    <td class="text-end fw-bold">Saldo total: </td>
                                                    <td><?=formatNum($order['amount']-$abonoTotal)?></td>
                                                </tr>
                                                <?php }?>
                                            </table>
                                        </td>
                                        <td colspan="2" class="p-0">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td class="text-end fw-bold">Subtotal:</td>
                                                    <td data-label="Subtotal:"><?= formatNum($subtotal,false)?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end fw-bold">Descuento:</td>
                                                    <td data-label="Descuento"><?= formatNum($discount)?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end fw-bold">Envio:</td>
                                                    <td data-label="Envio"><?= formatNum($order['shipping'],false)?></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-end fw-bold">Total:</td>
                                                    <td data-label="Total"><?= formatNum($order['amount'],false)?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                <?php }?>
                            </tbody>
                        </table>
                    </div>
                    <table class="table text-center">
                        <tbody>
                            <tr><td><p class="fw-bold">Después de 30 días no se responde por trabajos o pedidos finalizados</p></td></tr>
                            <tr><td><p class="fw-bold">Esta factura de compra venta se asimila en todos sus efectos
                                legales a la letra de cambio de acuerdo al ART.774 del código de comercio
                            </p></td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-6 text-start">
                        <a href="<?=base_url()?>/pedidos" class="btn btn-secondary text-white"><i class="fas fa-arrow-circle-left"></i> Regresar</a>
                    </div>
                    <div class="col-6 text-end">
                    <a href="<?=base_url()."/factura/generarFactura/".$order['idorder']?>" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?>             