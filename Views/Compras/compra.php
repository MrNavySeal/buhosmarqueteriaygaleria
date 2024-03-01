<?php 
headerPage($data);
//dep($data['data']);exit;
$purchase = $data['data'];
$products = json_decode($purchase["products"],true);
$total = 0;
$discount=0;
$subtotal=0;
$iva=0;
$company = $data['company'];
//dep($products);exit;
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
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <tbody>
                                <tr class="fw-bold">
                                    <td>Referencia</td>
                                    <td>Descripcion</td>
                                    <td>Cantidad</td>
                                    <td>Precio</td>
                                    <td>IVA</td>
                                    <td>Precio IVA</td>
                                    <td>Subtotal</td>
                                </tr>
                                <?php 
                                    if(count($products) > 0){
                                        foreach ($products as $product) {
                                            $total+= $product['total'];
                                            $subtotal += $product['subtotal'];
                                            $discount += $product['discount'];
                                            $iva += $product['iva'];
                                ?>
                                <tr>
                                    <td>
                                        <?=$product['reference']?>
                                    </td>
                                    <td>
                                        <?=$product['name']?>
                                    </td>
                                    <td>
                                        <?=$product['qty']?>
                                    </td>
                                    <td>
                                        <?=formatNum($product['cost'])?>
                                    </td>
                                    <td>
                                        <?=$product['ivatext']?>
                                    </td>
                                    <td>
                                        <?=formatNum($product['iva'])?>
                                    </td>
                                    <td>
                                        <?=formatNum($product['subtotal'],false)?>
                                    </td>
                                </tr>   
                                <?php } }?>        
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-end">Subtotal:</th>
                                    <td class="text-start" id="txtSubtotal"><?=formatNum($subtotal)?></td>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Descuento:</th>
                                    <td class="text-start" id="txtDiscount"><?=formatNum($discount)?></td>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">IVA:</th>
                                    <td class="text-start" id="txtIva"><?=formatNum($iva)?></td>
                                </tr>
                                <tr>
                                    <th colspan="6" class="text-end">Total:</th>
                                    <td class="text-start" id="txtTotal"><?=formatNum($total)?></td>
                                </tr>
                            </tfoot>
                        </table>
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
</main>
<?php footerPage($data)?>        