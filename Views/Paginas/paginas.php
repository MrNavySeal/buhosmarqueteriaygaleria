<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                <div class="row mb-3">
                    <div class="col-md-6 mt-3">
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search" name="search">
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center text-end">
                                <span>Ordenar por: </span>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" aria-label="Default select example" id="sortBy" name="sortBy" required>
                                    <option value="1">Más reciente</option>
                                    <option value="2">Más antiguo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="scroll-y">
                    <table class="table items align-middle" id="table<?=$data['page_title']?>">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Fecha de creación</th>
                                <th>Fecha de actualización</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="listItem">
                            <?=$data['data']['data']?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>  
<?php footerPage($data)?>        