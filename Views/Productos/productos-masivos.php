<?php 
    headerAdmin($data);
    getModal("Paginacion/modalPaginacionCategorias");
    getModal("Paginacion/modalPaginacionSubcategorias");
?>
<div id="modalItem"></div>
<ul class="nav nav-pills mt-5 mb-5" id="product-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab" aria-controls="info" aria-selected="true">Crear</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab" aria-controls="social" aria-selected="false">Editar</button>
    </li>
</ul>
<div class="tab-content mb-3" id="myTabContent">
    <div class="tab-pane show active" id="info" role="tabpanel" aria-labelledby="info-tab">
        <div class="mb-3">
            <h4>Paso 1 - Descargar excel</h4>
            <p class="text-secondary">Descarga nuestra plantilla de excel</p>
            <app-button btn="success" title="Descargar" @click="download()"></app-button>
        </div>
        <div class="mb-3">
            <h4>Paso 2 - Completar la información</h4>
            <p class="text-secondary">Completar la información siguiendo las instrucciones.</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <h4>Paso 3 - Subir excel</h4>
                    <p class="text-secondary">Sube el excel una vez que hayas terminado de editar la información de los productos.</p>
                    <div class="d-flex mb-3 align-items-center">
                        <input class="form-control" type="file" accept=".xlsx" @change="setFile">
                        <app-button btn="primary" title="Cargar" @click="uploadFile(1)" :disabled="category.processing" :processing="category.processing"></app-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab">
        <div class="mb-3">
            <h4>Paso 1 - Seleccionar artículos</h4>
            <p class="text-secondary">Seleccione los artículos por categoria o todos</p>
        </div>
        <div class="row">
            <div class="col-md-4">
                <app-button-input 
                    title="Categorías"
                    :errors="category.errors.category"
                    btn="primary" icon="new" 
                    :value="objCategory.name" 
                    >
                    <template #left>
                        <app-button icon="new" btn="primary" @click="changeCategory()"></app-button>
                    </template>
                    <template #right>
                        <app-button icon="delete" btn="danger" @click="del()"></app-button>
                    </template>
                </app-button-input>
            </div>
            <div class="col-md-4" v-if="objCategory.id != ''">
                <app-button-input 
                    title="Subcategorias"
                    :errors="subcategory.errors.category"
                    btn="primary" icon="new" 
                    :value="objSubcategory.name" 
                    >
                        <template #left>
                            <app-button icon="new" btn="primary" @click="changeCategory('subcategory')"></app-button>
                        </template>
                        <template #right>
                            <app-button icon="delete" btn="danger" @click="del('subcategory')">
                        </template>
                    </app-button>
                </app-button-input>
            </div>
        </div>
        <div class="mb-3">
            <h4>Paso 2 - Descargar excel</h4>
            <p class="text-secondary">Descarga nuestra plantilla de excel</p>
            <app-button btn="success" title="Descargar" @click="download('edit')"></app-button>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <h4>Paso 3 - Subir excel</h4>
                    <p class="text-secondary">Sube el excel una vez que hayas terminado de editar la información de los productos.</p>
                    <div class="d-flex mb-3 align-items-center">
                        <input class="form-control" type="file" accept=".xlsx" @change="setFile">
                        <app-button btn="primary" title="Cargar" @click="uploadFile(2)" :disabled="category.processing" :processing="category.processing"></app-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?> 
