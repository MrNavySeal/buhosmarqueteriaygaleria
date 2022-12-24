<?php 

$order = $data['order']['order'];
$detail = $data['order']['detail'];
$subtotal = 0;
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
							Teléfono: <?= "+".$data['company']['phonecode']." ".$data['company']['phone'] ?> <br>
							Email: <?= $data['company']['email'] ?>
						</p>
					</div>
				</td>
				<td width="33.33%">
					<div class="text-right">
						<p>
							Pedido: <strong><?= $order['idorder'] ?></strong><br>
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
		      <th>Descripción</th>
		      <th class="text-right">Precio</th>
		      <th class="text-center">Cantidad</th>
		      <th class="text-right">Total</th>
		    </tr>
		  </thead>
		  <tbody id="detalleOrden">
		  	<thead class="text-center">
				<tr>
					<th>Descripcion</th>
					<th>Precio</th>
					<th>Cantidad</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
			<?php 
				if(count($detail) > 0){
					
					foreach ($detail as $product) {
						$subtotal+= $product['quantity']*$product['price'];
			?>
			<tr>
				<?php
					if($product['topic'] == 2 || $product['topic'] == 3){
				?>
			<td class="text-start">
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
				<td class="text-start">
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
					<th colspan="3" class="text-end">Subtotal:</th>
					<td class="text-right"><?= formatNum(floor($subtotal),false)?></td>
				</tr>
				<?php
					if(isset($order['cupon'])){
						$cupon = $order['cupon'];
						$subtotal = $subtotal - ($subtotal*($cupon['discount']/100));
				?>
				<tr>
					<th colspan="3" class="text-end">Cupon:</th>
					<td class="text-right"><?= $cupon['code']." - ".$cupon['discount']?>%</td>
				</tr>
				<tr>
					<th colspan="3" class="text-end">Subtotal:</th>
					<td class="text-right"><?= formatNum(floor($subtotal),false)?></td>
				</tr>
				<?php }?>
				<tr>
					<th colspan="3" class="text-end">Envio:</th>
					<td class="text-right"><?= formatNum($order['shipping'],false)?></td>
				</tr>
				<tr>
					<th colspan="3" class="text-end">Total:</th>
					<td class="text-right"><?= formatNum($order['amount'],false)?></td>
				</tr>
		</tfoot>
		</table>
		<div class="text-center">
			<h4>Gracias por tu compra!</h4>			
		</div>
	</div>									
</body>
</html>