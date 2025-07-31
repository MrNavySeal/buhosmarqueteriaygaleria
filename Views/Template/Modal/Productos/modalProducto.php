<app-modal :title="common.productTitle" id="modalProduct" v-model="common.showModalProduct" size="xl">
    <template #body>
        <app-input label="" type="hidden"  v-model="common.intId"></app-input>
        <ul class="nav nav-pills mt-5 mb-5" id="product-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Características</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab" aria-controls="variants" aria-selected="false">Variantes</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="prices-tab" data-bs-toggle="tab" data-bs-target="#prices" type="button" role="tab" aria-controls="prices" aria-selected="true">Precios al por mayor</button>
            </li>
        </ul>
        <div class="tab-content mb-3" id="myTabContent">
            <div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <div  class="d-flex" style="overflow-x:auto;" id="upload-multiple">
                                <div class="mb-3 upload-images me-3">
                                    <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-images fs-1"></i>
                                            <p class="m-0">Subir imágen</p>
                                        </div>
                                    </label>
                                    <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*" @change="uploadMultipleImage"> 
                                </div>
                                <div class="upload-images d-flex">
                                    <div class="upload-image ms-3" v-for="(data,index) in arrImages" :key="index">
                                        <img :src="data.route">
                                        <div class="deleteImg" @click="delItem('image',data.name)" name="delete">x</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5>Información de artículo</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <app-input label="Nombre" :errors="errors.name" type="text" v-model="strName" required="true"></app-input>
                                    </div>
                                    <div class="col-md-6">
                                        <app-input label="Referencia" type="text" title="Código SKU" v-model="strReference"></app-input>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h5>Descripción de artículo</h5>
                                <app-input label="descripcionCorta" type="text" title="Descripción corta" v-model="strShortDescription"></app-input>
                                <div class="mb-3">
                                    <label for="txtDescription" class="form-label">Descripción </label>
                                    <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-6">
                                <app-button-input 
                                    title="Categorías"
                                    btn="primary" icon="new" 
                                    :value="objCategory.name" 
                                    required="true"
                                    :errors="errors.category"
                                    >
                                    <template #left>
                                        <app-button icon="new" btn="primary" @click="changeCategory('category')"></app-button>
                                    </template>
                                    <template #right>
                                        <app-button icon="delete" btn="danger" @click="delItem('category')"></app-button>
                                    </template>
                                </app-button-input>
                            </div>
                            <div class="col-md-6">
                                <app-button-input 
                                    title="Subcategorias"
                                    btn="primary" icon="new" 
                                    :value="objSubcategory.name" 
                                    required="true"
                                    :errors="errors.subcategory"
                                    >
                                        <template #left>
                                            <app-button icon="new" btn="primary" @click="changeCategory('subcategory')"></app-button>
                                        </template>
                                        <template #right>
                                            <app-button icon="delete" btn="danger" @click="delItem('subcategory')">
                                        </template>
                                    </app-button>
                                </app-button-input>
                            </div>
                        </div>
                        <div class="mb-3">
                            <h5>Tipo de artículo</h5>
                            <app-input label="checkProduct" :errors="errors.product_type" @click="intCheckRecipe=false" title="Seleccione si el artículo es un producto" type="switch" v-model="intCheckProduct"></app-input>
                            <app-input label="checkIngredient" :errors="errors.product_type" @click="intCheckRecipe=false" title="Seleccione si el artículo es un insumo" type="switch" v-model="intCheckIngredient"></app-input>
                            <app-input label="checkRecipe" :errors="errors.product_type" @click="intCheckIngredient=false;intCheckProduct=false;" title="Seleccione si el artículo es una fórmula/servicio/combo" type="switch" v-model="intCheckRecipe"></app-input>
                        </div>
                        <app-select label="unidadMedida" title="Unidad de medida" v-model="intMeasure">
                            <option v-for="(data,index) in arrMeasures" :key="index" :value="data.id">{{data.name}}</option>
                        </app-select>
                        <div class="mb-3" v-if="!intCheckRecipe">
                            <h5>Inventario</h5>
                            <app-input label="checkInventory" title="Seleccione si el artículo maneja inventario" type="switch" v-model="intCheckStock"></app-input>
                            <div class="row" v-if="intCheckStock">
                                <div class="col-md-6">
                                    <app-input label="stock" :errors="errors.stock" type="text" title="Stock" required="true" v-model="intStock"></app-input>
                                </div>
                                <div class="col-md-6">
                                    <app-input label="minStock" :errors="errors.min_stock" type="text" title="Stock mínimo" required="true" v-model="intMinStock"></app-input>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h5>Impuesto</h5>
                            <app-select label="impuesto" title="Seleccione impuesto" required="true" v-model="intTax">
                                <option value="0">Ninguno</option>
                                <option value="19">IVA 19%</option>
                            </app-select>
                        </div>
                        <div>
                            <h5>Precio de artículo</h5>
                            <div class="row">
                                <div class="col-md-4" v-if="!intCheckRecipe">
                                    <app-input label="purchasePrice" type="number" :errors="errors.price_purchase" title="Precio de compra" required="true" v-model="intPurchasePrice"></app-input>
                                </div>
                                <div :class="intCheckRecipe ? 'col-md-6' : 'col-md-4'">
                                    <app-input label="sellPrice" type="number" :errors="errors.price_sell" title="Precio de venta" required="true" v-model="intSellPrice"></app-input>
                                </div>
                                <div :class="intCheckRecipe ? 'col-md-6' : 'col-md-4'">
                                    <app-input label="offerPrice" type="number" :errors="errors.price_offer" title="Precio de oferta" required="true" v-model="intOfferPrice"></app-input>
                                </div>
                            </div>
                        </div>
                        <div>
                            <app-input label="isVisible" title="Habilitar si el artículo es visible en la tienda" type="switch" v-model="intVisible"></app-input>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <app-select label="Modo enmarcar"  v-model="intFraming">
                                        <option value="1">Aplica</option>
                                        <option value="2">No aplica</option>
                                    </app-select>
                                </div>
                                <div class="col-md-6">
                                    <app-select label="Estado"  v-model="intStatus">
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </app-select>
                                </div>
                            </div>
                            <div class="mb-3" v-if="intFraming==1">
                                <h4>Imagen a enmarcar</h4>
                                <div class="uploadImg">
                                    <img :src="strImgUrl">
                                    <label for="strImage"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                                    <input class="d-none" type="file" id="strImage" @change="uploadImagen"  accept="image/*"> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                <p class="text-secondary">Agrega características que quieres resaltar de tu artículo como el material, marca modelo, etc.</p>
                <div v-if="arrSpecs.length == 0">
                    <p class="text-secondary">
                        No tienes ninguna característica creada. 
                        <a href="<?=base_url()."/productos/caracteristicas/"?>" target="_blank" class="btn btn-primary text-white">
                            Crear característica
                        </a>
                    </p>
                </div>
                <app-button-select v-if="arrSpecs.length > 0" label="caracteristica" placeholder="Seleccione" title="Características" v-model="intSpec">
                    <template #options>
                        <option v-for="(data,index) in arrSpecs" :key="index" :value="data.id">{{data.name}}</option>
                    </template>
                    <template #right>
                        <app-button icon="new" btn="primary" @click="addItem('spec')"></app-button>
                    </template>
                </app-button-select>
                <div v-if="arrSpecsAdded.length > 0">
                    <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
                        <table class="table align-middle">
                            <thead>
                                <th>Nombre</th>
                                <th>Valor</th>
                                <th>Opciones</th>
                            </thead>
                            <tbody id="tableSpecs">
                                <tr v-for="(data,index) in arrSpecsAdded" :key="index">
                                    <td data-title="Nombre">{{data.name}}</td>
                                    <td data-title="Valor">
                                        <div><input type="text" class="form-control" v-model="data.value"></div>
                                    </td>
                                    <td>
                                        <div>
                                            <app-button  icon="delete" btn="danger" @click="delItem('spec',data)"></app-button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                <app-input label="checkVariant"  title="Seleccione si el artículo tiene múltiples opciones como diferentes tallas, tamaños o colores" type="switch" v-model="intCheckVariant"></app-input>
                <hr>
                <div class="mt-3" v-if="intCheckVariant">
                    <app-button-select  label="Variantes" :errors="errors.combinations" placeholder="Seleccione" title="Variantes" required="true" v-model="intVariant">
                        <template #options>
                            <option v-for="(data,index) in arrVariants" :key="index" :value="data.id">{{data.name}}</option>
                        </template>
                        <template #right>
                            <app-button icon="new" btn="primary" @click="addItem('variant')"></app-button>
                        </template>
                    </app-button-select>
                    <div v-if="arrVariantsAdded.length > 0">
                        <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
                            <table class="table align-middle">
                                <thead>
                                    <th>Variante</th>
                                    <th>Opciones</th>
                                    <th></th>
                                </thead>
                                <tbody >
                                    <tr v-for="(data,i) in arrVariantsAdded" :key="i">
                                        <td data-title="Variante">{{data.name}}</td>
                                        <td data-title="Opciones">
                                            <div class="d-flex gap-3 flex-wrap align-items-center">
                                                <app-input
                                                    @change="changeVariant()" 
                                                    v-for="(variant,j) in data.options" 
                                                    :label="'checkVariant'+variant.name"  
                                                    :title="variant.name" 
                                                    type="switch"
                                                    v-model="variant.checked">
                                                </app-input>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <app-button  icon="delete" btn="danger" @click="delItem('variant',data)"></app-button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mb-3">
                    <div  v-if="arrCombination.length > 0" class="table-responsive overflow-auto no-more-tables" style="max-height:50vh" id="tableVariantsCombination">
                        <table class="table align-middle">
                            <thead>
                                <th class="text-nowrap">Variante</th>
                                <th class="text-nowrap">Precio de compra</th>
                                <th class="text-nowrap">Precio de venta</th>
                                <th class="text-nowrap">Precio de oferta</th>
                                <th class="text-nowrap d-flex">
                                    <app-input label="checkVariantStock"  title="Stock/Stock mínimo" type="switch" v-model="intCheckStock"></app-input>
                                </th>
                                <th class="text-nowrap">Código SKU</th>
                                <th>Mostrar</th>
                            </thead>
                            <tbody>
                                <tr v-for="(data,index) in arrCombination" :key="index">
                                    <td data-title="Variante">{{data.name}}</td>
                                    <td data-title="Precio de compra"><div><input type="text" class="form-control" v-model="data.price_purchase" ></div></td>
                                    <td data-title="Precio de venta"><div><input type="text" class="form-control" v-model="data.price_sell" ></div></td>
                                    <td data-title="Precio oferta"><div><input type="text" class="form-control" v-model="data.price_offer" ></div></td>
                                    <td data-title="Stock/Stock mínimo">
                                        <div class="d-flex">
                                            <input type="number" class="form-control" v-model="data.stock" :disabled = "intCheckStock ? false : true">
                                            <input type="number" class="form-control" v-model="data.min_stock" :disabled = "intCheckStock ? false : true">
                                        </div>
                                    </td>
                                    <td data-title="Código SKU"><div><input type="text" class="form-control" v-model="data.sku" ></div></td>
                                    <td data-title="Mostrar"><div><app-input :label="'checkVariant'+data.name" type="switch" v-model="data.status"></app-input></div></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="prices" role="tabpanel" aria-labelledby="prices-tab"></div>
        </div>
    </template>
    <template #footer>
        <app-button icon="save" @click="save()" btn="primary" title="Guardar" :disabled="common.processing" :processing="common.processing"></app-button>
    </template>
</app-modal>