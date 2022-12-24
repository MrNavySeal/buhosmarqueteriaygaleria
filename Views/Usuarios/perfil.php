<?php headerAdmin($data)?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <form id="formProfile" name="formProfile" class="mb-4">
                    <input type="hidden" id="idUser" name="idUser" value="<?=$_SESSION['idUser']?>">
                    <div class="mb-3 uploadImg">
                        <img src="<?=media()?>/images/uploads/<?=$_SESSION['userData']['image']?>">
                        <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                        <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtFirstName" class="form-label">Nombres <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtFirstName" name="txtFirstName" value="<?=$_SESSION['userData']['firstname']?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtLastName" class="form-label">Apellidos <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtLastName" name="txtLastName" value="<?=$_SESSION['userData']['lastname']?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="<?=$_SESSION['userData']['email']?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPhone" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="txtPhone" name="txtPhone" value="<?=$_SESSION['userData']['phone']?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="countryList" class="form-label">País <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="countryList" name="countryList" required></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="stateList" class="form-label">Estado/departamento <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="stateList" name="stateList" required></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="cityList" class="form-label">Ciudad <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="cityList" name="cityList" required></select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtAddress" class="form-label">Dirección <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtAddress" name="txtAddress" value="<?=$_SESSION['userData']['address']?>" required>
                    </div>
                    <div class="row">
                        <hr>
                        <p class="fs-4 fw-bold">Cambiar contraseña</p>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPassword" class="form-label">Contraseña</label>
                                <input type="password" class="form-control" id="txtPassword" name="txtPassword">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtConfirmPassword" class="form-label">Confirmar contraseña</label>
                                <input type="password" class="form-control" id="txtConfirmPassword" name="txtConfirmPassword">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnAdd"> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        