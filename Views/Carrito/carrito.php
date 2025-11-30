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
    $shipping = 0;
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
        <nav class="m-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                <li class="breadcrumb-item active" aria-current="page">Carrito</li>
            </ol>
        </nav>
        <div class="container">
            <?php if(isset($_SESSION['arrCart']) && !empty($_SESSION['arrCart'])){ 
                    $arrProducts = $_SESSION['arrCart'];
                    
                    $cupon = 0;
                    for ($i=0; $i < count($arrProducts) ; $i++) { 
                        $subtotal+=$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                    }
                    if(isset($data['cupon'])){
                        $cupon = $subtotal-($subtotal*($data['cupon']['discount']/100));
                        $total = $cupon;
                    }else{
                        $total = $subtotal;
                    }
                    if($arrShipping['id'] != 3){
                        $shipping+=$arrShipping['value'];
                    }
                    if($situ=="true"){
                        $shipping = 0;
                    }
                    $total+=$shipping;
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
                                    $price = $arrProducts[$i]['price'];
                                    $totalPerProduct=0;
                                    $totalPerProduct = formatNum($price*$arrProducts[$i]['qty'],false);
                                    if($arrProducts[$i]['topic'] == 1){
                                        $img = $arrProducts[$i]['img'] != "" ? $arrProducts[$i]['img'] : $img = media()."/images/uploads/".$arrProducts[$i]['cat_img'];
                                    }elseif ($arrProducts[$i]['topic'] == 1 && $arrProducts[$i]['photo']!="") {
                                        $img = media()."/images/uploads/".$arrProducts[$i]['photo'];
                                    }else{
                                        $img = $arrProducts[$i]['image'];
                                    }
                            ?>  
                            <tr data-id = "<?=$i?>" class="row-product-cart">   
                            <?php if($arrProducts[$i]['topic'] == 1){?>
                            <?php }else if($arrProducts[$i]['topic'] == 2){?>
                                <?php if($arrProducts[$i]['producttype']){
                                    $arrVariant = explode("-",$arrProducts[$i]['variant']['name']); 
                                    $props = $arrProducts[$i]['props'];
                                    $propsTotal = count($props);
                                    $htmlComb="";
                                    
                                    for ($j=0; $j < $propsTotal; $j++) { 
                                        $options = $props[$j]['options'];
                                        $optionsTotal = count($options);
                                        for ($k=0; $k < $optionsTotal ; $k++) { 
                                            if($options[$k]== $arrVariant[$j]){
                                                $htmlComb.='<li><span class="fw-bold t-color-3" >'.$props[$j]['name'].': '.$arrVariant[$j].'</span></li>';
                                                break;
                                            }
                                        }
                                    }
                                    $variant = '<ul>'.$htmlComb.'</ul>';
                                ?>
                                <?php }?>
                            <?php }?>
                            <td>
                                <div class="position-relative">
                                    <img src="<?=$img?>"  class="p-2" height="100px" width="100px" alt="<?=$arrProducts[$i]['name']?>">
                                    <div class="c-p position-absolute btn-del-cart bg-color-2 rounded-circle ps-2 pe-2 top-0 start-0"><i class="fas fa-times"></i></div>
                                </div>
                            </td>
                            <td>
                                <a class="w-100" href="<?=$arrProducts[$i]['url']?>"><?=$arrProducts[$i]['name']?></a>
                                <?=$variant?>
                                <?php
                                    if($arrProducts[$i]['topic'] == 1){
                                        $arrSpecs = $arrProducts[$i]['specs'];
                                ?>
                                <ul>
                                    <?php foreach ($arrSpecs as $e) {?>
                                    <li><span class="fw-bold t-color-3"><?=$e['name']?></span> <?=$e['value']?></li>
                                    <?php }?>
                                </ul>
                                <?php }?>
                            </td>
                            <td><?=formatNum($price,false)?></td>
                            <td>
                                <div class="d-flex justify-content-center align-items-center flex-wrap">
                                    <div class="btn-qty-1">
                                        <button class="btn cartDecrement" onclick="cartDecrement(this)"><i class="fas fa-minus"></i></button>
                                        <input type="number" name="txtQty" class="inputCart" onchange="cartInput(this)" min="1" value ="<?=$arrProducts[$i]['qty']?>">
                                        <button class="btn cartIncrement" onclick="cartIncrement(this)"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </td>
                            <td class="totalPerProduct"><?=$totalPerProduct?></td>
                          </tr>
                          <?php }?>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-4 mt-5 ">
                    <h3 class="t-p">RESUMEN</h3>
                    <div class="mb-3 position-relative pb-1 af-b-line">
                        <div class="d-flex justify-content-between mb-3 mt-3">
                            <p class="m-0 fw-bold">Total</p>
                            <p class="m-0 fw-bold" id="totalProducts"><?=formatNum($total)?></p>
                        </div>
                    </div>
                    <!--<div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Coupon code" aria-label="Coupon code" aria-describedby="button-addon2">
                        <button class="btn btnc-primary" type="button" id="button-addon2">+</button>
                    </div>-->

                    <button type="button" onclick="modalCheckout(this)" class="mb-3 w-100 btn btn-bg-1 btnModalCheckout">Pagar</button>
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