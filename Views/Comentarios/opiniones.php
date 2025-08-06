<?php headerAdmin($data)?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="row mb-3">
        <div class="col-md-6 mt-3">
            <div class="row">
                <div class="col-md-3 d-flex align-items-center text-end">
                    <span>Ordenar por: </span>
                </div>
                <div class="col-md-9">
                    <select class="form-control" aria-label="Default select example" id="sortBy" name="sortBy" required>
                        <option value="1">Más recientes</option>
                        <option value="2">Aprobados</option>
                        <option value="3">Rechazados</option>
                        <option value="4">En espera</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="scroll-y">
        <table class="table items align-middle" id="table<?=$data['page_title']?>">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Usuario</th>
                    <th>Calificación</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody id="listItem">
                <?=$data['data']['data']?>
            </tbody>
        </table>
    </div>
</div> 
<?php footerAdmin($data)?>        