<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div >
                        <h5 class="modal-title mb-3">Información inicial</h5>
                        <div class="mb-3 uploadImg">
                            <img src="<?= BASE_URL?>/Assets/images/uploads/category.jpg">
                            <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtNit" class="form-label">NIT </label>
                                    <input type="text" class="form-control" id="txtNit" name="txtNit">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre proveedor<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtEmail" class="form-label">Correo</label>
                                    <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtPhone" name="txtPhone" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtWeb" class="form-label">Sitio web </label>
                                    <input type="text" class="form-control" id="txtWeb" name="txtWeb">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listCountry" class="form-label">Pais<span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="listCountry" name="listCountry" required>
                                        <option selected disabled>Seleccione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listState" class="form-label">Departamento<span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="listState" name="listState" required>
                                        <option selected disabled>Seleccione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listCity" class="form-label">Ciudad<span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="listCity" name="listCity" required>
                                        <option selected disabled>Seleccione</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtAddress" class="form-label">Dirección<span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtAddress" name="txtAddress" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="typeList" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="modal-title mb-3">Datos de contacto adicionales</h5>
                        <div class="d-flex align-items-center">
                            <div>
                                <label for="txtContact" class="form-label">Nombre </label>
                                <input type="text" class="form-control" id="txtContact">
                            </div>
                            <div >
                                <label for="txtPhoneContact" class="form-label">Teléfono</label>
                                <div class="d-flex align-items-center">
                                    <input type="number" class="form-control" id="txtPhoneContact">
                                    <button type="button" class="btn btn-primary" onclick="addContact()"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive overflow-y" style="max-height:50vh">
                            <table class="table table-sm align-middle text-center">
                                <thead>
                                    <th>Nombre</th>
                                    <th>Teléfono</th>
                                    <th></th>
                                </thead>
                                <tbody id="tableContacts"></tbody>
                            </table>
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