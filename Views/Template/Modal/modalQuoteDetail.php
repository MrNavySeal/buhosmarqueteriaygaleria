<div class="modal fade" id="modalView">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle de cotización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                                <label for="" class="form-label fw-bold">No. Cotización</label>
                                <p class="text-break" id="strId"></p>
                            </div>
                        </div>
                    </div>   
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="" class="form-label fw-bold">Estado</label>
                                <p class="text-break" id="strStatus"></p>
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
                                <th class="text-end">Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tablePurchaseDetail"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="fw-bold text-end">Subtotal:</td>
                                <td id="subtotal" class="text-end"></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="fw-bold text-end">Descuento:</td>
                                <td id="orderDiscount" class="text-end"></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="fw-bold text-end">Total:</td>
                                <td id="total" class="text-end"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>