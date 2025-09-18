<?php 
    $arrShipping = getShippingMode();
?>
<div class="modal fade" id="modalPago">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table align-middle text-break">
                    <tbody id="listItem">
                        
                        <form id="formCheckout">
                            <div class="row">
                                <div class="col-md-8">
                                    <h2>Detalles de facturación</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckName" class="form-label">Nombres <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="strCheckName" name="strCheckName" value="<?=$_SESSION['userData']['firstname']?>" required>
                                                <ul class="strCheckName text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckLastname" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="strCheckLastname" name="strCheckLastname" value="<?=$_SESSION['userData']['lastname']?>" required>
                                                <ul class="strCheckLastname text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckEmail" class="form-label">Correo <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="strCheckEmail" name="strCheckEmail" value="<?=$_SESSION['userData']['email']?>" required="">
                                                <ul class="strCheckEmail text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="strCheckPhone" name="strCheckPhone" value="<?=$_SESSION['userData']['phone']?>" required placeholder="312 345 6789">
                                                <ul class="strCheckPhone text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckPersonType" class="form-label">Tipo de persona <span class="text-danger">*</span></label>
                                                <select class="form-control" aria-label="Default select example" id="strCheckPersonType" name="strCheckPersonType" required>
                                                    <option value="individual">Natural</option>
                                                    <option value="association">Jurídica</option>
                                                </select>
                                                <ul class="strCheckPersonType text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckDocumentType" class="form-label">Tipo y número de documento <span class="text-danger">*</span></label>
                                                <div class="d-flex">
                                                    <select class="form-control" aria-label="Default select example" id="strCheckDocumentType" name="strCheckDocumentType" required>
                                                        <option value="CC">Cédula de ciudadania</option>
                                                        <option value="TE">Tarjeta de extranjeria</option>
                                                        <option value="CE">Cédula de extranjeria</option>
                                                        <option value="PAS">Pasaporte</option>
                                                        <option value="NIT">NIT</option>
                                                        <option value="DI">Documento de identidad</option>
                                                        <option value="TI">Tarjeta de identidad</option>
                                                        <option value="RC">Registro civil de nacimiento</option>
                                                    </select>
                                                    <input type="text" class="form-control" id="strCheckDocument" name="strCheckDocument" value="<?=$_SESSION['userData']['identification']?>" required>
                                                </div>
                                                <ul class="strCheckDocumentType text-danger"></ul>
                                                <ul class="strCheckDocument text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="strCheckBank" class="form-label">Banco <span class="text-danger">*</span></label>
                                                <select class="form-control" aria-label="Default select example" id="strCheckBank" name="strCheckBank" required>
                                                </select>
                                                <ul class="strCheckBank text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listCountry" class="form-label">País <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listCountry" name="listCountry" aria-label="Default select example" data-country="<?=$_SESSION['userData']['countryid']?>" required onchange="getSelectCountry()">
                                                </select>
                                                <ul class="listCountry text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listState" class="form-label">Departamento <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listState" name="listState" aria-label="Default select example"  required onchange="getSelectState()">
                                                </select>
                                                <ul class="listState text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listCity" class="form-label">Ciudad <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listCity" name="listCity" aria-label="Default select example" required="">
                                                </select>
                                                <ul class="listCity text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckAddress" class="form-label"> Dirección<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="strCheckAddress" value="<?=$_SESSION['userData']['address']?>" name="strCheckAddress" required="" placeholder="Carrera, calle, barrio...">
                                                <ul class="strCheckAddress text-danger"></ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckCode" class="form-label"> Código postal</label>
                                                <input type="text" class="form-control" id="strCheckCode" name="strCheckCode" placeholder="50001">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h2>Resumen</h2>
                                    <div id="checkoutResume"></div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <p class="m-0 fw-bold">Subtotal:</p>
                                        <p class="m-0" id="checkSubtotal"></p>
                                    </div>
                                    <div id="contentCoupon" class="mb-3">
                                        <div class="input-group">
                                            <input type="text" id="coupon" name="cupon" class="form-control" placeholder="Código de descuento" aria-label="Coupon code" aria-describedby="button-addon2">
                                            <button type="button" class="btn btn-bg-1" type="button" onclick="setCoupon(this)">+</button>
                                        </div>
                                    </div>
                                    <div id="divCoupon" class="d-none">
                                        <p class="m-0 fw-bold">Cupón:</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="m-0" id="htmlCoupon"></p>
                                            <p class="m-0" id="htmlDiscount" ></p>
                                        </div>
                                        <p><a href="#" class="pe-auto mb-3" onclick="delCoupon()">Remover cupón</a></p>
                                    </div>
                                    <?php if($arrShipping['id']!= 3){?>
                                    <div class="d-flex justify-content-between mb-3">
                                        <p class="m-0 fw-bold">Envio <?= $arrShipping['id'] == 4 ? "contra entrega": ""?>:</p>
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
                                    <div class="d-flex justify-content-between mt-3 mb-3 position-relative af-b-line">
                                        <p class="m-0 fw-bold fs-5">Total</p>
                                        <p class="m-0 fw-bold fs-5" id="checkTotal"></p>
                                    </div>
                                </div>
                                
                            </div>
                            <p>Al realizar una compra en nuestro sitio web, aceptas <a href="<?=base_url()?>/politicas/terminos" target="_blank">nuestras políticas de uso</a> y 
                            <a href="<?=base_url()?>/politicas/privacidad" target="_blank">de privacidad</a>.</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-bg-1" id="btnCheckout" >Pagar</button>
                                <button type="button" class="btn btn-bg-2 text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>