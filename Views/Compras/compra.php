<?php 
headerAdmin($data);
//dep($data['data']);exit;
$purchase = $data['data'];
$products = json_decode($purchase["products"],true);
$total = 0;
$company = $data['company'];
//dep($products);exit;
?>

<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div id="modalItem"></div>
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <div id="orderInfo" class="position-relative overflow-hidden">
                    <div class="d-flex justify-content-between flex-wrap mb-3">
                        <div class="mb-3 d-flex flex-wrap align-items-center">
                            <img src="<?=media()."/images/uploads/".$company['logo']?>" class="me-2" style="width=170px;height:170px;" alt="">
                            <div>
                                <p class="m-0 fw-bold"><?=$company['name']?></p>
                                <p class="m-0">Oswaldo Parrado Clavijo</p>
                                <p class="m-0">NIT 17.344.806-8 No responsable de IVA</p>
                                <p class="m-0"><?=$company['addressfull']?></p>
                                <p class="m-0">+<?=$company['phonecode']." ".$company['phone']?></p>
                                <p class="m-0"><?=$company['email']?></p>
                                <p class="m-0"><?=BASE_URL?></p>
                            </div>
                        </div>
                        <div class="text-start">
                            <p class="m-0"><span class="fw-bold">Fecha: </span><?=$purchase['date']?></p>
                            <p class="m-0"><span class="fw-bold">Compra: </span>#<?=$purchase['idpurchase']?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 mb-3">
                            <p class="m-0 fw-bold">Proveedor</p>
                            <p class="m-0">Nombre: <?=$purchase['name']?></p>
                            <p class="m-0">NIT: <?=$purchase['nit']?></p>
                            <p class="m-0">Dirección: <?=$purchase['address']?></p>
                            <p class="m-0">Teléfono: <?=$purchase['phone']?></p>
                            <p class="m-0">Correo: <?=$purchase['email']?></p>
                        </div>
                    </div>
                    <table class="table items align-middle">
                        <thead>
                            <tr>
                                <th>Descripcion</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
                            if(count($products) > 0){
                                foreach ($products as $product) {
                                    $total+= $product['qty']*$product['price'];
                                    $totalPrice = $product['qty']*$product['price'];
                        ?>
                        <tr>
                            <td class="text-start text-break">
                                <?=$product['name']?>
                            </td>
                            <td data-label="Precio: " class="text-break">
                                <?=formatNum($product['price'],false)?>
                            </td>
                            <td data-label="Cantidad: " class="text-break">
                                <?=$product['qty']?>
                            </td>
                            <td data-label="Total" class="text-break">
                                <?=formatNum($totalPrice,false)?>
                            </td>
                        </tr>   
                        <?php } }?>        
                    </tbody>
                    <tfoot class="tdeskfoot">
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <td class="text-start"><?= formatNum($total,false)?></td>
                        </tr>
                    </tfoot>
                    </table>
                    <div class="row tmobilefoot mb-4">
                        <div class="col-12 mt-3 d-flex justify-content-between">
                            <div class="fw-bold">Total:</div>
                            <div><?= formatNum($total,false)?></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 text-start">
                        <a href="<?=base_url()?>/compras/compras" class="btn btn-secondary text-white"><i class="fas fa-arrow-circle-left"></i> Regresar</a>
                    </div>
                    <div class="col-6 text-end">
                        <button type="button" onclick="printDiv('orderInfo','<?=$purchase['idpurchase'].$purchase['name']?>')" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        