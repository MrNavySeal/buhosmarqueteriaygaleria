<?php
    $pago="";
    for ($i=0; $i < count(PAGO) ; $i++) { 
        $pago .='<option value="'.PAGO[$i].'">'.PAGO[$i].'</option>';
    }
?>
<div class="modal fade" id="modalAdvance">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Abono de compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table text-nowrap text-start">
                        <thead>
                            <tr>
                                <th>Fecha:</th>
                                <td id="strDateAdvance"></td>
                                
                                
                            </tr>
                            <tr>
                                <th>Factura de compra:</th>
                                <td id="strIdAdvance"></td>
                            </tr>
                            <tr>
                                <th>Factura de proveedor:</th>
                                <td id="strCodeAdvance"></td>
                            </tr>
                            <tr>
                                <th>Proveedor:</th>
                                <td id="strSupplierAdvance"></td>
                            </tr>
                            <tr>
                                <th>Valor crédito:</th>
                                <td id="strTotalAdvance"></td>
                            </tr>
                        </thead>
                    </table>
                    <table class="table align-middle text-nowrap">
                        <thead>
                            <tr><th colspan="4" class="text-center">Abonar factura</th></tr>
                            <tr>
                                <th>Fecha</th>
                                <th>Valor a abonar</th>
                                <th>Método de pago</th>
                            </tr>
                            <tr>
                                <td><input type="date" class="form-control" id="subDate"></td>
                                <td><input type="number" class="form-control" id="subDebt" value="0" placeholder="Abono"></td>
                                <td><select class="form-control" aria-label="Default select example" id="subType"><?=$pago?></select></td>
                                <td><button class="btn btn-primary" type="button" title="add" onclick="addAdvance()"><i class="fas fa-plus"></i></button></td>
                            </tr>
                        </thead>
                        <tbody id="tablePurchaseAdvance"></tbody>
                    </table>
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>Responsable</th>
                                <th>Fecha</th>
                                <th>Abono</th>
                                <th class="text-center">Método de pago</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="tablePurchaseAdvance"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="fw-bold text-end">Total abonado:</td>
                                <td id="totalAdvance"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="fw-bold text-end">Total pendiente:</td>
                                <td id="totalPendent"></td>
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