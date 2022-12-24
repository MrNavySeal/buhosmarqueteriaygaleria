<?php 
    $colores = $data['colores'];
    $company = getCompanyInfo();
?>
<div id="modalPoup"></div>
<main class="container mb-3">
<h1 class="section--title" id="enmarcarTipo" data-route="<?=$data['tipo']['route']?>" data-name="<?=$data['tipo']['name']?>" data-id="<?=$data['tipo']['id']?>"><?=$data['tipo']['name']?></h1>
    <div class="custom--frame mt-3" id="frame">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="frame">
                    <div class="zoom">
                        <i class="fas fa-search-minus" id="zoomMinus"></i>
                        <input type="range" class="form-range custom--range" min="10" max="200" value="100" step="10" id="zoomRange">
                        <i class="fas fa-search-plus" id="zoomPlus"></i>
                    </div>
                    <div class="layout">
                        <div class="cube-container">
                            <div class="cube">
                                <div class="layout--face face-front">
                                    <img src="<?=media()."/images/uploads/".$company['logo']?>" alt="Enmarcar <?=$data['tipo']['name']?>">
                                </div>
                                <div class="layout--face face-superior"></div>
                                <div class="layout--face face-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <p class="mt-3 text-center fw-bold fs-5" id="imgQuality"></p>
            </div>
            <div class="col-md-6 page mb-4">
                <div class="mb-3">
                    <span class="fw-bold">1. Sube una foto (opcional)</span>
                    <p class="t-color-3">La resolución debe ser de al menos 100ppi. Asegurate que abajo de tu imagen siempre diga: 
                    <span class="fw-bold text-success">buena calidad</span></p>
                    <div class="mt-3">
                        <form id="formPicture">
                            <input class="form-control" type="file" name="txtPicture" id="txtPicture" accept="image/*">
                        </form>
                    </div>
                </div>
                <div class="mb-3">
                    <span class="fw-bold">2. Ingresa las dimensiones</span>
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div class="measures--dimension">
                            <label for="">Ancho (cm)</label>
                            <div class="measures--limits"><span>min 10.0</span><span>max 300.0</span></div>
                            <input type="number" class="measures--input" name="intWidth" id="intWidth" value="10">
                        </div>
                        <div class="measures--dimension">
                            <label for="">Alto (cm)</label>
                            <div class="measures--limits"><span>min 10.0</span><span>max 300.0</span></div>
                            <input type="number" class="measures--input" name="intHeight" id="intHeight" value="10">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="fw-bold d-flex justify-content-between">
                        <span>3. Elige el color del borde</span>
                        <span id="marginColor"></span>
                    </div>
                    <div class="colors mt-3">
                        <?php
                            for ($i=0; $i < count($colores); $i++) { 
                        ?>
                        <div class="colors--item color--margin element--hover" onclick="selectActive(this,'.color--margin')" title="<?=$colores[$i]['name']?>" data-id="<?=$colores[$i]['id']?>">
                            <div style="background-color:#<?=$colores[$i]['color']?>"></div>
                        </div>
                        <?php }?>
                    </div>
                </div>
                <div class="mb-3 d-none retablo">
                    <span class="fw-bold">4. Incluye la impresion de la imagen</span>
                    <select class="form-select mt-3" aria-label="Default select example" id="selectStyle">
                        <option value="1">Con impresion</option>
                        <option value="2">Sin impresión</option>
                    </select>
                </div>
                <div class="text-center">
                    <div class="fw-bold fs-2 t-color-1 mt-3 totalFrame">$ 0.00</div>
                    <div class="d-flex justify-content-center align-items-center flex-wrap mt-3">
                        <div class="btn-qty-1 me-3">
                            <button class="btn" id="btnDecrement"><i class="fas fa-minus"></i></button>
                            <input type="number" name="txtQty" id="txtQty" min="1" max ="99" value="1">
                            <button class="btn" id="btnIncrement"><i class="fas fa-plus"></i></button>
                        </div>
                        <button type="button" class="btn btn-bg-1" id="addFrame">Agregar <i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>