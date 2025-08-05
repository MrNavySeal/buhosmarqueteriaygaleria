<?php 
    headerAdmin($data);
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="row">
        <div class="col-md-8 mb-2">
            <button class="btn btn-sm btn-success" id="exportExcel"><i class='fas fa-file-excel'></i> Excel</button>
            <button class="btn btn-sm btn-danger" id="exportPDF"><i class="fas fa-file-pdf"></i> PDF</button>
        </div>
        <div class="col-md-4 d-flex justify-content-end mb-2">
            <div class="">
                <?php
                    if($_SESSION['permitsModule']['w']){
                ?>
                <a class="btn btn-primary" href="<?=base_url()."/InventarioAjuste/Ajuste"?>" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="mb-3">
                <label for="perPage" class="form-label">Por página</label>
                <select class="form-control" aria-label="Default select example" id="perPage" name="perPage">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="1000">1000</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="txtInitialDate" class="form-label">Fecha inicial</label>
                <input type="date" class="form-control" id="txtInitialDate" name="txtInitialDate">
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="txtFinalDate" class="form-label">Fecha final</label>
                <input type="date" class="form-control" id="txtFinalDate" name="txtFinalDate">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="txtSearch" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="txtSearch" name="txtSearch">
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height:45vh">
        <table class="table align-middle" >
            <thead class="text-center">
                <tr>
                    <th>Nro ajuste</th>
                    <th>Responsable</th>
                    <th>Fecha</th>
                    <th>Referencia</th>
                    <th>Artículo</th>
                    <th>Actual</th>
                    <th>Costo</th>
                    <th>Tipo</th>
                    <th>Ajuste</th>
                    <th>Resultado</th>
                    <th>Valor ajuste</th>
                </tr>
            </thead>
            <tbody id="tableData"></tbody>
        </table>
    </div>
    <p id="totalRecords" class="text-center m-0 mb-1"><strong>Total de registros: </strong> 0</p>
    <nav aria-label="Page navigation example" class="d-flex justify-content-center w-100">
        <ul class="pagination" id="pagination">
            <li class="page-item">
                <a class="page-link text-secondary" href="#" aria-label="Next">
                    <span aria-hidden="true"><i class="fas fa-angle-double-left"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link text-secondary" href="#" aria-label="Previous">
                    <span aria-hidden="true"><i class="fas fa-angle-left"></i></span>
                </a>
            </li>
            <li class="page-item"><a class="page-link text-secondary" href="#">1</a></li>
            <li class="page-item"><a class="page-link text-secondary" href="#">2</a></li>
            <li class="page-item"><a class="page-link text-secondary" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link text-secondary" href="#" aria-label="Next">
                    <span aria-hidden="true"><i class="fas fa-angle-right"></i></span>
                </a>
            </li>
            <li class="page-item">
                <a class="page-link text-secondary" href="#" aria-label="Next">
                    <span aria-hidden="true"><i class="fas fa-angle-double-right"></i></span>
                </a>
            </li>
        </ul>
    </nav>
</div>
<?php footerAdmin($data)?> 