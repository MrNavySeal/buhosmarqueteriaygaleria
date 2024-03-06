<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="idSupplier" name="idSupplier">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtNit" class="form-label">NIT </label>
                                <input type="text" class="form-control" id="txtNit" name="txtNit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtName" name="txtName" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">Correo</label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="txtPhone" name="txtPhone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtAddress" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="txtAddress" name="txtAddress">
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