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
<h1 class="section--title" id="enmarcarTipo" data-route="<?=$data['tipo']['route']?>" data-name="<?=$data['tipo']['name']?>" data-id="<?=$data['tipo']['id']?>">Costos <?=$data['tipo']['name']?></h1>
    <div class="custom--frame mt-3" id="frame">
        <div class="row">
            <div class="col-md-6 page mb-4">
                <div class="mb-3">
                    <span class="fw-bold ">Ingresa las dimensiones</span>
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <div class="measures--dimension">
                            <label for="">Ancho (cm)</label>
                            <input type="number" class="measures--input" name="intWidth" id="intWidth" value="20" >
                        </div>
                        <div class="measures--dimension">
                            <label for="">Alto (cm)</label>
                            <input type="number" class="measures--input" name="intHeight" id="intHeight" value="20" >
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
                </div>
                <div class="mb-3">
                    <div class="mb-3">
                        <span class="fw-bold">Elige el tipo de espejo</span>
                        <select class="form-select mt-3" aria-label="Default select example" id="selectStyle">
                            <option value="1">Espejo 3mm</option>
                            <option value="2">Espejo biselado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-4">
                <table class="table table-bordered">
                    <thead>
                        <th class="bg-light fw-bold text-center" colspan="2">Tabla de costos</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="bg-light w-50" >Marco</td>
                            <td id="costMarco"></td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >MDF</td>
                            <td id="costMDF"></td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50" >Espejo</td>
                            <td id="costEspejo"></td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50 fw-bold" >Costo total</td>
                            <td id="costTotal" class="fw-bold text-danger"></td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50 fw-bold" >Precio de venta</td>
                            <td id="price" class="fw-bold text-success"></td>
                        </tr>
                        <tr>
                            <td class="bg-light w-50 fw-bold" >Utilidad neta</td>
                            <td id="utilidad" class="fw-bold text-success"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            <a href="#frame" class="btn btn-bg-2 me-1 ms-1 d-none" id="btnBack">Atrás</a>
            <a href="#frame" class="btn btn-bg-2 me-1 ms-1" id="btnNext">Siguiente</a>
        </div>
    </div>
</main>
