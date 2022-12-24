<?php
    headerPage($data);
    $total = 0;
    require_once("Libraries/vendor/autoload.php");
    MercadoPago\SDK::setAccessToken($data['credentials']['secret']);

    $preference = new MercadoPago\Preference();
    $item = new MercadoPago\Item();
    $arrProducts = $_SESSION['arrCart'];
    $arrShipping = $data['shipping'];
    $total = 0;
    $cupon = 0;
    $subtotal = 0;
    $envio = 0;
    $urlCupon="";
    $situ = isset($_GET['situ']) ? "?situ=".$_GET['situ'] : "";

    for ($i=0; $i < count($arrProducts) ; $i++) { 
        $subtotal += $arrProducts[$i]['qty'] * $arrProducts[$i]['price'];
    }

    if(isset($data['cupon']) && !$data['cupon']['check']){
        $cupon = $subtotal-($subtotal*($data['cupon']['discount']/100));
        $total = $cupon;
    }else{
        $total = $subtotal;
    }
    //dep($situ);
    if($arrShipping['id'] < 3 || $arrShipping['id'] == 4){
        $envio = $arrShipping['value'];
        $total+=$envio;
    }else if($arrShipping['id'] == 3 && isset($_SESSION['shippingcity'])){
        $envio = $_SESSION['shippingcity'];
        $total+= $envio;
    }
    $item->title = "productos";
    $item->quantity = 1;
    $item->unit_price = floor($total);
    $item->currency_id=$data['company']['currency']['code'];
    $preference->items = array($item);
    $preference->back_urls = array(
        "success" => base_url()."/pago/confirmar",
        "failure" => base_url()."/pago/error"
    );
    $preference->auto_return = "approved";
    $preference->binary_mode = true;
    $preference->save();


?>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<main id="<?=$data['page_name']?>">
    <div class="container">
        <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
                <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/carrito">Carrito</a></li>
                <li class="breadcrumb-item active" aria-current="page">Pago</li>
            </ol>
        </nav>
        <div class="row">
           <div class="col-lg-7 order-lg-1 order-md-5 order-sm-5">
                <form id="formOrder" name="formOrder" class="p-4">
                    <h2>Detalles de facturación</h2>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtNameOrder" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtNameOrder" name="txtNameOrder" value="<?=$_SESSION['userData']['firstname']?>" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtLastNameOrder" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtLastNameOrder" name="txtLastNameOrder" value="<?=$_SESSION['userData']['lastname']?>" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtEmailOrder" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="txtEmailOrder" name="txtEmailOrder" value="<?=$_SESSION['userData']['email']?>" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPhoneOrder" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="txtPhoneOrder" name="txtPhoneOrder" required placeholder="312 345 6789">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtAddressOrder" class="form-label"> Dirección<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtAddressOrder" name="txtAddressOrder" required="" placeholder="Carrera, calle, barrio...">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="listCountry" class="form-label">País <span class="text-danger">*</span></label>
                                <select class="form-select" id="listCountry" name="listCountry" aria-label="Default select example" required="">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="listState" class="form-label">Departamento <span class="text-danger">*</span></label>
                                <select class="form-select" id="listState" name="listState" aria-label="Default select example" required="">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="listCity" class="form-label">Ciudad <span class="text-danger">*</span></label>
                                <select class="form-select" id="listCity" name="listCity" aria-label="Default select example" required="">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPostCodeOrder" class="form-label"> Código postal</label>
                                <input type="text" class="form-control" id="txtPostCodeOrder" name="txtPostCodeOrder" placeholder="500001">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtNote" class="form-label">Notas</label>
                        <textarea class="form-control" id="txtNote" name="txtNote" rows="5" placeholder="Es posible que..."></textarea>
                    </div>
                </form>
            </div>
            <div class="col-lg-5 order-lg-5 order-md-5 order-sm-1 mb-4">
                <div class="p-4 mb-4">
                    <h2>Resumen</h2>
                    <?php 
                        for ($i=0; $i < count($arrProducts) ; $i++) { 
                            $price=$arrProducts[$i]['qty']*$arrProducts[$i]['price'];
                    ?>
                    <div class="d-flex justify-content-between">
                        <p><?=$arrProducts[$i]['name']." x ".$arrProducts[$i]['qty']?></p>
                        <p><?=formatNum($price,false)?></p>
                    </div>
                    <?php }?>
                    <div class="d-flex justify-content-between mb-3">
                        <p class="m-0 fw-bold">Subtotal:</p>
                        <p class="m-0" id="subtotal"><?=formatNum($subtotal)?></p>
                    </div>
                    <?php if(isset($data['cupon']) && !$data['cupon']['check']){?>
                        <p class="m-0 fw-bold">Cupón:</p>
                        <div class="d-flex justify-content-between">
                            <p class="m-0"><?=$data['cupon']['code']?>:</p>
                            <p class="m-0"><?=$data['cupon']['discount']?>%</p>
                        </div>
                        <a href="<?=base_url()."/pago".$situ?>" class="mb-3">Remover cupón</a>
                        <div class="d-flex justify-content-between mb-3">
                            <p class="m-0 fw-bold">Subtotal:</p>
                            <p class="m-0" id="cuponTotal"><?=formatNum($cupon)?></p>
                        </div>
                    <?php }else if(isset($data['cupon']) && $data['cupon']['check']){?>
                    <form id="formCoupon" class="mb-3">
                        <div class="input-group">
                            <input type="text" id="txtCoupon" name="cupon" class="form-control" placeholder="Código de descuento" aria-label="Coupon code" aria-describedby="button-addon2">
                            <button type="button" class="btn btn-bg-1" type="button" id="btnCoupon">+</button>
                        </div>
                        <div class="alert alert-danger mt-3 d-none" id="alertCoupon" role="alert"></div>
                    </form>
                    <div id="alertCheckData" class="alert alert-danger" role="alert">
                        El cupón <?=$data['cupon']['code']?> ya fue usado anteriormente, ingresa otro.
                    </div>
                    <?php }else{?>
                    <form id="formCoupon" class="mb-3">
                        <div class="input-group">
                            <input type="text" id="txtCoupon" name="cupon" class="form-control" placeholder="Código de descuento" aria-label="Coupon code" aria-describedby="button-addon2">
                            <button type="button" class="btn btn-bg-1" type="button" id="btnCoupon">+</button>
                        </div>
                        <div class="alert alert-danger mt-3 d-none" id="alertCoupon" role="alert"></div>
                    </form>
                    <?php }?>
                    <div class="d-flex justify-content-between mb-3 position-relative af-b-line">
                        <p class="m-0 fw-bold">Envio</p>
                        <p class="m-0 fw-bold"><?=formatNum($envio)?></p>
                    </div>
                    <div class="d-flex justify-content-between mb-3 position-relative af-b-line">
                        <p class="m-0 fw-bold fs-5">Total</p>
                        <p class="m-0 fw-bold fs-5" id="totalResume"><?=formatNum($total)?></p>
                    </div>
                    <button type="button" class="mb-3 w-100 btn btn-bg-1" id="btnOrder" red="<?=$preference->init_point; ?>">Pagar</button>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
const mp = new MercadoPago('<?=$data['credentials']['client']?>', {
    locale: 'en-US'
})
const checkout = mp.checkout({
    preference: {
        id: '<?php echo $preference->id; ?>'
    }
});
    </script>
<?php
    footerPage($data);
?>