<div class="modal fade" id="modalPago">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Continua con el pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table align-middle text-break">
                    <tbody id="listItem">
                        <form id="formSchedule">
                            <ul class="nav nav-pills mb-3" id="product-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="pills-card-tab" data-bs-toggle="pill" data-bs-target="#pills-card" type="button" role="tab" aria-controls="pills-card" aria-selected="true"><i class="fas fa-credit-card"></i></button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pills-pse-tab" data-bs-toggle="pill" data-bs-target="#pills-pse" type="button" role="tab" aria-controls="pills-pse" aria-selected="false">Descripción</button>
                                </li>
                            </ul>
                             <div class="tab-content" id="pills-tabContent">
                                
                                <div class="tab-pane fade show active" id="pills-card" role="tabpanel" aria-labelledby="pills-card-tab" tabindex="0">
                                    <form id="form-checkout">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="form-checkout__cardNumber" class="form-label">Número de tarjeta</label>
                                                    <input type="text" class="form-control" id="form-checkout__cardNumber">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="form-checkout__expirationDate" class="form-label">Fecha de expiración</label>
                                                    <input type="text" class="form-control" id="form-checkout__expirationDate">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="form-checkout__securityCode" class="form-label">CCV</label>
                                                    <input type="text" class="form-control" id="form-checkout__securityCode">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="form-checkout__cardholderName" class="form-label">Nombre que aparece en la tarjeta</label>
                                                    <input type="text" class="form-control" id="form-checkout__cardholderName">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label for="form-checkout__cardNumber" class="form-label">Cédula/Doc de identidad</label>
                                                    <input type="text" class="form-control" id="form-checkout__securityCode">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade" id="pills-pse" role="tabpanel" aria-labelledby="pills-pse-tab" tabindex="0">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-bg-1" id="btnSchedule" >Pagar</button>
                                <button type="button" class="btn btn-bg-2 text-white" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>