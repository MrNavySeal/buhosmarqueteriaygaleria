<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="suppList" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="suppList" name="suppList" required>
                                    <?php
                                        for ($i=0; $i < count($data) ; $i++) { 
                                    ?>
                                    <option value="<?=$data[$i]['idsupplier']?>"><?=$data[$i]['name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtReference" class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="txtReference" name="txtReference">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtName" name="txtName" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtCost" class="form-label">Costo <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="txtCost" name="txtCost" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="typeList" class="form-label">IVA <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                                    <option value="1">0%</option>
                                    <option value="2">5%</option>
                                    <option value="3">19%</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <option value="1">Activo</option>
                                    <option value="2">Inactivo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>