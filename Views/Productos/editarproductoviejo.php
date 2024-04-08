<?php 
    headerPage($data);
    $product = $data['product'];
    $images = $product['image'];
    $categories = $product['categories'];
    $subcategories = $product['subcategories'];
    $htmlc='<option value="0" >Seleccione</option>';
    $htmls='<option value="0" >Seleccione</option>';
    $framingImageToggle = $product['framing_mode'] == 1 ? "" : "d-none";
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="categoryList" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="categoryList" name="categoryList" required><?=$htmlc?></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="subcategoryList" class="form-label">Subcategoria <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required><?=$htmls?></select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="txtDiscount" class="form-label">Descuento</label>
                                <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount" value="<?=$product['discount']?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="framingMode" class="form-label">Modo enmarcar</label>
                                <select class="form-control" aria-label="Default select example" id="framingMode" name="framingMode" required>
                                    <?php
                                        $enmarcar='<option value="1" selected>Si aplica</option><option value="2" >No aplica</option>';
                                        if($product['framing_mode']==2){
                                            $enmarcar='<option value="1">Si aplica</option><option value="2" selected>No aplica</option>';
                                        }
                                    ?>
                                    <?=$enmarcar?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <?php
                                        $status='<option value="1" selected>Activo</option><option value="2" >Inactivo</option>';
                                        if($product['status']==2){
                                            $status='<option value="1">Activo</option><option value="2" selected>Inactivo</option>';
                                        }
                                    ?>
                                    <?=$status?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3 framingImage <?=$framingImageToggle?>">
                        <h4>Imagen a enmarcar</h4>
                        <div class="uploadImg">
                            <img src="<?=media()."/images/uploads/".$product['framing_img']?>">
                            <label for="txtImgFrame"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImgFrame" name="txtImgFrame" accept="image/*"> 
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="txtShortDescription" class="form-label">Descripción corta</label>
                        <input type="text" class="form-control" id="txtShortDescription" value="<?=$product['shortdescription']?>" name="txtShortDescription" placeholder="Max 140 carácteres"></input>
                    </div>
                    <div class="mb-3">
                        <label for="txtDescription" class="form-label">Descripción </label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"><?=$product['description']?></textarea>
                    </div>
                    <div class="mb-3">
                        <h3>Tipo de producto</h3>
                        <select class="form-control" aria-label="Default select example" id="selectProductType" name="selectProductType" required>
                            <?php
                                $typeProduct='<option value="1" selected>Sin variantes</option><option value="2" >Producto con variantes</option>';
                                $product1= "";
                                $product2="d-none";
                                if($product['product_type']==2){
                                    $product1= "d-none";
                                    $product2="";
                                    $typeProduct='<option value="1">Sin variantes</option><option value="2" selected>Producto con variantes</option>';
                                }
                            ?>
                            <?=$typeProduct?>
                        </select>
                    </div>
                    <div class="productType mb-3">
                        <div class="productBasic <?=$product1?>">
                            <div class="row">
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
                            </div>
                        </div>
                        <div class="productVariant <?=$product2?> mb-3">
                            <button type="button" class="btn btn-info mb-3 text-white" id="btnVariant"><i class="fas fa-plus"></i> Agregar</button>
                            <hr>
                            <h4>Variantes</h4>
                            <table class="table table-bordered">
                                <tbody class="variantList">
                                    <?php 
                                        $variants = $product['variants'];
                                        for ($i=0; $i < count($variants); $i++) { 
                                            $vWidth = $variants[$i]['width'];
                                            $vHeight = $variants[$i]['height'];
                                            $vStock = $variants[$i]['stock'];
                                            $vPrice = $variants[$i]['price'];
                                            $showVariant = $vWidth."-".$vHeight."-".$vStock."-".$vPrice;
                                    ?>
                                    <tr class="variantItem">
                                        <td><input type="number" value="<?=$vWidth?>" class="form-control" placeholder="Ancho"></td>
                                        <td><input type="number" value="<?=$vHeight?>" class="form-control" placeholder="Alto"></td>
                                        <td><input type="number" value="<?=$vStock?>" class="form-control" placeholder="Cantidad"></td>
                                        <td><input type="number" value="<?=$vPrice?>" class="form-control" placeholder="Precio"></td>
                                        <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                    <?php } ?>
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
                                <?php 
                                    $specs = json_decode($product['specifications'],true);
                                    for ($i=0; $i < count($specs); $i++) { 
                                ?>
                                <tr class="spcItem" >
                                    <td><input type="text" value="<?=$specs[$i]['name']?>" class="form-control" placeholder="Nombre dato"></td>
                                    <td><input type="<?=$specs[$i]['type']?>" value="<?=$specs[$i]['value']?>" class="form-control" placeholder="Dato"></td>
                                    <td><button type="button" class="btn btn-danger text-white" onclick="removeItem(this.parentElement.parentElement)"><i class="fas fa-trash"></i></button></td>
                                </tr>
                                <?php } ?>
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
