<?php 
    $colores = $data['colores'];
    $company = getCompanyInfo();
?>
<div id="modalPoup"></div>
<div id="framePhotos" class="d-none">
    <div class="frame__img__container">
        <img src="<?=media()."/images/uploads/titulo.png"?>" alt="">
        <div class="change__img__container">
            <div class="change__img"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
            <div class="change__img"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
        </div>
    </div>
    <div class="c-p position-absolute bg-color-1 rounded-circle ps-2 pe-2 top-0 end-0 m-3" id="closeImg"><i class="fas fa-times"></i></div>
</div>
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
                        <div class="layout--img"></div>
                        <div class="layout--margin"></div>
                        <div class="layout--border"></div>
                    </div>
                </div>
                <div class="product-image-slider d-none">
                    <div class="slider-btn-left"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
                    <div class="product-image-inner">
                        <div class="product-image-item"><img src="" alt=""></div>
                        <div class="product-image-item"><img src="" alt=""></div>
                        <div class="product-image-item"><img src="" alt=""></div>
                    </div>
                    <div class="slider-btn-right"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
                </div>
            </div>
            <div class="col-md-6 page mb-4">
                <div class="mb-3">
                    <span class="fw-bold">Elige la orientación</span>
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
                    <span class="fw-bold ">Ingresa las dimensiones</span>
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div class="measures--dimension">
                            <label for="">Ancho (cm)</label>
                            <input type="number" class="measures--input" name="intWidth" id="intWidth" value="10" disabled>
                        </div>
                        <div class="measures--dimension">
                            <label for="">Alto (cm)</label>
                            <input type="number" class="measures--input" name="intHeight" id="intHeight" value="10" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 page d-none">
                <div class="mb-3 mt-3">
                    <div class="fw-bold d-flex justify-content-between">
                        <span>Seleccione el tipo moldura</span>
                        <!--<span id="reference"></span>-->
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <select class="form-select" aria-label="Default select example" id="sortFrame">
                            <option value="1">Madera</option>
                            <option value="3">Madera Diseño único</option>
                            <option value="2">Poliestireno</option>
                        </select>
                        <input type="text" class="form-control" placeholder="Buscar" id="searchFrame">
                    </div>
                    <div class="select--frames mt-3">
                        <?=$data['molduras']['data'];?>
                    </div>
                    <div class="mt-3 mb-3" id="frame--color">
                        <div class="fw-bold d-flex justify-content-between">
                            <span>Elige el color del marco</span>
                            <span id="frameColor"></span>
                        </div>
                        <div class="colors mt-3">
                            <?php
                                for ($i=0; $i < count($colores); $i++) { 
                            ?>
                            <div class="colors--item color--frame element--hover" onclick="selectActive(this,'.color--frame')" title="<?=$colores[$i]['name']?>" data-id="<?=$colores[$i]['id']?>">
                                <div style="background-color:#<?=$colores[$i]['color']?>"></div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <span class="fw-bold">Elige el tipo de espejo</span>
                        <select class="form-select mt-3" aria-label="Default select example" id="selectStyle">
                            <option value="1">Espejo 3mm</option>
                            <option value="2">Espejo biselado</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-2 t-color-1 mt-3 totalFrame">$ 0.00</div>
                        <button type="button" class="btn btn-bg-1 mt-2" id="addFrame"><i class="fas fa-shopping-cart"></i> Agregar</button>
                    </div>
                </div>
                <div class="payment__methods mt-3">
                    <p>Pago seguro garantizado</p>
                    <ul>
                        <li><img src="<?=media()?>/images/uploads/icon1.png" alt=""></li>
                        <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon2.png" alt=""></a></li>
                        <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon3.png" alt=""></a></li>
                        <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon4.png" alt=""></a></li>
                        <li><a  target="_blank" href="https://icons8.com"><img src="<?=media()?>/images/uploads/icon5.png" alt=""></a></li>
                    </ul>
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
<section class="mt-3 container">
    <ul class="nav nav-pills mb-3" id="product-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-specification-tab" data-bs-toggle="pill" data-bs-target="#pills-specification" type="button" role="tab" aria-controls="pills-specification" aria-selected="true">Especificaciones</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-tiempo-tab" data-bs-toggle="pill" data-bs-target="#pills-tiempo" type="button" role="tab" aria-controls="pills-tiempo" aria-selected="false">Tiempo y despacho</button>
        </li>
    </ul>
    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-specification" role="tabpanel" aria-labelledby="pills-specification-tab" tabindex="0">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="bg-light w-50" >Referencia</td>
                        <td id="spcReference">N/A</td>
                    </tr>
                    <tr>
                        <td class="bg-light w-50" >Color del marco</td>
                        <td id="spcFrameColor">N/A</td>
                    </tr>
                    <tr>
                        <td class="bg-light w-50" >Material del marco</td>
                        <td id="spcFrameMaterial">Madera</td>
                    </tr>
                    <tr>
                        <td class="bg-light w-50" >Orientación</td>
                        <td id="spcOrientation">N/A</td>
                    </tr>
                    <tr>
                        <td class="bg-light w-50" >Tipo de espejo</td>
                        <td id="spcStyle">Directo</td>
                    </tr>
                    <tr>
                        <td class="bg-light w-50" >Medida Marco</td>
                        <td id="spcMeasureFrame">N/A</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade" id="pills-tiempo" role="tabpanel" aria-labelledby="pills-tiempo-tab" tabindex="0">
            Realizamos envíos con diferentes transportadoras del país, buscando siempre la mejor opción para nuestros clientes, los tiempos pueden variar de 3 días hasta 5 días hábiles según la ciudad o municipio destino, normalmente en ciudades principales las transportadoras entregan máximo en 3 días hábiles.
        </div>
    </div>
</section>
