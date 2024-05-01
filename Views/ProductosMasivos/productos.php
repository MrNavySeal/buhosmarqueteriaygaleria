<?php headerAdmin($data)?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="d-flex align-items-center">
        <a href="<?=base_url()?>/productos" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
        <h2 class="text-center m-0"><?=$data['page_title']?></h2>
    </div>
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
                <a href="<?=base_url()?>/ProductosMasivos/plantilla" class="btn btn-success"> Descargar</a>
            </div>
            <div class="mb-3">
                <h4>Paso 2 - Completar la información</h4>
                <p class="text-secondary">Completar la información siguiendo las instrucciones.</p>
            </div>
            <div class="mb-3">
                <h4>Paso 3 - Subir excel</h4>
                <p class="text-secondary">Sube el excel una vez que hayas terminado de completar la información de los productos.</p>
                <div class="d-flex mb-3 align-items-center">
                    <input class="form-control w-50" type="file" accept=".xlsx" id="formFile">
                    <button type="button" id="btnAdd" class="btn btn-primary text-white"> Cargar archivo</button>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="social" role="tabpanel" aria-labelledby="social-tab"></div>
    </div>
</div>
<?php footerAdmin($data)?> 
