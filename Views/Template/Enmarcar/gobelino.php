<?php $company = getCompanyInfo();?>
<div id="modalPoup"></div>
<main class="container mb-3">
<h1 class="section--title" id="enmarcarTipo" data-route="<?=$data['tipo']['route']?>" data-name="<?=$data['tipo']['name']?>" data-id="<?=$data['tipo']['id']?>"><?=$data['tipo']['name']?></h1>
    <div class="custom--frame mt-3" id="frame">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="frame">
                    <div class="up-image">
                        <label for="txtPicture"><i class="fas fa-camera"></i></label>
                        <input type="file" name="txtPicture" id="txtPicture" accept="image/*">
                    </div>
                    <div class="zoom">
                        <i class="fas fa-search-minus" id="zoomMinus"></i>
                        <input type="range" class="form-range custom--range" min="10" max="200" value="100" step="10" id="zoomRange">
                        <i class="fas fa-search-plus" id="zoomPlus"></i>
                    </div>
                    <div class="layout">
                        <div class="layout--img">
                            <img src="<?=media()."/images/uploads/gobelino.jpeg"?>" alt="Enmarcar <?=$data['tipo']['name']?>">
                        </div>
                        <div class="layout--margin"></div>
                    </div>
                </div>
                <!--
                <div class="slider--content d-none">
                    <div class="slider--controls slider--control-left"><i class="fa-solid fa-angle-left"></i></div>
                    <div class="slider--inner">
                        <div class="slider--item">
                            <img src="assets/images/muestra.gif" alt="">
                        </div>
                        <div class="slider--item">
                            <img src="assets/images/muestra.gif" alt="">
                        </div>
                        <div class="slider--item">
                            <img src="assets/images/muestra.gif" alt="">
                        </div>
                    </div>    
                    <div class="slider--controls slider--control-right"><i class="fa-solid fa-angle-right"></i></div>    
                </div>-->
            </div>
            <div class="col-md-6 page mb-4">
                <div class="mb-3">
                    <span class="fw-bold">1. Elige la orientación</span>
                    <div class="d-flex flex-wrap justify-content-center align-items-center mt-3">
                        <div class="orientation element--hover" data-name="horizontal" onclick="selectOrientation(this)">
                            <span>Horizontal</span>
                            <img src="<?=media()?>/images/uploads/horizontal.png" alt="Sentido horizontal">
                        </div>
                        <div class="orientation element--hover" data-name="vertical" onclick="selectOrientation(this)">
                            <span>Vertical</span>
                            <img src="<?=media()?>/images/uploads/vertical.png" alt="Sentido vertical">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <span class="fw-bold ">2. Ingresa las dimensiones</span>
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div class="measures--dimension">
                            <label for="">Ancho (cm)</label>
                            <div class="measures--limits"><span>min 10.0</span><span>max 500.0</span></div>
                            <input type="number" class="measures--input" name="intWidth" id="intWidth" value="10" disabled>
                        </div>
                        <div class="measures--dimension">
                            <label for="">Alto (cm)</label>
                            <div class="measures--limits"><span>min 10.0</span><span>max 500.0</span></div>
                            <input type="number" class="measures--input" name="intHeight" id="intHeight" value="10" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 page d-none">
                <div class="mb-3 mt-3">
                    <div class="fw-bold d-flex justify-content-between">
                        <span>1. Selecciona la moldura</span>
                        <span id="reference"></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <input type="text" class="form-control" placeholder="Buscar" id="searchFrame">
                        <select class="form-select" aria-label="Default select example" id="sortFrame">
                            <option value="1">Todas las molduras</option>
                            <option value="2">Moldura en madera</option>
                            <option value="3">Moldura importada</option>
                        </select>
                    </div>
                    <div class="select--frames row mt-3">
                        <?=$data['molduras']['data'];?>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-2 t-color-1 mt-3 totalFrame">$ 0.00</div>
                        <button type="button" class="btn btn-bg-1 mt-2" id="addFrame">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                        <p class="text-center t-color-3 mt-2">Recuerda enviar tu gobelino a nuestro domicilio <?=$company['addressfull']?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            <a href="#frame" class="btn btn-bg-2 me-1 ms-1 d-none" id="btnBack">Atrás</a>
            <a href="#frame" class="btn btn-bg-2 me-1 ms-1 d-none" id="btnNext">Siguiente</a>
        </div>
    </div>
</main>