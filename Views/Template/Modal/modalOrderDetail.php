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
                            <table class="table text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Fecha de emisión:</th>
                                        <td id="strDate"></td>
                                    </tr>
                                    <tr>
                                        <th>Fecha de vencimiento:</th>
                                        <td id="strDateBeat"></td>
                                        <th>Factura de venta:</th>
                                        <td id="strId"></td>
                                    </tr>
                                    <tr>
                                        <th>Transacción:</th>
                                        <td id="strCode"></td>
                                        <th>Método de pago:</th>
                                        <td id="strMethod"></td>
                                    </tr>
                                    <tr>
                                        <th>Estado de pago:</th>
                                        <td id="strStatus"></td>
                                        <th>Estado de pedido:</th>
                                        <td id="strStatusOrder"></td>
                                    </tr>
                                    <tr>
                                        <th>Nombre:</th>
                                        <td id="strName"></td>
                                        <th>Dirección:</th>
                                        <td id="strAddress"></td>
                                    </tr>
                                    <tr>
                                        <th>Teléfono:</th>
                                        <td id="strPhone"></td>
                                        <th>Correo:</th>
                                        <td id="strEmail"></td>
                                    </tr>
                                    <tr>
                                        <th>CC/NIT:</th>
                                        <td id="strNit"></td>
                                        <th>Notas:</th>
                                        <td id="strNotes"></td>
                                    </tr>
                                </thead>
                            </table>
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
                        <div class="table-responsive">
                            <table class="table text-nowrap text-start">
                                <thead>
                                    <tr>
                                        <th>Fecha:</th>
                                        <td id="viewStrDateAdvance"></td>
                                        <th>Factura de venta:</th>
                                        <td id="viewStrIdAdvance"></td>
                                    </tr>
                                    <tr>
                                        <th>Valor crédito:</th>
                                        <td id="viewStrTotalAdvance"></td>
                                    </tr>
                                </thead>
                            </table>
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