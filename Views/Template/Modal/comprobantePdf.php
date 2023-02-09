<?php 

$order = $data['orderdata'];
$detail = $data['orderdetail'];
$total=0;
$subtotal = 0;
$status = "";
if($order['status'] == "approved"){
    $status = 'aprobado';
}else{
    $status = 'pendiente';
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
		hr{border:0; border-top: 1px solid #CCC;}
		h4{font-family: arial; margin: 0;}
		table{
            margin: 10px 0;
            width:100%;
            max-width:600px; 
            border: 1px solid #CCC; 
            border-spacing: 0;
        }
		table tr td, table tr th{
            padding: 5px 10px;
            font-family: arial; 
            font-size: 12px;
        }
		#detalleOrden tr td{
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
		@media screen and (max-width: 470px) {
			.logo{width: 90px;}
			p, table tr td, table tr th{
                font-size: 9px;
            }
		}
	</style>
</head>
<body>
    <table>
        <tr>
            <td class="w33">
                <img src="<?= media()."/images/uploads/".$data['company']['logo'];?>" alt="Logo" width="70" height="70">
            </td>
            <td class="w33 text-center">
                <h4><strong><?= $data['company']['name'] ?></strong></h4>
                <p>Oswaldo Parrado Clavijo</p>
                <p>NIT 17.344.806-8 No responsable de IVA</p>
                <p>
                    <?= $data['company']['addressfull']?> <br>
                    Teléfono: <?= $data['company']['phone'] ?> - 3193094264 <br>
                    Email: <?= $data['company']['email'] ?>
                </p>
            </td>
            <td class=" w33 text-right">
                <p>
                    Fecha: <?= $order['date'] ?><br>
                    Factura de venta: <strong>#<?= $order['idorder'] ?></strong><br>
                    <?php if($order['idtransaction']){?>
                    Transacción: <strong><?= $order['idtransaction'] ?></strong><br>
                    <?php }?>
                    Tipo de pago: <?=$order['type']?><br>
                </p>
            </td>				
        </tr>
    </table>
    <table>
        <tr>
            <td>Nombre:</td>
            <td><?= $order['name'] ?></td>
        </tr>
        <tr>
            <td>CC/NIT:</td>
            <td><?= $order['identification'] ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $order['email'] ?></td>
        </tr>
        <tr>
            <td>Teléfono</td>
            <td><?= $order['phone'] ?></td>
        </tr>
        <tr>
            <td>Dirección:</td>
            <td><?= $order['address']?></td>
        </tr>
        <tr>
            <td><strong>Notas:</strong></td>
            <td><?= $order['note']?></td>
        </tr>
    </table>
    <table>
        <thead class="table-active">
        <tr>
            <th>Descripción</th>
            <th class="text-right">Precio</th>
            <th class="text-right">Cantidad</th>
            <th class="text-right">Total</th>
        </tr>
        </thead>
        <tbody id="detalleOrden">
            <?php 
                if(count($detail) > 0){
                    
                    foreach ($detail as $product) {
                        $subtotal+= $product['quantity']*$product['price'];
            ?>
            <tr>
                <?php
                    if($product['topic'] == 2 || $product['topic'] == 3){
                ?>
                <td class="text-start w55">
                    <?=$product['description']?><br>
                </td>
                <?php 
                    }else{ 
                        $arrProducts = json_decode($product['description'],true);
                        $photo = "";
                        if($arrProducts['photo']!=""){
                            $photo = '<img src="'.media()."/images/uploads/".$arrProducts['photo'].'" width="70" height="70"><br>';
                        }
                ?>
                <td class="text-start w55">
                    <?=$photo?>
                    <?=$arrProducts['name']?>
                    <?php
                        $margen = $arrProducts['margin'] > 0 ? '<li><span class="fw-bold t-color-3">Margen:</span> '.$arrProducts['margin'].'cm</li>' : "";
                        $colorMargen = $arrProducts['colormargin'] != "" ? '<li><span class="fw-bold t-color-3">Color margen:</span> '.$arrProducts['colormargin'].'</li>' : "";
                        $colorBorder = $arrProducts['colorborder'] != "" ? '<li><span class="fw-bold t-color-3">Color Borde:</span> '.$arrProducts['colorborder'].'</li>' : "";
                        $medidas = $arrProducts['width']."cm X ".$arrProducts['height']."cm";
                        $medidasMarco = ($arrProducts['width']+($arrProducts['margin']*2))."cm X ".($arrProducts['height']+($arrProducts['margin']*2))."cm"; 
                    ?>
                    <?php if($arrProducts['idType'] == 1 || $arrProducts['idType'] == 4 || $arrProducts['idType'] == 5){?>
                    <ul>
                        <li><span class="fw-bold text-start">Referencia:</span> <?=$arrProducts['reference']?></li>
                        <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                        <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts['style']?></li>
                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                        <?=$margen?>
                        <li><span class="fw-bold t-color-3">Medidas del marco:</span> <?=$medidasMarco?></li>
                        <?=$colorMargen?>
                        <?=$colorBorder?>
                    </ul>
                    <?php }else if($arrProducts['idType'] == 3 || $arrProducts['idType'] == 7){?>
                    <ul>
                        <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts['reference']?></li>
                        <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
                        <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts['style']?></li>
                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
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
                            <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts['style']?></li>
                            <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                            <?=$colorBorder?>
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
                <td class="text-right w15"><?=formatNum($product['price'],false)?></td>
                <td class="text-right w15"><?= $product['quantity'] ?></td>
                <td class="text-right w15"><?= formatNum($product['price']*$product['quantity'],false)?></td>
            </tr>
            <?php 		
                }
            } 
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Subtotal:</th>
                <td class="text-right"><?= formatNum(floor($subtotal),false)?></td>
            </tr>
            <?php
                if(isset($order['cupon'])){
                    $cupon = $order['cupon'];
                    $subtotal = $subtotal - ($subtotal*($cupon['discount']/100));
            ?>
            <tr>
                <th colspan="3" class="text-right">Cupon:</th>
                <td class="text-right"><?= $cupon['code']." - ".$cupon['discount']?>%</td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Subtotal:</th>
                <td class="text-right"><?= formatNum(floor($subtotal),false)?></td>
            </tr>
            <?php }?>
            <tr>
                <th colspan="3" class="text-right">Envio:</th>
                <td class="text-right"><?= formatNum($order['shipping'],false)?></td>
            </tr>
            <tr>
                <th colspan="3" class="text-right">Total:</th>
                <td class="text-right"><?= formatNum($order['amount'],false)?></td>
            </tr>
        </tfoot>
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