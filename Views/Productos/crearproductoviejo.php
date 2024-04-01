<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <form id="formFile" name="formFile">
                    <div class="row scrolly" id="upload-multiple">
                        <div class="col-6 col-lg-3">
                            <div class="mb-3 upload-images">
                                <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                    <div>
                                        <i class="far fa-images fs-1"></i>
                                        <p class="m-0">Subir imágen</p>
                                    </div>
                                </label>
                                <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*"> 
                            </div>
                        </div>
                    </div>
                </form>
                <form id="formItem" name="formItem" class="mb-4">  
                    <input type="hidden" id="idProduct" name="idProduct" value="">
                    <div class="row">
                        <p class="text-center">Todos los campos con (<span class="text-danger">*</span>) son obligatorios.</p>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtReference" class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="txtReference" name="txtReference">
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="txtDiscount" class="form-label">Descuento</label>
                                <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="framingMode" class="form-label">Modo enmarcar</label>
                                <select class="form-control" aria-label="Default select example" id="framingMode" name="framingMode" required>
                                    <option value="1">Si aplica</option>
                                    <option value="2" selected>No aplica</option>
                                </select>
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
                    <div class="mb-3 framingImage d-none">
                        <h4>Imagen a enmarcar</h4>
                        <div class="uploadImg">
                            <img src="<?=media()?>/images/uploads/category.jpg">
                            <label for="txtImgFrame"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImgFrame" name="txtImgFrame" accept="image/*"> 
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtShortDescription" class="form-label">Descripción corta</label>
                        <input type="text" class="form-control" id="txtShortDescription" name="txtShortDescription" placeholder="Max 140 carácteres"></input>
                    </div>
                    <div class="mb-3">
                        <label for="txtDescription" class="form-label">Descripción </label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <h3>Tipo de producto</h3>
                        <select class="form-control" aria-label="Default select example" id="selectProductType" name="selectProductType" required>
                            <option value="1" selected>Producto sin variantes</option>
                            <option value="2">Producto con variantes</option>
                        </select>
                    </div>
                    <hr>
                    <div class="productType mb-3">
                        <div class="productBasic">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="txtPrice" name="txtPrice">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtStock" class="form-label">Cantidad</label>
                                        <input type="number" value="0" class="form-control" id="txtStock" name="txtStock">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="productVariant d-none mb-3">
                            <button type="button" class="btn btn-info mb-3 text-white" id="btnVariant"><i class="fas fa-plus"></i> Agregar variante</button>
                            <hr>
                            <h4>Variantes</h4>
                            <table class="table table-bordered">
                                <tbody class="variantList">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <h3>Especificaciones</h3>
                    <div class="productSpc">
                        <label for="selectTypeSpc" class="form-label">Tipo de dato <span class="text-danger">*</span></label>
                        <div class="d-flex justify-content-start align-items mb-3">
                            <select class="form-control" aria-label="Default select example" id="selectTypeSpc" name="selectTypeSpc">
                                <option value="1">Texto</option>
                                <option value="2">Número</option>
                            </select>
                            <button type="button" class="btn btn-info text-white" id="btnSpc"><i class="fas fa-plus"></i></button>
                        </div>
                        <hr>
                        <h4>Ficha técnica</h4>
                        <table class="table table-bordered">
                            <tbody class="spcList">
                                <tr class="spcItem" >
                                    <td><input type="text" value="Dimensiones" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="text" value="30*30*30cm" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <tr class="spcItem" >
                                    <td><input type="text" value="Material" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="text" value="Lienzo" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <tr class="spcItem">
                                    <td><input type="text" value="Acabado" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="text" value="Natural" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <tr class="spcItem">
                                    <td><input type="text" value="Largo" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="number" value="30" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <tr class="spcItem">
                                    <td><input type="text" value="Alto" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="number" value="30" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                            <a href="<?=BASE_URL?>/inventario/productos" class="btn btn-secondary text-white"> Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?> 
