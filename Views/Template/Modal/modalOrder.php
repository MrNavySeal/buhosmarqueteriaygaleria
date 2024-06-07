<?php
    $pago="";
    $status="";
    for ($i=0; $i < count(PAGO) ; $i++) { 
        $pago .='<option value="'.PAGO[$i].'">'.PAGO[$i].'</option>';
    }
    for ($i=0; $i < count(STATUS) ; $i++) { 
        if(STATUS[$i]!="anulado"){
            $status .='<option value="'.$i.'">'.STATUS[$i].'</option>';
        }
    }

?>
<div class="modal fade" tabindex="-1" id="modalPurchase">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información de pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="" class="form-label">Cliente</label>
                    <input class="form-control" type="search" placeholder="Buscar cliente" aria-label="Search" id="searchItems" name="searchItems">
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
                                <label for="" class="form-label">Fecha de venta</label>
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
                        <div class="col-md-4">
                            <div class="mt-3 mb-3">
                                <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                                    <?=$status?>
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
                    <div class="d-flex justify-content-end">
                        <div class="d-flex p-2 fw-bold">
                            <p class="me-2">Total a pagar: </p>
                            <p id="totalPurchase">$0</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnSetPurchase"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>