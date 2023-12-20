<div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nuevo cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formItem" name="formItem" class="mb-4">
                        <input type="hidden" id="idUser" name="idUser">
                        <div class="mb-3 uploadImg">
                            <img src="<?=BASE_URL."/Assets/images/uploads/user.jpg"?>">
                            <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtFirstName" class="form-label">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtFirstName" name="txtFirstName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtLastName" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtLastName" name="txtLastName" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtDocument" class="form-label">CC/NIT</label>
                                    <input type="text" class="form-control" id="txtDocument" name="txtDocument">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="txtEmail" name="txtEmail">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="txtPhone" name="txtPhone" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtAddress" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="txtAddress" name="txtAddress">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listCountry" class="form-label">País</label>
                                    <select class="form-control" aria-label="Default select example" id="listCountry" name="listCountry"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listState" class="form-label">Estado/departamento</label>
                                    <select class="form-control" aria-label="Default select example" id="listState" name="listState"></select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="listCity" class="form-label">Ciudad</label>
                                    <select class="form-control" aria-label="Default select example" id="listCity" name="listCity"></select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="txtPassword" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="txtPassword" name="txtPassword">
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