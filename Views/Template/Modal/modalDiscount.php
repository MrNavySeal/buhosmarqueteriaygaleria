
<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo descuento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="idDiscount" name="idDiscount">
                    <div class="mb-3">
                        <label for="typeList" class="form-label">Tipo de descuento<span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                            <option value="1">Por Categoría</option>
                            <option value="2">Por Subcategoría</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required>
                            <?= $data?>
                        </select>
                    </div>
                    <div class="mb-3 subcategoryDisplay d-none">
                        <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList"></select>
                    </div>
                    <div class="mb-3">
                        <label for="intDiscount" class="form-label">Descuento (%) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="intDiscount" name="intDiscount" required>
                    </div>
                    <div class="mb-3">
                        <label for="typeList" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                            <option value="1">Activo</option>
                            <option value="2">Inactivo</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnAdd">Guardar</button>
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>