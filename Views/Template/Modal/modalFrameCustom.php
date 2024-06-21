
<div class="modal fade" id="modalFrame">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="frameTitle">Enmarcar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="framePhotos" class="d-none">
                    <div class="frame__img__container">
                        <img src="" alt="">
                        <div class="change__img__container">
                            <div class="change__img"><i class="fas fa-angle-left" aria-hidden="true"></i></div>
                            <div class="change__img"><i class="fas fa-angle-right" aria-hidden="true"></i></div>
                        </div>
                    </div>
                    <div class="c-p position-absolute bg-color-1 rounded-circle ps-2 pe-2 top-0 end-0 m-3" id="closeImg"><i class="fas fa-times"></i></div>
                </div>
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
                                    <button type="button" class="btn btn-bg-1 mt-2" id="addFrame"><i class="fas fa-shopping-cart"></i> Agregar</button>
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
            </div>
        </div>
    </div>
</div>