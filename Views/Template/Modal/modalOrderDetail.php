<div class="modal fade" id="modalView">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle de pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills mb-3" id="product-tab">
                    <li class="nav-item">
                        <button class="nav-link active" id="navDetail-tab" data-bs-toggle="tab" data-bs-target="#navDetail" type="button" role="tab" aria-controls="navDetail" aria-selected="true">Detalle</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link " id="navAdvance-tab" data-bs-toggle="tab" data-bs-target="#navAdvance" type="button" role="tab" aria-controls="navAdvance" aria-selected="true">Abonos</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navDetail">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Fecha de emisión</label>
                                        <p class="text-break" id="strDate"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Fecha de vencimiento</label>
                                        <p class="text-break" id="strDateBeat"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Factura de venta</label>
                                        <p class="text-break" id="strId"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Transacción</label>
                                        <p class="text-break" id="strCode"></p>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Estado de pago</label>
                                        <p class="text-break" id="strStatus"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Estado de pedido</label>
                                        <p class="text-break" id="strStatusOrder"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Método de pago</label>
                                        <p class="text-break" id="strMethod"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Nombre</label>
                                        <p class="text-break" id="strName"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Teléfono</label>
                                        <p class="text-break" id="strPhone"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Correo</label>
                                        <p class="text-break" id="strEmail"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">CC/NIT</label>
                                        <p class="text-break" id="strNit"></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Dirección</label>
                                        <p class="text-break" id="strAddress"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label fw-bold">Notas</label>
                                <p class="text-break" id="strNotes"></p>
                            </div>
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Referencia</th>
                                        <th>Descripción</th>
                                        <th>Precio</th>
                                        <th class="text-center">Cantidad</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="tablePurchaseDetail"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="fw-bold text-end">Subtotal:</td>
                                        <td id="subtotal"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bold text-end">Descuento:</td>
                                        <td id="orderDiscount"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bold text-end">Envio:</td>
                                        <td id="shipping"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="fw-bold text-end">Total:</td>
                                        <td id="total"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navAdvance">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Fecha</label>
                                    <p class="text-break" id="viewStrDateAdvance"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Factura de venta</label>
                                    <p class="text-break" id="viewStrIdAdvance"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Valor crédito</label>
                                    <p class="text-break" id="viewStrTotalAdvance"></p>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="max-height:40vh">
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Responsable</th>
                                        <th>Fecha</th>
                                        <th class="text-center">Método de pago</th>
                                        <th>Abono</th>
                                    </tr>
                                </thead>
                                <tbody id="viewTablePurchaseAdvance"></tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-end">Total abonado:</td>
                                        <td id="viewTotalAdvance"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="fw-bold text-end">Total pendiente:</td>
                                        <td id="viewTotalPendent"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>