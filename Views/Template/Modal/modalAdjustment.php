<div class="modal fade" id="modalView">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Detalle de ajuste</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Fecha:</th>
                                <td id="strDate"></td>
                                <th>Nro ajuste:</th>
                                <td id="strId"></td>
                                <th>Responsable:</th>
                                <td colspan="3" id="strUser"></td>
                            </tr>
                            <tr>
                                <th>Concepto:</th>
                                <td colspan="7" id="strDescription" class="text-wrap"></td>
                            </tr>
                            <tr>
                                <th>Referencia</th>
                                <th>Nombre</th>
                                <th class="text-center">Actual</th>
                                <th class="text-end">Costo</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Ajuste</th>
                                <th class="text-center">Resultado</th>
                                <th class="text-end">Valor ajuste</th>
                            </tr>
                        </thead>
                        <tbody id="tableAdjustmentDet"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7" class="fw-bold text-end">Total:</td>
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