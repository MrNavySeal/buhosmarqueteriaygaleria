<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo Egreso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div class="mt-3 mb-3">
                        <label for="txtDate" class="form-label">Fecha</label>
                        <input type="date" name="txtDate" id="txtDate" class="form-control">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="typeList" class="form-label">Tipo <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                                    <option value="1">Gastos</option>
                                    <option value="2">Costos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required>
                                    <?php
                                        for ($i=0; $i < count($data) ; $i++) { 
                                    ?>
                                    <option value="<?=$data[$i]['id']?>"><?=$data[$i]['name']?></option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Concepto</label>
                        <input type="text" class="form-control" id="txtName" name="txtName">
                    </div>
                    <div class="mb-3">
                        <label for="txtAmount" class="form-label">Monto <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="txtAmount" name="txtAmount" required>
                    </div>
                    <div class="mb-3">
                        <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
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