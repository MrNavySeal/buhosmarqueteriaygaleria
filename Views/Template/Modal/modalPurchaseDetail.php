<div class="modal fade" id="modalView">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle de compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Fecha:</th>
                                <td id="strDate"></td>
                                <th>Factura de compra:</th>
                                <td id="strId"></td>
                                <th>Factura de proveedor:</th>
                                <td id="strCode"></td>
                            </tr>
                            <tr>
                                <th>Método de pago:</th>
                                <td colspan="3" id="strMethod"></td>
                                <th>Estado:</th>
                                <td colspan="3" id="strStatus"></td>
                            </tr>
                            <tr>
                                <th>Proveedor:</th>
                                <td colspan="3" id="strSupplier"></td>
                                <th>Atendió:</th>
                                <td colspan="3" id="strUser"></td>
                            </tr>
                            <tr>
                                <th colspan="3">Nombre</th>
                                <th>Precio</th>
                                <th class="text-center">Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="tablePurchaseDetail"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="fw-bold text-end">Subtotal:</td>
                                <td id="subtotal"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="fw-bold text-end">IVA:</td>
                                <td id="iva"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="fw-bold text-end">Descuento:</td>
                                <td id="discount"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="fw-bold text-end">Total:</td>
                                <td id="total"></td>
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