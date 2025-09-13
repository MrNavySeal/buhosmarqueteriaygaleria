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
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckLastname" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="strCheckLastname" name="strCheckLastname" value="<?=$_SESSION['userData']['lastname']?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckEmail" class="form-label">Correo <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control" id="strCheckEmail" name="strCheckEmail" value="<?=$_SESSION['userData']['email']?>" required="">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strCheckPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control" id="strCheckPhone" name="strCheckPhone" value="<?=$_SESSION['userData']['phone']?>" required placeholder="312 345 6789">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="strPersonType" class="form-label">Tipo de persona <span class="text-danger">*</span></label>
                                                <select class="form-control" aria-label="Default select example" id="strCheckPersonType" name="strCheckPersonType" required>
                                                    <option value="individual">Natural</option>
                                                    <option value="association">Jurídica</option>
                                                </select>
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
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="strCheckBank" class="form-label">Banco <span class="text-danger">*</span></label>
                                                <select class="form-control" aria-label="Default select example" id="strCheckBank" name="strCheckBank" required>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listCountry" class="form-label">País <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listCountry" name="listCountry" aria-label="Default select example" data-country="<?=$_SESSION['userData']['countryid']?>" required onchange="getSelectCountry()">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listState" class="form-label">Departamento <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listState" name="listState" aria-label="Default select example"  required onchange="getSelectState()">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="listCity" class="form-label">Ciudad <span class="text-danger">*</span></label>
                                                <select class="form-select" id="listCity" name="listCity" aria-label="Default select example" required="">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="strCheckAddress" class="form-label"> Dirección<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="strCheckAddress" value="<?=$_SESSION['userData']['address']?>" name="strCheckAddress" required="" placeholder="Carrera, calle, barrio...">
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
                                    <form id="formCoupon" class="mb-3">
                                        <div class="input-group">
                                            <input type="text" id="txtCoupon" name="cupon" class="form-control" placeholder="Código de descuento" aria-label="Coupon code" aria-describedby="button-addon2">
                                            <button type="button" class="btn btn-bg-1" type="button" id="btnCoupon">+</button>
                                        </div>
                                        <div class="alert alert-danger mt-3 d-none" id="checkAlertCoupon" role="alert"></div>
                                    </form>
                                    <div class="d-flex justify-content-between mt-3 mb-3 position-relative af-b-line">
                                        <p class="m-0 fw-bold fs-5">Total</p>
                                        <p class="m-0 fw-bold fs-5" id="checkTotal"></p>
                                    </div>
                                </div>
                            </div>
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