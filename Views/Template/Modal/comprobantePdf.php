<?php 
$order = $data['orderdata'];
$detail = $data['orderdetail'];
$arrRows = $detail['rows'];
$discount = $order['coupon'];
$total=0;
$subtotal = $detail['subtotal'];
$status="";
$rows =0;
$arrAccount =$order['advance'];
if($order['status'] =="pendent"){
    $status = 'crédito';
}else if($order['status'] =="approved"){
    $status = 'pagado';
}else if($order['status'] =="canceled"){
    $status = 'anulado';
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
                <td colspan="5" style="width:90%;"><?=$order['note']?></td>
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
            <?php foreach ($arrRows as $row) { ?>
            <tr>
                <td class="text-start w10"><?=$row['reference']?></td>
                <td class="text-start w55"><?=$row['description']?></td>
                <td class="text-right w10"><?=$row['price']?></td>
                <td class="text-start w10"><?=$row['qty']?></td>
                <td class="text-start w15"><?=$row['subtotal']?></td>
            </tr>
            <?php } ?>
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
        </tbody>
    </table>
    <table class="text-center">
        <tbody>
            <tr><td class="w100"><p class="fw-bold">Esta factura de compra venta se asimila en todos sus efectos
                legales a la letra de cambio de acuerdo al ART.774 del código de comercio
            </p></td></tr>
        </tbody>
    </table>								
</body>
</html>