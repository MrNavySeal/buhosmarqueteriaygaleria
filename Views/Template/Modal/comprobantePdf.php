<?php 

$order = $data['orderdata'];
$detail = $data['orderdetail'];
$discount = $order['coupon'];
$total=0;
$subtotal = 0;
$status="";
$rows =0;
$arrAccount =$order['advance'];
if($order['status'] =="pendent"){
    $status = 'pendiente';
}else if($order['status'] =="approved"){
    $status = 'pagado';
}else if($order['status'] =="canceled"){
    $status = 'rechazado';
}
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel ="shortcut icon" href="<?=media()."/images/uploads/".$data['company']['logo']?>" sizes="114x114" type="image/png">
	<title>Pedido</title>
	<style type="text/css">
		p{
			font-family: arial;
            letter-spacing: 1px;
            font-size: 12px;
            margin:0;
		}
		.t-1{
			color:#E05A10;
		}
		.t-2{
			color:#03071E;
		}
		.t-3{
			color:#6D6A75;
		}
		.t-4{
			color:#fff;
		}
		.bg-1{
            color:#fff;
            background-color: #E05A10;
        }
        .bg-2{
            color:#fff;
            background-color: #03071E;
        }
        .bg-3{
            color:#fff;
            background-color: #6D6A75;
        }
        .bg-4{
            color:#fff;
            background-color: #03071E;
        }
        .w10{
            width:10%;
        }
        .w33{
            width:33.33%;
        }
        .w55{
            width:55%;
        }
        .w15{
            width:15%;
        }
        .w100{
            width:100%;
        }
        .fs-4 {
            font-size: 16px;
        }
		hr{border:0; border-top: 1px solid #CCC;}
		h4{font-family: arial; margin: 0;}
		table{
            margin: 10px 0;
            width:100%;
            max-width:600px; 
            caption-side: bottom;
            border-collapse: collapse;
        }
		table tr td, table tr th{
            padding: 5px 10px;
            font-family: arial; 
            font-size: 12px;
        }
		.table-bordered tr td{
            border: 1px solid #CCC;
        }
		.table-active{
            background-color: #CCC;
        }
		.text-center{
            text-align: center;
        }
		.text-right{
            text-align: right;
        }
        .fw-bold{
            font-weight:bold;
        }
		@media screen and (max-width: 470px) {
			.logo{width: 90px;}
			p, table tr td, table tr th{
                font-size: 9px;
            }
		}
	</style>
</head>
<body>
    <table class="table-bordered">
        <tr>
            <td class="w10">
                <img src="<?= media()."/images/uploads/".$data['company']['logo'];?>" alt="Logo" width="70" height="70">
            </td>
            <td class="w55 text-center">
                <h4><strong><?= $data['company']['name'] ?></strong></h4>
                <p>Oswaldo Parrado Clavijo</p>
                <p>NIT 17.344.806-8 No responsable de IVA</p>
                <p>
                    <?= $data['company']['addressfull']?> <br>
                    Teléfono: <?= $data['company']['phone'] ?> - <?=$data['company']['phones']?> <br>
                    Email: <?= $data['company']['email'] ?>
                </p>
            </td>
            <td class="w33 text-center" >
                <p class="m-0"><span class="fw-bold">Factura de venta</span></p>
                <p class="m-0"><span class="fs-4 fw-bold">No. <?=$order['idorder']?></span></p>
                <?php if($order['idtransaction'] != ""){?>
                <p class="m-0"><span class="fw-bold">Transacción</span></p>
                <p class="m-0"><span class="fs-4 fw-bold"><?=$order['idtransaction']?></span></p>
                <?php }?>
            </td>							
        </tr>
    </table>
    <table class="table-bordered">
        <tbody>
            <tr class="align-middle">
                <td class="fw-bold w10 bg-3">Nombre</td>
                <td colspan="4" style="width:57%;"><?=$order['name']?></td>
                <td class="fw-bold text-center w33 bg-3">Fecha de emisión</td>
            </tr>
            <tr class="align-middle">
                <td class="fw-bold bg-3">Dirección</td>
                <td colspan="4"><?=$order['address']?></td>
                <td class="text-center"><?=$order['date']?></td>
            </tr>
            <tr class="align-middle">
                <td class="fw-bold bg-3">Teléfono</td>
                <td colspan="4"><?=$order['phone']?></td>
                <td class="fw-bold text-center bg-3">Fecha de vencimiento</td>
            </tr>
            <tr class="align-middle">
                <td class="fw-bold bg-3">Correo</td>
                <td><?=$order['email']?></td>
                <td  class="fw-bold bg-3">CC/NIT</td>
                <td colspan="2"><?=$order['identification']?></td>
                <td class="text-center"><?=$order['date_beat']?></td>
            </tr>
            <tr class="align-middle">
                <td class="fw-bold bg-3">Tipo de pago</td>
                <td><?=$order['type']?></td>
                <td class="fw-bold bg-3">Estado de pago</td>
                <td><?=$status?></td>
                <td class="fw-bold bg-3">Estado de pedido</td>
                <td><?=$order['statusorder']?></td>
            </tr>
            <tr class="align-middle">
                <td class="fw-bold bg-3">Notas</td>
                <td colspan="5"><?=$order['note']?></td>
            </tr>
        </tbody>
    </table>
    <table class="table-bordered">
        <tbody>
            <tr class="bg-3 fw-bold">
                <td>Referencia</td>
                <td style="width:55.8%;">Descripción</td>
                <td class="text-right">Precio</td>
                <td class="text-right">Cantidad</td>
                <td class="text-right">Subtotal</td>
            </tr>
            <?php 
                if(count($detail) > 0){
                    
                    foreach ($detail as $product) {
                        $subtotal+= $product['quantity']*$product['price'];
            ?>
            <tr>
                <td class="text-start w10">
                    <?=$product['reference']?><br>
                </td>
                <?php
                    if($product['topic'] == 2 || $product['topic'] == 3){
                        $flag = substr($product['description'], 0,1) == "{" ? true : false;
                        if($flag){
                            $arrData = json_decode($product['description'],true);
                            $name = $arrData['name'];
                            $varDetail = $arrData['detail'];
                            $textDetail ="";
                            foreach ($varDetail as $d) {
                                $textDetail .= '<li><span class="fw-bold t-color-3">'.$d['name'].':</span> '.$d['option'].'</li>';
                            }
                            $product['description'] = $name.'<ul>'.$textDetail.'</ul>';
                        }
                ?>
                <td class="text-start w55">
                    <?=$product['description']?><br>
                </td>
                <?php 
                    }else{ 
                        $arrProducts = json_decode($product['description'],true);
                ?>
                <td class="text-start w55">
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
                <td class="text-right w10"><?=formatNum($product['price'],false)?></td>
                <td class="text-right w10"><?= $product['quantity'] ?></td>
                <td class="text-right w15"><?= formatNum($product['price']*$product['quantity'],false)?></td>
            </tr>
            <?php 		
                }
            } 
            ?>
            <?php
                if($order['idtransaction']!= ""){

            ?>
            <tr>
                <td colspan="4" class="text-right fw-bold bg-3">Subtotal:</td>
                <td class="text-right"><?= formatNum($subtotal,false)?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right fw-bold bg-3">Descuento:</td>
                <td class="text-right"><?= formatNum($discount)?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right fw-bold bg-3">Envio:</td>
                <td class="text-right"><?= formatNum($order['shipping'],false)?></td>
            </tr>
            <tr>
                <td colspan="4" class="text-right fw-bold bg-3">Total:</td>
                <td class="text-right"><?= formatNum($order['amount'],false)?></td>
            </tr>
            <?php } else{ if(!empty($arrAccount)){?>
                
                <tr>
                    <td colspan="2" class="text-center fw-bold bg-3">Anticipos realizados</td>
                    <td colspan="2" class="text-right fw-bold bg-3">Subtotal:</td>
                    <td class="text-right"><?= formatNum($subtotal,false)?></td>
                </tr>
                <tr>
                    <td class="fw-bold bg-3">Fecha</td>
                    <td class="fw-bold bg-3">Anticipo</td>
                    <td colspan="2" class="text-right fw-bold bg-3">Descuento:</td>
                    <td class="text-right"><?= formatNum($discount)?></td>
                </tr>
                <?php
                    $abonoTotal = 0;
                    for ($i=0; $i < count($arrAccount) ; $i++) { 
                        $abonoTotal+= intval($arrAccount[$i]['advance']);

                        $td="";
                        $td1="";
                        if($i == 0){
                            $td = '
                            <td colspan="2" class="text-right fw-bold bg-3">Envio:</td>
                            <td class="text-right">'.formatNum($order['shipping'],false).'</td>
                            ';
                        }else if($i == 1){
                            $td = '
                            <td colspan="2" class="text-right fw-bold bg-3">Total:</td>
                            <td class="text-right">'.formatNum($order['amount'],false).'</td>
                            ';
                        }
                        if(count($arrAccount) == 1){
                            $td1 = '
                            <td colspan="2" class="text-right fw-bold bg-3">Total:</td>
                            <td class="text-right">'.formatNum($order['amount'],false).'</td>
                            ';
                        }
                ?>
                <tr>
                    <td><?=$arrAccount[$i]['date']?></td>
                    <td><?=formatNum($arrAccount[$i]['advance'])." (".$arrAccount[$i]['type'].")"?></td>
                    <?=$td?>
                </tr>
                <?php }?>
                <tr>
                    <td class="fw-bold bg-3">Saldo total: </td>
                    <td><?=formatNum($order['amount']-$abonoTotal)?></td>
                     <?=$td1?>
                </tr>
                <?php }else{?>
                    <tr>
                    <td colspan="4" class="text-right fw-bold bg-3">Subtotal:</td>
                    <td class="text-right"><?= formatNum($subtotal,false)?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right fw-bold bg-3">Descuento:</td>
                    <td class="text-right"><?= formatNum($discount)?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right fw-bold bg-3">Envio:</td>
                    <td class="text-right"><?= formatNum($order['shipping'],false)?></td>
                </tr>
                <tr>
                    <td colspan="4" class="text-right fw-bold bg-3">Total:</td>
                    <td class="text-right"><?= formatNum($order['amount'],false)?></td>
                </tr>
            <?php } }?>
        </tbody>
    </table>
    <table class="text-center">
        <tbody>
            <tr><td class="w100"><p class="fw-bold">Después de 30 días no se responde por trabajos o pedidos finalizados</p></td></tr>
            <tr><td class="w100"><p class="fw-bold">Esta factura de compra venta se asimila en todos sus efectos
                legales a la letra de cambio de acuerdo al ART.774 del código de comercio
            </p></td></tr>
        </tbody>
    </table>								
</body>
</html>