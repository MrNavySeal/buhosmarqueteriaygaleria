<?php
    $status="";
    for ($i=0; $i < count(STATUS) ; $i++) { 
        if(STATUS[$i]!="anulado"){
            $status .='<option value="'.STATUS[$i].'">'.STATUS[$i].'</option>';
        }
    }
?>
<div class="modal fade" tabindex="-1" id="modalEdit">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idOrder" name="idOrder" value="">
                <div class="mt-3 mb-3">
                    <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                    <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                        <?=$status?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnAdd" onclick="updateItem()"><i class="fas fa-save"></i> Guardar</button>
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>