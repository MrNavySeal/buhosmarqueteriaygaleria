<?php headerAdmin($data)?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
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
        <div class="col-md-10">
            <div class="mb-3">
                <label for="txtSearch" class="form-label">Buscar</label>
                <div class="d-flex">
                    <input type="text" class="form-control" id="txtSearch" name="txtSearch">
                    <button class="btn btn-primary" onclick="getData()" id="btnGenerate">Generar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="table-responsive" style="max-height:45vh">
        <table class="table align-middle" >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Referencia</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Subcategoria</th>
                    <th>Unidad</th>
                    <th>Stock</th>
                    <th>Costo</th>
                    <th class="text-nowrap">Costo total</th>
                </tr>
            </thead>
            <tbody id="tableData"></tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="fw-bold text-end">Total</td>
                    <td id="totalInventory" class="text-end"></td>
                </tr>
            </tfoot>
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