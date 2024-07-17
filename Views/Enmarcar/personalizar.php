<?php
    headerPage($data);
    $arrExamples = $data['examples'];
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
                        <button type="button" class="btn btn-bg-1 mt-2" id="addFrame" onclick="addProduct()"><i class="fas fa-shopping-cart"></i> Agregar</button>
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
                <button class="nav-link " id="pills-specification-tab" data-bs-toggle="pill" data-bs-target="#pills-specification" type="button" role="tab" aria-controls="pills-specification" aria-selected="true">Especificaciones</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-examples-tab" data-bs-toggle="pill" data-bs-target="#pills-examples" type="button" role="tab" aria-controls="pills-examples" aria-selected="true">Ejemplos</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-tiempo-tab" data-bs-toggle="pill" data-bs-target="#pills-tiempo" type="button" role="tab" aria-controls="pills-tiempo" aria-selected="false">Tiempo y despacho</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade " id="pills-specification" role="tabpanel" aria-labelledby="pills-specification-tab" tabindex="0">
                <table class="table table-bordered">
                    <tbody id="tableSpecs"></tbody>
                </table>
            </div>
            <div class="tab-pane fade show active" id="pills-examples" role="tabpanel" aria-labelledby="pills-examples-tab" tabindex="0">
                <div class="row">
                    <?php
                    if(!empty($arrExamples)){
                        foreach ($arrExamples as $e) {
                            $strName = !$e['is_visible'] ? "" :'<p class="mb-0 fw-bold">'.$e['name'].'</p>';
                            $strAddress =$e['address'];
                            $url = media()."/images/uploads/".$e['img'];
                            $arrConfig = array(
                                "frame"=>$e['frame'],
                                "margin"=>$e['margin'],
                                "height"=>$e['height'],
                                "width"=>$e['width'],
                                "orientation"=>$e['orientation'],
                                "color_frame"=>$e['color_frame'],
                                "color_margin"=>$e['color_margin'],
                                "color_border"=>$e['color_border'],
                                "props"=>json_decode($e['props'],true),
                                "type_frame_id"=>$e['type_frame'],
                                "type_frame"=>ucwords(json_decode($e['specs'],true)['detail'][1]['value'])
                            );
                            $objConfig = json_encode($arrConfig,JSON_UNESCAPED_UNICODE);
                    ?>
                    <div class="col-12 col-lg-3 col-md-6" data-id="<?=$e['frame']?>" data-margin="<?=$e['margin']?>" data-height="<?=$e['height']?>"
                    data-width="<?=$e['width']?>" data-orientation="<?=$e['orientation']?>" data-colorframe="<?=$e['color_frame']?>"
                    data-colormargin="<?=$e['color_margin']?>" data-colorborder="<?=$e['color_border']?>" data-typeframe="<?=$e['type_frame']?>"
                    data-props="'<?=$e['props']?>'">
                        <div class="card--product">
                            <div class="card--product-img">
                                <a href="">
                                    <img src="<?=$url?>" alt="<?=$data['name']?>">
                                </a>
                            </div>
                            <div class="card--product-info">
                                <?=$strName?>
                                <p><?=$strAddress?></p>
                            </div>
                            <div class="card--product-btns">
                                <div class="d-flex flex-column">
                                    <a href="#frame" class="btn btn-sm btn-bg-1 mb-2" onclick='copyStyle(<?=$objConfig?>,false)'>Copiar estilos</a>
                                    <a href="#frame" class="btn btn-sm btn-bg-1" onclick='copyStyle(<?=$objConfig?>,true)'>Copiar con medidas</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } }?>
                </div>
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