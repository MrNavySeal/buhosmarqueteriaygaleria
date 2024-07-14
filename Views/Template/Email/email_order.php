<?php 
$company = getCompanyInfo();
$order = $data['order']['order'];
$arrDet = $data['order']['detail'];
$subtotal = 0;
$discount = $order['coupon'];
$html="";
$arrRows = [];
foreach ($arrDet as $data) {
	$subtotalProduct =$data['quantity']*$data['price'];
	$subtotal+= $data['quantity']*$data['price'];
	$description="";
	if($data['topic'] == 1){
		$detail = json_decode($data['description']);
		$img ="";
		if(isset($detail->type)){
			$intWidth = floatval($detail->width);
			$intHeight = floatval($detail->height);
			$intMargin = floatval($detail->margin);
			$colorFrame =  $detail->colorframe ? $detail->colorframe : "";
			$material = $detail->material ? $detail->material : "";
			$marginStyle = $detail->style == "Flotante" || $detail->style == "Flotante sin marco interno" ? "Fondo" : "Paspartú";
			$borderStyle = $detail->style == "Flotante" ? "marco interno" : "bocel";
			$glassStyle = $detail->idType == 4 ? "Bastidor" : "Tipo de vidrio";
			$measureFrame = ($intWidth+($intMargin*2))."cm X ".($intHeight+($intMargin*2))."cm";
			if($detail->photo !=""){
				$img = '<a href="'.media().'/images/uploads/'.$detail->photo.'" target="_blank">Ver imagen</a><br>';
			}
			$description.='
					'.$img.'
					'.$detail->name.'
					<ul>
						<li><span class="fw-bold t-color-3">Referencia: </span>'.$detail->reference.'</li>
						<li><span class="fw-bold t-color-3">Color del marco: </span>'.$colorFrame.'</li>
						<li><span class="fw-bold t-color-3">Material: </span>'.$material.'</li>
						<li><span class="fw-bold t-color-3">Orientación: </span>'.$marginStyle.'</li>
						<li><span class="fw-bold t-color-3">Estilo de enmarcación: </span>'.$detail->style.'</li>
						<li><span class="fw-bold t-color-3">'.$marginStyle.': </span>'.$detail->margin.'cm</li>
						<li><span class="fw-bold t-color-3">Medida imagen: </span>'.$detail->width.'cm X '.$detail->height.'cm</li>
						<li><span class="fw-bold t-color-3">Medida marco: </span>'.$measureFrame.'</li>
						<li><span class="fw-bold t-color-3">Color del '.$marginStyle.': </span>'.$detail->colormargin.'</li>
						<li><span class="fw-bold t-color-3">Color del '.$borderStyle.': </span>'.$detail->colorborder.'</li>
						<li><span class="fw-bold t-color-3">'.$glassStyle.': </span>'.$detail->glass.'</li>
					</ul>
			';
		}else{
			if($detail->img !="" && $detail->img !=null){
				$img = '<a href="'.media().'/images/uploads/'.$detail->img.'" target="_blank">Ver imagen</a><br>';
			}
			$htmlDetail ="";
			$arrDet = $detail->detail;
			foreach ($arrDet as $d) {
				$htmlDetail.='<li><span class="fw-bold t-color-3">'.$d->name.': </span>'.$d->value.'</li>';
			}
			$description = $img.$detail->name.'<ul>'.$htmlDetail.'</ul>';
		}
	}else{
		$description=$data['description'];
		$flag = substr($data['description'], 0,1) == "{" ? true : false;
		if($flag){
			$arrData = json_decode($data['description'],true);
			$name = $arrData['name'];
			$varDetail = $arrData['detail'];
			$textDetail ="";
			foreach ($varDetail as $d) {
				$textDetail .= '<li><span class="fw-bold t-color-3">'.$d['name'].':</span> '.$d['option'].'</li>';
			}
			$description = $name.'<ul>'.$textDetail.'</ul>';
		}
	}
	$row = array(
		"reference"=>$data['reference'],
		"description"=>$description,
		"price"=>formatNum($data['price'],false),
		"qty"=>$data['quantity'],
		"subtotal"=>formatNum($subtotalProduct,false)
	);
	$html.='<td class="text-start w10">'.$data['reference'].'</td>';
	$html.='<td class="text-start w55">'.$description.'</td>';
	$html.='
		<td class="text-right w10">'.formatNum($data['price'],false).'</td>
		<td class="text-right w10">'.$data['quantity'].'</td>
		<td class="text-right w15">'.formatNum($subtotalProduct,false).'</td>
	';
	array_push($arrRows,$row);
}
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
						<h4><strong><?= $company['name'] ?></strong></h4>
						<p>
							<?= $company['addressfull']?> <br>
							Teléfono: <?=$company['phone']." ".$company['phones'] ?> <br>
							Email: <?= $company['email'] ?>
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
				<?php foreach ($arrRows as $row) { ?>
				<tr>
					<td class="text-start w10"><?=$row['reference']?></td>
					<td class="text-start w55"><?=$row['description']?></td>
					<td class="text-right w10"><?=$row['price']?></td>
					<td class="text-start w10"><?=$row['qty']?></td>
					<td class="text-start w15"><?=$row['subtotal']?></td>
				</tr>
				<?php } ?>
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
			<p>Recuerda que puedes ver tu pedido en tu perfil de usuario</p>	
		</div>
	</div>									
</body>
</html>