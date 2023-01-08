<?php 
    headerAdmin($data);
    $product = $data['product'];
    $images = $product['image'];
    $categories = $product['categories'];
    $subcategories = $product['subcategories'];
    $htmlc='<option value="0" >Seleccione</option>';
    $htmls='<option value="0" >Seleccione</option>';
    for ($i=0; $i < count($categories); $i++) { 
        if($product['idcategory'] == $categories[$i]['idcategory']){
            $htmlc.='<option value="'.$categories[$i]['idcategory'].'" selected>'.$categories[$i]['name'].'</option>';
        }else{
            $htmlc.='<option value="'.$categories[$i]['idcategory'].'">'.$categories[$i]['name'].'</option>'; 
        }
    }
    for ($i=0; $i < count($subcategories); $i++) { 
        if($product['idsubcategory'] == $subcategories[$i]['idsubcategory']){
            $htmls.='<option value="'.$subcategories[$i]['idsubcategory'].'" selected>'.$subcategories[$i]['name'].'</option>';
        }else{
            $htmls.='<option value="'.$subcategories[$i]['idsubcategory'].'">'.$subcategories[$i]['name'].'</option>';
        }
    }
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
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
                        <?php for ($i=0; $i < count($images); $i++) { ?>
                        <div class="col-6 col-lg-3 upload-image mb-3" data-name="<?=$images[$i]['name']?>" data-rename="<?=$images[$i]['rename']?>">
                            <img src="<?=$images[$i]['url']?>">
                            <div class="deleteImg" name="delete">x</div>
                        </div>
                        <?php }?>
                    </div>
                </form>
                <form id="formItem" name="formItem" class="mb-4">  
                    <input type="hidden" id="idProduct" name="idProduct" value="<?=$product['idproduct']?>">
                    <div class="row">
                        <p class="text-center">Todos los campos con (<span class="text-danger">*</span>) son obligatorios.</p>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtReference" class="form-label">Referencia</label>
                                <input type="text" class="form-control" id="txtReference" name="txtReference" value="<?=$product['reference']?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="txtName" name="txtName" value="<?=$product['name']?>" required >
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required><?=$htmlc?></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required><?=$htmls?></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtDiscount" class="form-label">Descuento</label>
                                <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount" value="<?=$product['discount']?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" value="<?=$product['price']?>" id="txtPrice" name="txtPrice" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="txtStock" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                <input type="number" value="<?=$product['stock']?>" class="form-control" id="txtStock" name="txtStock" required>
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
                    <div class="mb-3">
                        <label for="txtShortDescription" class="form-label">Descripción corta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtShortDescription" value="<?=$product['shortdescription']?>" name="txtShortDescription" placeholder="Max 140 carácteres"></input>
                    </div>
                    <div class="mb-3">
                        <label for="txtDescription" class="form-label">Descripción </label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"><?=$product['description']?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2" id="btnAdd"><i class="fas fa-plus-circle"></i> Agregar</button>
                            <a href="<?=BASE_URL?>/inventario/productos" class="btn btn-secondary text-white"> Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data)?>     
</script> 
