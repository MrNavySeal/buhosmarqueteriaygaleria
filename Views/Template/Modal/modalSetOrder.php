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
<div class="modal fade" tabindex="-1" id="modalSetOrder">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Información de pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="id" value ="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mt-3 mb-3">
                            <label for="" class="form-label">Tipo de pago <span class="text-danger">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="paymentList" name="paymentList" required>
                                <?=$pago?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mt-3 mb-3">
                            <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                            <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                                <?=$status?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnSetPurchase"><i class="fas fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>