<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nueva categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="idCategory" name="idCategory">
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtName" name="txtName" required>
                    </div>
                    <div class="mb-3">
                        <label for="typeList" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                            <option value="1">Gastos</option>
                            <option value="2">Costos</option>
                            <option value="3">Ingresos</option>
                        </select>
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