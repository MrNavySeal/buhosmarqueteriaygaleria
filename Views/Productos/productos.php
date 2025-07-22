<?php headerAdmin($data)?>
<div class="mt-3">
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
                <input type="text" class="form-control" id="txtSearch" name="txtSearch">
            </div>
        </div>
    </div>
    <div class="table-responsive overflow-y no-more-tables" style="max-height:50vh">
        <table class="table align-middle table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Portada</th>
                    <th>Nombre</th>
                    <th>Referencia</th>
                    <th>Categoria</th>
                    <th>Subcategoria</th>
                    <th class="text-nowrap">Precio de compra</th>
                    <th class="text-nowrap">Precio de venta</th>
                    <th class="text-nowrap">Precio de oferta</th>
                    <th>Stock</th>
                    <th>Producto</th>
                    <th>Insumo</th>
                    <th class="text-nowrap">Servicio/Receta/Combo</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Opciones</th>
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