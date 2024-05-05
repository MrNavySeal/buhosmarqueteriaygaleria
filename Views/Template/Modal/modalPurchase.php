<?php
    $pago="";
    for ($i=0; $i < count(PAGO) ; $i++) { 
        $pago .='<option value="'.PAGO[$i].'">'.PAGO[$i].'</option>';
    }
?>
<div class="modal fade" tabindex="-1" id="modalPurchase">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información de pago de compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="" class="form-label">Proveedor</label>
                    <input class="form-control" type="search" placeholder="Buscar proveedor" aria-label="Search" id="searchItems" name="searchItems">
                </div>
                <div class="position-relative" id="selectItems">
                    <div id="items" class="bg-white position-absolute w-100 border border-primary" style="overflow-y:scroll; max-height:30vh;"></div>
                </div>
                <div id="selectedItem"></div>
                <form id="formSetOrder">
                    <input type="hidden" name="id" id="id" value ="">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Código de factura</label>
                                <input type="text" name="strCode" id="txtCode" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Fecha de compra</label>
                                <input type="date" name="strDate" id="txtDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Tipo de pago <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="paymentList" name="paymentList" required>
                                    <?=$pago?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Notas</label>
                                <textarea rows="3" name="strNote" id="txtNote" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div id="itemSuscription" class="d-none">
                        <hr>
                        <label for="typeList" class="form-label mt-3">Anticipos</label>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody id="listSuscription">
                                    <tr>
                                        <td class="fw-bold">Fecha</td>
                                        <td class="fw-bold">Anticipo</td>
                                        <td class="fw-bold">Tipo de pago</td>
                                    </tr>
                                    <tr>
                                        <td><input type="date" class="form-control" id="subDate"></td>
                                        <td><input type="number" class="form-control" id="subDebt" value="0" placeholder="Abono"></td>
                                        <td><select class="form-control" aria-label="Default select example" id="subType"><?=$pago?></select></td>
                                        <td><button class="btn btn-primary m-1" type="button" title="add" onclick="addSuscription()"><i class="fas fa-plus"></i></button></td>
                                    </tr>
                                </tbody>
                                <tfoot id="totalDebt"></tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <div class="d-flex p-2 fw-bold">
                            <p class="me-2">Total a pagar: </p>
                            <p id="totalPurchase">$0</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnSetPurchase">Guardar</button>
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>