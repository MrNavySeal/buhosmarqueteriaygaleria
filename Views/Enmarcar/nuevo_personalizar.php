<?php
    headerPage($data);
?>
<div id="modalPoup"></div>
<main class="container mb-3">
    <h1 class="section--title" id="enmarcarTipo"><?=$data['name']?></h1>
    <div>
        <div class="custom--frame mt-3" id="frame">
            <div class="row">
                <div class="col-md-6 mb-4" id="isFrame">
                    <div class="frame">
                        <div class="up-image">
                            <label for="txtImgShow"><i class="fas fa-camera"></i></label>
                            <input type="file" name="txtImgShow" id="txtImgShow" accept="image/*">
                        </div>
                        <div class="zoom">
                            <i class="fas fa-search-minus" id="zoomMinus"></i>
                            <input type="range" class="form-range custom--range" min="10" max="200" value="100" step="10" id="zoomRange">
                            <i class="fas fa-search-plus" id="zoomPlus"></i>
                        </div>
                        <div class="layout">
                            <div class="layout--img">
                                <img src="" alt="">
                            </div>
                            <div class="layout--margin"></div>
                            <div class="layout--border"></div>
                        </div>
                    </div>
                    <p class="mt-3 text-center fw-bold fs-5" id="imgQuality"></p>
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
                    <div class="mb-3" id="isPrint">
                        <span class="fw-bold">Sube una foto</span>
                        <p class="t-color-3">La calidad de la imagen debe ser de al menos 100ppi, asegurate que abajo de tu imagen siempre diga: 
                        <span class="fw-bold text-success">buena calidad</span></p>
                        <div class="mt-3">
                            <input class="form-control" type="file" name="txtPicture" id="txtPicture" accept="image/*">
                        </div>
                    </div>
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
                        <p class="t-color-3">Ingresa las medidas exactas de tu documento/imagen</p>
                        <div class="d-flex flex-wrap justify-content-center align-items-center">
                            <div class="measures--dimension">
                                <label for="">Ancho (cm)</label>
                                <input type="number" class="measures--input" name="intWidth" id="intWidth" value="20">
                            </div>
                            <div class="measures--dimension">
                                <label for="">Alto (cm)</label>
                                <input type="number" class="measures--input" name="intHeight" id="intHeight" value="20">
                            </div>
                        </div>
                    </div>
                </div>
                <!--Selección de molduras-->
                <div class="col-md-6 page d-none">
                    <div class="mb-3 mt-3">
                        <div class="fw-bold d-flex justify-content-between">
                            <span>Seleccione el tipo moldura</span>
                            <!--<span id="reference"></span>-->
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <select class="form-select" aria-label="Default select example" id="sortFrame"></select>
                            <input type="text" class="form-control" placeholder="Buscar" id="searchFrame">
                        </div>
                        <div class="select--frames mt-3"></div>
                        <div class="mt-3 mb-3 d-none" id="frame--color">
                            <div class="fw-bold d-flex justify-content-between">
                                <span>Elige el color del marco</span>
                                <span id="frameColor"></span>
                            </div>
                            <div class="colors mt-3">
                                <div class="colors--item color--frame element--hover"  title="blanco" data-id="1">
                                    <div style="background-color:#fff"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Propiedades-->
                    <div class="mb-3" id="contentProps"></div>
                    <div class="text-center">
                        <div class="fw-bold fs-2 t-color-1 mt-3 totalFrame">$ 0.00</div>
                        <button type="button" class="btn btn-bg-1 mt-2" id="addFrame">Agregar</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-center">
                <a href="#frame" class="btn btn-bg-2 me-1 ms-1 d-none" id="btnBack">Atrás</a>
                <a href="#frame" class="btn btn-bg-2 me-1 ms-1" id="btnNext">Siguiente</a>
            </div>
        </div>
    </div>
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
                            <td class="bg-light w-50" >Estilo de enmarcación</td>
                            <td id="spcStyle">Directo</td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Medida paspartú</td>
                            <td id="spcMeasureP">0cm</td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Medida imagen</td>
                            <td id="spcMeasureImg">10x10</td>
                        </tr>
                        
                        <tr>
                            <td class="bg-light w-50" >Medida Marco</td>
                            <td id="spcMeasureFrame">N/A</td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Color del paspartú</td>
                            <td id="spcColorP">N/A</td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Color del bocel</td>
                            <td id="spcColorB">N/A</td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Tipo de vidrio</td>
                            <td id="spcGlass">Antireflejante</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="tab-pane fade" id="pills-tiempo" role="tabpanel" aria-labelledby="pills-tiempo-tab" tabindex="0">
                Realizamos envíos con diferentes transportadoras del país, buscando siempre la mejor opción para nuestros clientes, los tiempos pueden variar de 3 días hasta 5 días hábiles según la ciudad o municipio destino, normalmente en ciudades principales las transportadoras entregan máximo en 3 días hábiles.
            </div>
        </div>
    </section>
</main>
<?php
    footerPage($data);
?>