<?php
    headerPage($data);
    //dep($_SESSION['arrCart']);
    $qtyCart = 0;
    $total = 0;
    $subtotal = 0;
    $arrShipping = $data['shipping'];
    $urlCupon="";
    $situ = isset($_GET['situ']) ? $_GET['situ'] : "";
    $urlSitu = $situ !="" ? "?situ=".$situ : "";
    
    if(isset($_GET['cupon'])){
        if($situ !=""){
            $urlCupon = "?cupon=".$_GET['cupon']."&situ=".$situ;
        }else{
            $urlCupon = "?cupon=".$_GET['cupon'];
        }
    }else{
        $urlCupon = $urlSitu;
    }
?>
    <main id="cart">
        <div class="container">
            <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Carrito</li>
                </ol>
            </nav>
            <?php if(isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){ 
                    $arrProducts = $_SESSION['arrCart'];
                    $cupon = 0;
                    for ($i=0; $i < count($arrProducts) ; $i++) { 
                        if($arrProducts[$i]['topic'] == 2){
                            if($arrProducts[$i]['producttype'] == 2){
                                $subtotal+=$arrProducts[$i]['qty']*$arrProducts[$i]['variant']['price'];
                            }else{
                                $subtotal+=$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                            }
                        }else{
                            $subtotal += $arrProducts[$i]['price']*$arrProducts[$i]['qty']; 
                        }
                    }
                    if(isset($data['cupon'])){
                        $cupon = $subtotal-($subtotal*($data['cupon']['discount']/100));
                        $total = $cupon;
                    }else{
                        $total = $subtotal;
                    }
                    if($arrShipping['id'] != 3){
                        $total+=$arrShipping['value'];
                    }
            ?>
            <div class="row mb-5">
                <div class="col-lg-8 mt-5">
                    <table class="table table-borderless table-cart">
                        <thead class="position-relative af-b-line">
                          <tr>
                            <th scope="col">Producto</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Subtotal</th>
                          </tr>
                        </thead>
                        <tbody>
                            <?php 
                                for ($i=0; $i <count($arrProducts) ; $i++) { 
                                    $variant = "";
                                    $price =0;
                                    if($arrProducts[$i]['topic'] == 2 && $arrProducts[$i]['producttype'] == 2){

                                        $price = $arrProducts[$i]['variant']['price'];
                                    }else{
                                        $price = $arrProducts[$i]['price'];
                                    }
                                    $totalPerProduct=0;
                                    $totalPerProduct = formatNum($price*$arrProducts[$i]['qty'],false);
                                    if($arrProducts[$i]['topic'] == 1){
                                        $img = $arrProducts[$i]['img'];
                                    }elseif ($arrProducts[$i]['topic'] == 1 && $arrProducts[$i]['photo']!="") {
                                        $img = media()."/images/uploads/".$arrProducts[$i]['photo'];
                                    }else{
                                        $img = $arrProducts[$i]['image'];
                                    }
                            ?>     
                            <?php if($arrProducts[$i]['topic'] == 1){?>
                            <tr data-id="<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>" data-h="<?=$arrProducts[$i]['height']?>"
                        data-w="<?=$arrProducts[$i]['width']?>" data-m="<?=$arrProducts[$i]['margin']?>" data-s="<?=$arrProducts[$i]['style']?>" 
                        data-mc="<?=$arrProducts[$i]['colormargin']?>" data-bc="<?=$arrProducts[$i]['colorborder']?>" data-t="<?=$arrProducts[$i]['idType']?>" 
                        data-r="<?=$arrProducts[$i]['reference']?>" data-f="<?=$arrProducts[$i]['photo']?>" data-material="<?=$arrProducts[$i]['material']?>"
                        data-fc="<?=$arrProducts[$i]['colorframe']?>" data-glass="<?=$arrProducts[$i]['glass']?>">
                            <?php }else if($arrProducts[$i]['topic'] == 2){?>
                                <?php if($arrProducts[$i]['producttype'] == 2){
                                    $variant = '<ul><li><span class="fw-bold t-color-3">Tamaño:</span> '.$arrProducts[$i]['variant']['width']."x".$arrProducts[$i]['variant']['height'].'cm</li></ul>'
                                ?>
                                    <tr data-id = "<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>" data-variant ="<?=$arrProducts[$i]['variant']['id_product_variant']?>">
                                <?php }else{?>
                                    <tr data-id = "<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>">
                                <?php }?>
                            <?php }?>
                            <td>
                                <div class="position-relative">
                                    <img src="<?=$img?>"  class="p-2" height="100px" width="100px" alt="<?=$arrProducts[$i]['name']?>">
                                    <div class="c-p position-absolute btn-del-cart bg-color-2 rounded-circle ps-2 pe-2 top-0 start-0"><i class="fas fa-times"></i></div>
                                </div>
                            </td>
                            <td>
                                <a class="w-100"href="<?=$arrProducts[$i]['url']?>"><?=$arrProducts[$i]['reference']?> <?=$arrProducts[$i]['name']?></a>
                                <?=$variant?>
                                <?php
                                    if($arrProducts[$i]['topic'] == 1){
                                        $borderStyle = $arrProducts[$i]['style'] == "Flotante" ? "marco interno" : "bocel";
                                        $marginStyle = $arrProducts[$i]['style'] == "Flotante" ? "fondo" : "paspartú";
                                        $margen = $arrProducts[$i]['margin'] > 0 ? '<li><span class="fw-bold t-color-3">Medida '.$marginStyle.':</span> '.$arrProducts[$i]['margin'].'cm</li>' : "";
                                        $colorMargen = $arrProducts[$i]['colormargin'] != "" ? '<li><span class="fw-bold t-color-3">Color del '.$marginStyle.':</span> '.$arrProducts[$i]['colormargin'].'</li>' : "";
                                        $colorBorder = $arrProducts[$i]['colorborder'] != "" ? '<li><span class="fw-bold t-color-3">Color del '.$borderStyle.':</span> '.$arrProducts[$i]['colorborder'].'</li>' : "";
                                        $colorFrame = $arrProducts[$i]['colorframe'] != "" ? '<li><span class="fw-bold t-color-3">Color del marco:</span> '.$arrProducts[$i]['colorframe'].'</li>' : "";
                                        $medidas = $arrProducts[$i]['width']."cm X ".$arrProducts[$i]['height']."cm";
                                        $medidasMarco = ($arrProducts[$i]['width']+($arrProducts[$i]['margin']*2))."cm X ".($arrProducts[$i]['height']+($arrProducts[$i]['margin']*2))."cm"; 

                                ?>
                                <?php if($arrProducts[$i]['idType'] == 1 || $arrProducts[$i]['idType'] == 3){?>
                                <ul>
                                    <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts[$i]['reference']?></li>
                                    <?=$colorFrame?>
                                    <li><span class="fw-bold t-color-3">Material del marco:</span> <?=$arrProducts[$i]['material']?></li>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts[$i]['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Estilo de enmarcación:</span> <?=$arrProducts[$i]['style']?></li>
                                    <?=$margen?>
                                    <li><span class="fw-bold t-color-3">Medida imagen:</span> <?=$medidas?></li>
                                    <li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
                                    <?=$colorMargen?>
                                    <?=$colorBorder?>
                                    <li><span class="fw-bold t-color-3">Tipo de vidrio:</span> <?=$arrProducts[$i]['glass']?></li>
                                </ul>
                                <?php }else if($arrProducts[$i]['idType'] == 5){?>
                                <ul>
                                <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts[$i]['reference']?></li>
                                    <?=$colorFrame?>
                                    <li><span class="fw-bold t-color-3">Material del marco:</span> <?=$arrProducts[$i]['material']?></li>
                                    <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts[$i]['orientation']?></li>
                                    <li><span class="fw-bold t-color-3">Tipo de espejo:</span> <?=$arrProducts[$i]['style']?></li>
                                    <li><span class="fw-bold t-color-3">Medida Marco:</span> <?=$medidasMarco?></li>
                                    
                                </ul>
                                <?php }else if($arrProducts[$i]['idType'] == 6){?>
                                    <ul>
                                        <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts[$i]['reference']?></li>
                                        <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts[$i]['orientation']?></li>
                                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                        <?=$margen?>
                                        <li><span class="fw-bold t-color-3">Medidas del marco:</span> <?=$medidasMarco?></li>
                                        <?=$colorMargen?>
                                    </ul>
                                <?php }else if($arrProducts[$i]['idType'] == 8){?>
                                    <ul>
                                        
                                        <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts[$i]['style']?></li>
                                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                        <?=$colorBorder?>
                                    </ul>
                                <?php }else if($arrProducts[$i]['idType'] == 9){?>
                                   <ul>
                                        <li><span class="fw-bold t-color-3">Referencia:</span> <?=$arrProducts[$i]['reference']?></li>
                                        <li><span class="fw-bold t-color-3">Orientación:</span> <?=$arrProducts[$i]['orientation']?></li>
                                        <li><span class="fw-bold t-color-3">Estilo:</span> <?=$arrProducts[$i]['style']?></li>
                                        <li><span class="fw-bold t-color-3">Medidas:</span> <?=$medidas?></li>
                                   </ul>
                                <?php }?>
                                <?php }?>
                            </td>
                            <td><?=formatNum($price,false)?></td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center flex-wrap">
                                    <div class="btn-qty-1">
                                        <button class="btn cartDecrement" onclick="cartDecrement(this)"><i class="fas fa-minus"></i></button>
                                        <input type="number" name="txtQty" class="inputCart" oninput="cartInput(this)" min="1" value ="<?=$arrProducts[$i]['qty']?>">
                                        <button class="btn cartIncrement" onclick="cartIncrement(this)"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </td>
                            <td class="totalPerProduct"><?=$totalPerProduct?></td>
                          </tr>
                          <?php }?>
                        </tbody>
                    </table>
                    <?php
                    if(!isset($data['cupon'])){
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <form id="formCoupon">
                                <div class="input-group">
                                    <input type="text" id="txtCoupon" name="cupon" class="form-control" placeholder="Código de descuento" aria-label="Coupon code" aria-describedby="button-addon2">
                                    <button type="submit" class="btn btn-bg-1" type="button" id="btnCoupon">+</button>
                                </div>
                                <div class="alert alert-danger mt-3 d-none" id="alertCoupon" role="alert"></div>
                            </form>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <div class="col-lg-4 mt-5 ">
                    <h3 class="t-p">RESUMEN</h3>
                    <div class="mb-3 position-relative pb-1 af-b-line">
                        <div class="d-flex justify-content-between mb-3">
                            <p class="m-0 fw-bold">Subtotal:</p>
                            <p class="m-0" id="subtotal"><?=formatNum($subtotal)?></p>
                        </div>
                        <?php 
                        if(isset($data['cupon'])){
                          
                        ?>
                        <p class="m-0 fw-bold">Cupón:</p>
                        <div class="d-flex justify-content-between">
                            <p class="m-0"><?=$data['cupon']['code']?>:</p>
                            <p class="m-0"><?=$data['cupon']['discount']?>%</p>
                        </div>
                        <a href="<?=base_url()."/carrito".$urlSitu?>" class="mb-3">Remover cupón</a>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="m-0 fw-bold">Subtotal:</p>
                            <p class="m-0" id="cuponTotal"><?=formatNum($cupon)?></p>
                        </div>
                        <?php }?>
                        <?php if((isset($_GET['situ']) && $_GET['situ']=="false") || !isset($_GET['situ'])){?>
                            <?php if($arrShipping['id']!= 3){?>
                            <div class="d-flex justify-content-between mb-3">
                                <p class="m-0 fw-bold">Envio:</p>
                                <p class="m-0"><?=formatNum($arrShipping['value'])?></p>
                            </div>
                            <?php }else{?>
                                <p class="m-0 fw-bold">Envio:</p>
                                <select class="form-select" aria-label="Default select example" id="selectCity" name="selectCity">
                                    <option value ="0" selected>Seleccionar ciudad</option>
                                    <?php for ($i=0; $i < count($arrShipping['cities']); $i++) { ?>
                                    <option value="<?=$arrShipping['cities'][$i]['id']?>"><?=$arrShipping['cities'][$i]['city']." - ".formatNum($arrShipping['cities'][$i]['value'],false)?></option>
                                    <?php }?>
                                </select>
                            <?php }?>
                        <?php }else{?>
                            <div class="d-flex justify-content-between mb-3">
                                <p class="m-0 fw-bold">Envio:</p>
                                <p class="m-0"><?=formatNum(0)?></p>
                            </div>
                        <?php }?>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="" id="boolCheck" name="boolCheck">
                            <label class="form-check-label" for="boolCheck">
                                Recoger en local
                            </label>
                        </div>
                        <div class="d-flex justify-content-between mb-3 mt-3">
                            <p class="m-0 fw-bold">Total</p>
                            <p class="m-0 fw-bold" id="totalProducts"><?=formatNum($total)?></p>
                        </div>
                    </div>
                    <!--<div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Coupon code" aria-label="Coupon code" aria-describedby="button-addon2">
                        <button class="btn btnc-primary" type="button" id="button-addon2">+</button>
                    </div>-->
                    <?php if(isset($_SESSION['login'])){ 
                    ?>
                    <a href="<?=base_url()."/pago".$urlCupon?>" class="mb-3 w-100 btn btn-bg-1">Pagar</a>
                    <?php }else{ ?>
                    <button type="button" onclick="openLoginModal();" class="mb-3 w-100 btn btn-bg-1">Pagar</button>
                    <?php }?>
                    <a href="<?=base_url()?>/tienda" class="w-100 btn btn-bg-2">Continuar comprando</a>
                </div>
            </div>
            <?php }else {?>
                <div class="d-flex justify-content-center align-items-center p-5 text-center">
                    <div>
                        <p>No hay productos en el carrito.</p>
                        <a href="<?=base_url()?>/tienda" class="btn btn-bg-1">Ver tienda</a>
                        <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-1">Enmarcar</a>
                    </div>
                </div>
            <?php }?>
        </div>
    </main>
<?php
    footerPage($data);
?>