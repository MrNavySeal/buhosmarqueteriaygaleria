<?php
    $pago="";
    for ($i=0; $i < count(PAGO) ; $i++) { 
        $pago .='<option value="'.$i.'">'.PAGO[$i].'</option>';
    }
?>
<div class="modal fade" tabindex="-1" id="modalPos">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Punto de venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="" class="form-label">Cliente</label>
                    <input class="form-control" type="search" placeholder="Buscar" aria-label="Search" id="searchCustomers" name="searchCustomers">
                </div>
                <div class="position-relative" id="selectCustomers">
                    <div id="customers" class="bg-white position-absolute w-100" style="overflow-y:scroll; max-height:30vh;"></div>
                </div>
                <div id="selectedCustomer"></div>
                <form id="formSetOrder">
                    <input type="hidden" id="idOrder" name="idOrder" value="">
                    <input type="hidden" name="id" id="idCustomer" value ="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Fecha de emisión</label>
                                <input type="date" name="strDate" id="txtDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Fecha de vencimiento</label>
                                <input type="date" name="strDate" id="txtDateBeat" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Notas</label>
                                <textarea rows="3" name="strNote" id="txtNotePos" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Tipo de pago <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="paymentList" name="paymentList" required>
                                    <?=$pago?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mt-3 mb-3">
                                <label for="typeList" class="form-label">Estado de pago <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <option value="1">aprobado</option>
                                    <option value="2" selected>pendiente</option>
                                    <option value="3">cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mt-3 mb-3">
                                <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                                    <?php
                                        $status="";
                                        for ($i=0; $i < count(STATUS) ; $i++) { 
                                            $status .='<option value="'.$i.'">'.STATUS[$i].'</option>';
                                        }
                                    ?>
                                    <?=$status?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mt-3 mb-3">
                                <label for="updateCustomer" class="form-label">Actualizar datos cliente</label>
                                <select class="form-control" aria-label="Default select example" id="updateCustomer" name="updateCustomer">
                                    <option value="1" selected>No</option>
                                    <option value="2">Si</option>
                                </select>
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
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label" id="totalOrder">Dinero recibido <span class="text-danger">*</span></label>
                                <input type="number" name="received" id="moneyReceived" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">

                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Descuento (opcional)</label>
                                <input type="number" name="discount" id="discount" class="form-control">
                            </div>
                        </div>
                    </div>
                    <p id="saleValue"></p>
                    <p id="moneyBack"></p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnAddPos">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>