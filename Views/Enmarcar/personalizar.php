<?php
    headerPage($data);
    $arrExamples = $data['examples'];
?>
<div id="modalPoup"></div>
<main class="m-3">
    <h1 class="section--title" id="enmarcarTipo"><?=$data['name']?></h1>
    <div class="row">
        <div class="col-md-7" id="isFrame">
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
        <div class="col-md-5">
            <ul class="nav nav-pills mb-3" id="product-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-frame-tab" data-bs-toggle="pill" data-bs-target="#pills-frame" type="button" role="tab" aria-controls="pills-frame" aria-selected="true">Personalizar</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-specs-tab" data-bs-toggle="pill" data-bs-target="#pills-specs" type="button" role="tab" aria-controls="pills-specs" aria-selected="true">Especificaciones</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-cost-tab" data-bs-toggle="pill" data-bs-target="#pills-cost" type="button" role="tab" aria-controls="pills-cost" aria-selected="true">Ejemplos</button>
                </li>
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-discount-tab" data-bs-toggle="pill" data-bs-target="#pills-discount" type="button" role="tab" aria-controls="pills-discount" aria-selected="true">Al por mayor</button>
                </li> -->
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="pills-frame" role="tabpanel" aria-labelledby="pills-frame-tab" tabindex="0">
                    <div class="mb-3" id="isPrint">
                        <span class="fw-bold">Sube una foto</span>
                        <p class="t-color-3">La calidad de la imagen debe ser de al menos 100ppi, asegurate que abajo de tu imagen siempre diga: 
                        <span class="fw-bold text-success">buena calidad</span></p>
                        <div class="mt-3">
                            <input class="form-control" type="file" name="txtPicture" id="txtPicture" accept="image/*">
                        </div>
                    </div>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-dimensions" aria-expanded="false" aria-controls="flush-dimensions">
                                    1. Dimensiones y orientación
                                </button>
                            </h2>
                            <div id="flush-dimensions" class="accordion-collapse collapse show" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body ">
                                    <div class="border rounded p-2">
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-center gap-2 align-items-center mt-3">
                                                <div class="orientation element--hover w-100" data-name="horizontal" onclick="selectOrientation(this,'horizontal')">
                                                    <span>Horizontal</span>
                                                </div>
                                                <div class="orientation element--hover w-100" data-name="vertical" onclick="selectOrientation(this,'vertical')">
                                                    <span>Vertical</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3 d-flex justify-content-center gap-2 align-items-center">
                                            <div class="measures--dimension">
                                                <label for="">Ancho (cm)</label>
                                                <input type="number" class="measures--input" name="intWidth" id="intWidth" value="20">
                                            </div>
                                            <div class="measures--dimension">
                                                <label for="">Alto (cm)</label>
                                                <input type="number" class="measures--input " name="intHeight" id="intHeight" value="20">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-frame" aria-expanded="false" aria-controls="flush-frame">
                                    2. Moldura
                                </button>
                            </h2>
                            <div id="flush-frame" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="border rounded p-2">
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
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#flush-options" aria-expanded="false" aria-controls="flush-options">
                                    3. Opciones de presentación
                                </button>
                            </h2>
                            <div id="flush-options" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body ">
                                    <div class="border rounded p-2">
                                        <div class="mb-3" id="contentProps"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-specs" role="tabpanel" aria-labelledby="pills-specs-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody id="tableSpecs"></tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="pills-cost" role="tabpanel" aria-labelledby="pills-cost-tab" tabindex="0">
                    <div class="slider-examples owl-carousel owl-theme">
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
                            <div  data-id="<?=$e['frame']?>" data-margin="<?=$e['margin']?>" data-height="<?=$e['height']?>"
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
                                            <a href="#frame" class="btn btn-sm btn-bg-2 mb-2" onclick='copyStyle(<?=$objConfig?>,false)'>Copiar estilos</a>
                                            <a href="#frame" class="btn btn-sm btn-bg-2" onclick='copyStyle(<?=$objConfig?>,true)'>Copiar con medidas</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } }?>

                    </div>
                </div>
                <div class="tab-pane fade" id="pills-discount" role="tabpanel" aria-labelledby="pills-discount-tab" tabindex="0">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">Cantidad mínima</th>
                                <th class="text-center">Cantidad máxima</th>
                                <th class="text-center">Descuento (%)</th>
                                <th class="text-center">Valor</th>
                            </tr>
                        </thead>
                        <tbody id="tableFrameDiscounts">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="text-center d-flex justify-content-center gap-3 align-items-bottom mt-4 border rounded p-2">
                <div class="d-flex gap-2 align-items-center justify-content-end">
                    <span class="text-start fw-bold">Precio:</span>
                    <div class="fw-bold fs-2 t-color-1 totalFrame">$ 0.00</div>
                </div>
                    <button type="button" class="btn btn-bg-1 mt-2" id="addFrame" onclick="addProduct()"><i class="fas fa-shopping-cart"></i> Agregar</button>
            </div>
        </div>
    </div>
</main>
<?php
    footerPage($data);
?>