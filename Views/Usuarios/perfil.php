<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mt-5 mb-3" id="<?=$data['page_name']?>">
    <nav class="mt-2 mb-2" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>">Inicio</a></li>
            <li class="breadcrumb-item"><a class="text-decoration-none" href="<?=base_url()?>/perfil">Perfil</a></li>
        </ol>
    </nav>
    <div class="row w-100">
        <div class="col-3 col-lg-3 col-md-12">
            <aside class="p-2 filter-options">
                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-categorie">
                            <button class="btn" type="button"></button>
                            <a href="<?=base_url()?>/dashboard" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-chart-line"></i> Dashboard </a>
                        </h2>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="true" aria-controls="flush-collapseTwo">
                            
                        </button>
                        <a href="<?=base_url()?>/usuarios" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-users"></i> Usuarios</a>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/usuarios">Usuarios</a>
                                    </li> 
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/roles">Roles</a>
                                    </li>        
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-categorie">
                            <button class="btn" type="button"></button>
                            <a href="<?=base_url()?>/clientes" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-user"></i> Clientes </a>
                        </h2>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-heading4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse4" aria-expanded="true" aria-controls="flush-collapse4">
                            
                        </button>
                        <a href="" class="text-decoration-none t-color-2 t-color-h-2"><i class="fas fa-crop-alt"></i> Marqueteria</a>
                        </h2>
                        <div id="flush-collapse4" class="accordion-collapse collapse show" aria-labelledby="flush-heading4">
                            <div class="accordion-body">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/marqueteria/molduras">Molduras</a>
                                    </li> 
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/marqueteria/materiales">Materiales</a>
                                    </li>      
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/marqueteria/colores">Colores</a>
                                    </li> 
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="<?=base_url()?>/marqueteria/categorias">Categorias</a>
                                    </li>   
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="filter-options-overlay"></div>
        </div>
        <div class="col-12 col-lg-9 col-md-12">
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="txtDocument" class="form-label">CC/NIT: <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtDocument" name="txtDocument" value="<?=$_SESSION['userData']['identification']?>" required>
                        </div>
                    </div>
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
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="txtAddress" class="form-label">Dirección <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="txtAddress" name="txtAddress" value="<?=$_SESSION['userData']['address']?>" required>
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
</main>
<?php footerPage($data)?> 