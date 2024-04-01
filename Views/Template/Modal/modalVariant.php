<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nueva variante</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtName" name="txtName" required>
                    </div>
                    <div class="mb-3">
                        <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>
                    <div>
                        <h5 class="modal-title mb-3">Opciones creadas <span class="text-danger">*</span></h5>
                        <p class="text-secondary">Agrega opciones a la variante. Ejemplo: Si la variante se llama "Talla" las opciones son S, M, XL, etc...</p>
                        <label for="txtNameVariant" class="form-label">Nombre</label>
                        <div class="d-flex align-items-center mb-3">
                            <input type="text" class="form-control" id="txtNameVariant">
                            <button type="button" class="btn btn-primary" onclick="addVariant()"><i class="fas fa-plus"></i></button>
                        </div>
                        <div class="table-responsive overflow-y" style="max-height:30vh">
                            <div id="tableVariants" class="d-flex flex-wrap"></div>
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