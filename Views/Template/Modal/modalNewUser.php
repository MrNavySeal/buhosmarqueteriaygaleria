<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo usuario</h5>
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
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="txtPhone" name="txtPhone" required>
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
                                <label for="typeList" class="form-label">Rol <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required><?=$data['data']?></select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="typeList" class="form-label">Estado <span class="text-danger">*</span></label>
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