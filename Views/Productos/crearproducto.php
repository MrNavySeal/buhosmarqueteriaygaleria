<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <div class="d-flex align-items-center">
                    <a href="<?=base_url()?>/productos" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
                    <h2 class="text-center m-0"><?=$data['page_title']?></h2>
                </div>
                <ul class="nav nav-pills mb-5" id="product-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Variantes</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab" aria-controls="payment" aria-selected="true">Precios al por mayor</button>
                    </li>
                </ul>
                <div class="tab-content mb-3" id="myTabContent">
                    <div class="tab-pane show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                        <form id="formFile" name="formFile">
                            <div  style="display:flex;overflow-x:auto;" id="upload-multiple">
                                <div class="mb-3 upload-images me-3">
                                    <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-images fs-1"></i>
                                            <p class="m-0">Subir imágen</p>
                                        </div>
                                    </label>
                                    <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*"> 
                                </div>
                            </div>
                        </form>
                        <form id="formItem" name="formItem" class="mb-4">  
                            <input type="hidden" id="id" name="id" value="">
                            <p class="text-center">Todos los campos con (<span class="text-danger">*</span>) son obligatorios.</p>
                            <div>
                                <h5>Información de artículo</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="txtName" name="txtName" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="txtReference" class="form-label">Código SKU</label>
                                            <input type="text" class="form-control" id="txtReference" name="txtReference">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5>Descripción de artículo</h5>
                                <div class="mb-3">
                                    <label for="txtShortDescription" class="form-label">Descripción corta</label>
                                    <input type="text" class="form-control" id="txtShortDescription" name="txtShortDescription" placeholder="Max 140 carácteres"></input>
                                </div>
                                <div class="mb-3">
                                    <label for="txtDescription" class="form-label">Descripción </label>
                                    <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div id="showCategories" class="d-none">
                                    <div class="mb-3">
                                        <h5>Categoría de artículo</h5>
                                        <p class="text-secondary">Selecciona la categoría a la que pertenece tu artículo. (opcional)</p>
                                        <div class="mb-3">
                                            <select class="form-control" onchange="changeCategory()" aria-label="Default select example" id="categoryList" name="categoryList" required></select>
                                        </div>
                                    </div>
                                    <div class="mb-3" id="selectSubCategory">
                                        <h5>Subcategoria de artículo</h5>
                                        <p class="text-secondary">Selecciona la subcategoría de tu artículo. (opcional)</p>
                                        <div class="mb-3">
                                            <select class="form-control" aria-label="Default select example" id="subcategoryList" name="subcategoryList" required></select>
                                        </div>
                                    </div>
                                </div>
                                <div id="toAddCategories" class="d-none">
                                    <p class="text-secondary">
                                        No tienes ninguna categoría creada. 
                                        <a href="<?=base_url()."/ProductosCategorias/categorias"?>" target="_blank" class="btn btn-primary text-white">
                                            Crear categoría
                                        </a>
                                    </p>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5>Tipo de artículo</h5>
                                <div class="form-check">
                                    <input class="form-check-input product_type" type="checkbox" id="checkProduct" value="">
                                    <label class="form-check-label" for="checkProduct">
                                        Producto. <span class="text-secondary"> Marca esta casilla si el artículo es un producto</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input product_type" type="checkbox" id="checkIngredient" value="">
                                    <label class="form-check-label" for="checkIngredient">
                                        Insumo. <span class="text-secondary"> Marca esta casilla si el artículo es un insumo</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input product_type" type="checkbox" id="checkRecipe" value="">
                                    <label class="form-check-label" for="checkRecipe">
                                        Fórmula/servicio/combo. <span class="text-secondary"> Marca esta casilla si el artículo es una fórmula</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <h5>Unidad de medida</h5>
                                <div class="mb-3">
                                    <select class="form-control" aria-label="Default select example" id="selectMeasure" name="selectMeasure"></select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h5>Inventario</h5>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="checkInventory" value="">
                                    <label class="form-check-label text-secondary" for="checkInventory">
                                        <span class="text-secondary"> Este artículo tiene stocks limitados</span>
                                    </label>
                                </div>
                                <div class="row d-none" id="setStocks">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="txtStock" class="form-label">Stock <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="txtStock" name="txtStock" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="txtMinStock" class="form-label">Stock mínimo <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="txtMinStock" name="txtMinStock" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5>Impuesto</h5>
                                <div class="mb-3">
                                    <label for="selectImport" class="form-label">Seleccione impuesto <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="selectImport" name="selectImport" >
                                        <option value="0" selected>Ninguno</option>
                                        <option value="19">IVA 19%</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <h5>Precio de artículo</h5>
                                <div class="row">
                                    <div class="col-md-4" id="divPurchase">
                                        <div class="mb-3">
                                            <label for="txtPurchase" class="form-label">Precio de compra<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="txtPurchase" name="txtPurchase" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="txtPrice" class="form-label">Precio de venta<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="txtPrice" name="txtPrice">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label for="txtPriceOffer" class="form-label">Precio de oferta</label>
                                            <input type="number" class="form-control" id="txtPriceOffer" name="txtPriceOffer">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="framingMode" class="form-label">Modo enmarcar</label>
                                            <p class="text-secondary">Aplica si el artículo se puede enmarcar.</p>
                                            <select class="form-control" aria-label="Default select example" id="framingMode" name="framingMode">
                                                <option value="1">Si aplica</option>
                                                <option value="2" selected>No aplica</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                            <p class="text-secondary">Habilita si el artículo es visible en la tienda virtual.</p>
                                            <select class="form-control" aria-label="Default select example" id="statusList" name="statusList">
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
                            </div>
                            <div>
                                <h5>Características de artículo</h5>
                                <div id="activeSpecs" class="d-none">
                                    <p class="text-secondary">Agrega características que quieres resaltar de tu artículo como el material, marca modelo, etc.</p>
                                    <div class="mb-3">
                                        <label for="selectTypeSpc" class="form-label">Característica</label>
                                        <div class="d-flex justify-content-start align-items mb-3">
                                            <select class="form-control" aria-label="Default select example" id="selectTypeSpc" name="selectTypeSpc"></select>
                                            <button onclick ="addSpec()" type="button" class="btn btn-info text-white" id="btnSpc"><i class="fas fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div class="table-responsive overflow-y" style="max-height:50vh">
                                        <table class="table align-middle"><tbody id="tableSpecs"></tbody></table>
                                    </div>
                                </div>
                                <div id="addSpecs" class="d-none">
                                    <p class="text-secondary">
                                        No tienes ninguna característica creada. 
                                        <a href="<?=base_url()."/ProductosOpciones/caracteristicas"?>" target="_blank" class="btn btn-primary text-white">
                                            Crear característica
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
                        <h5>Variantes de producto</h5>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="" id="productVariant">
                            <label class="form-check-label" for="productVariant">
                                Este artículo tiene múltiples opciones como diferentes tallas, tamaños o colores.
                            </label>
                        </div>
                        <hr>
                        <div id="variantOptions" class="d-none mt-3">
                            <h5>Opciones</h5>
                            <div class="mb-3">
                                <label for="selectVariantOption" class="form-label">Seleccione una opción</label>
                                <div class="d-flex justify-content-start align-items mb-3">
                                    <select class="form-control" aria-label="Default select example" id="selectVariantOption" name="selectVariantOption"></select>
                                    <button onclick ="addVariant()" type="button" class="btn btn-info text-white" id="btnSpc"><i class="fas fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="table-responsive overflow-auto mb-3" style="max-height:50vh">
                                <table class="table align-middle">
                                    <thead>
                                        <th>Variante</th>
                                        <th>Opciones</th>
                                        <th></th>
                                    </thead>
                                    <tbody id="tableVariants"></tbody>
                                </table>
                            </div>
                            <hr class="mb-3">
                            <div  class="table-responsive overflow-auto" style="max-height:50vh">
                                <table class="table align-middle">
                                    <thead>
                                        <th class="text-nowrap">Variante</th>
                                        <th class="text-nowrap">Precio de compra</th>
                                        <th class="text-nowrap">Precio de venta</th>
                                        <th class="text-nowrap">Precio de oferta</th>
                                        <th class="text-nowrap">Stock</th>
                                        <th class="text-nowrap">Código SKU</th>
                                        <th>Mostrar</th>
                                    </thead>
                                    <tbody id="tableCombinations"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab"></div>
                </div>
                <div>
                    <button onclick="save()"type="button" class="btn btn-primary me-2 w-100" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?> 
