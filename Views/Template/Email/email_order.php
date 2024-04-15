<?php 

$order = $data['order']['order'];
$detail = $data['order']['detail'];
$subtotal = 0;
$discount = $order['coupon'];
 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Pedido</title>
	<style type="text/css">
		p{
			font-family: arial;letter-spacing: 1px;color: #7f7f7f;font-size: 12px;
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
		hr{border:0; border-top: 1px solid #CCC;}
		h4{font-family: arial; margin: 0;}
		table{width: 100%; max-width: 600px; margin: 10px auto; border: 1px solid #CCC; border-spacing: 0;}
		table tr td, table tr th{padding: 5px 10px;font-family: arial; font-size: 12px;}
		#detalleOrden tr td{border: 1px solid #CCC;}
		.table-active{background-color: #CCC;}
		.text-center{text-align: center;}
		.text-right{text-align: right;}

		@media screen and (max-width: 470px) {
			.logo{width: 90px;}
			p, table tr td, table tr th{font-size: 9px;}
		}
	</style>
</head>
<body>
	<div>
		<br>
		<p class="text-center">Se ha generado un pedido, a continuación encontrará los datos.</p>
		<br>
		<hr>
		<br>
		<table>
			<tr>
				<td width="33.33%">
					<img src="<?= media();?>/images/uploads/icon.gif" alt="Logo" width="100px" height="100px">
				</td>
				<td width="33.33%">
					<div class="text-center">
						<h4><strong><?= $data['company']['name'] ?></strong></h4>
						<p>
							<?= $data['company']['addressfull']?> <br>
							Teléfono: <?=$data['company']['phone']." ".$data['company']['phones'] ?> <br>
							Email: <?= $data['company']['email'] ?>
						</p>
					</div>
				</td>
				<td width="33.33%">
					<div class="text-right">
						<p>
							Factura de venta: <strong>#<?= $order['idorder'] ?></strong><br>
                            Fecha: <?= $order['date'] ?><br>
						</p>
					</div>
				</td>				
			</tr>
		</table>
		<table>
			<tr>
		    	<td width="140">Nombre:</td>
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
		    	<td>Notas:</td>
		    	<td><?= $order['note']?></td>
		    </tr>
		</table>
		<table>
		  <thead class="table-active">
		    <tr>
			  <th>Referencia</th>
		      <th>Descripción</th>
		      <th class="text-right">Precio</th>
		      <th class="text-center">Cantidad</th>
		      <th class="text-right">Subtotal</th>
		    </tr>
		  </thead>
		  <tbody id="detalleOrden">
			<tbody>
			<?php 
				if(count($detail) > 0){
					
					foreach ($detail as $product) {
						$subtotal+= $product['quantity']*$product['price'];
			?>
			<tr>
			<td class="text-start">
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
			<td class="text-start">
				<?=$product['description']?><br>
			</td>
				<?php 
					}else{ 
						$arrProducts = json_decode($product['description'],true);
						/*$photo = "";
						if($arrProducts['photo']!=""){
							$photo = '<img src="'.media()."/images/uploads/".$arrProducts['photo'].'" width="70" height="70"><br>';
						}*/
				?>
				<td class="text-start">
					<?=$arrProducts['name']?>
				<?php
					$borderStyle = $arrProducts['style'] == "Flotante" ? "marco interno" : "bocel";
					$marginStyle = $arrProducts['style'] == "Flotante" ? "fondo" : "paspartú";
					$colorFrame = $arrProducts['colorframe'] != "" ? '<li><span class="fw-bold t-color-3">Color del marco:</span> '.$arrProducts['colorframe'].'</li>' : "";
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
					<li><span class="fw-bold t-color-3">Material del marco:</span> <?=$arrProducts['material']?></li>
					<li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts['orientation']?></li>
					<li><span class="fw-bold t-color-3">Estilo de enmarcación:</span> <?=$arrProducts['style']?></li>
					<?=$margen?>
					<li><span class="fw-bold t-color-3">Medida imagen:</span> <?=$medidas?></li>
					<li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
					<?=$colorMargen?>
					<?=$colorBorder?>
					<li><span class="fw-bold t-color-3">Tipo de vidrio:</span> <?=$arrProducts['glass']?></li>
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
			<td class="text-right"><?=formatNum($product['price'],false)?></td>
			<td class="text-center"><?= $product['quantity'] ?></td>
			<td class="text-right"><?= formatNum($product['price']*$product['quantity'],false)?></td>
			</tr>
			<?php 		
				}
			} 
			?>
		  </tbody>
		  <tfoot>
				<tr>
					<th colspan="4" class="text-end">Subtotal:</th>
					<td class="text-right"><?= formatNum(floor($subtotal),false)?></td>
				</tr>
				<tr>
					<th colspan="4" class="text-end">Descuento:</th>
					<td class="text-right"><?= formatNum(floor($discount),false)?></td>
				</tr>
				<tr>
					<th colspan="4" class="text-end">Envio:</th>
					<td class="text-right"><?= formatNum($order['shipping'],false)?></td>
				</tr>
				<tr>
					<th colspan="4" class="text-end">Total:</th>
					<td class="text-right"><?= formatNum($order['amount'],false)?></td>
				</tr>
		</tfoot>
		</table>
		<div class="text-center">
			<h4>Gracias por tu compra!</h4>		
			<p>Recuerda que puedes ver tu pedido en tu cuenta de usuario</p>	
		</div>
	</div>									
</body>
</html>