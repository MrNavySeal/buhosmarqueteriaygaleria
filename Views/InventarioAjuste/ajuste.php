<?php 
    headerAdmin($data);
    getModal("modalPurchaseVariant");
    getModal("modalPurchase");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="d-flex align-items-center mb-4">
        <a href="<?=base_url()?>/InventarioAjuste/ajustes" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
        <h2 class="text-center m-0"><?=$data['page_title']?></h2>
    </div>
    <div class="row">
        <div class="col-md-5">
            <div class="mt-3">
                <div class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="txtSearch" class="form-label">Buscar</label>
                            <input type="text" class="form-control" id="txtSearch" name="txtSearch">
                        </div>
                    </div>
                </div>
                <div class="table-responsive overflow-y" style="max-height:50vh">
                    <table class="table align-middle table-hover">
                        <thead>
                            <tr>
                                <th>Stock</th>
                                <th>Referencia</th>
                                <th>Artículo</th>
                                <th>Costo</th>
                            </tr>
                        </thead>
                        <tbody id="tableProducts"></tbody>
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
        </div>
        <div class="col-md-7">
            <h3 class="bg-primary p-1 mb-0 text-center text-white">Ajuste de inventario</h3>
            <div class="table-responsive overflow-y" style="max-height:50vh">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-nowrap text-center">Artículo</th>
                            <th class="text-nowrap text-center">Actual</th>
                            <th class="text-nowrap text-center">Costo</th>
                            <th class="text-center">Tipo</th>
                            <th class="text-center">Ajuste</th>
                            <th class="text-center">Resultado</th>
                            <th class="text-nowrap text-center">Valor ajuste</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tablePurchase"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-end fw-bold">Total:</td>
                            <td class="text-end" id="totalProducts">$0</td>
                        </tr>
                    </tfoot>
                    </table>
                </table>
            </div>
            <div class="d-flex mt-2">
                <button type="button" class="btn btn-primary w-100" id="btnPurchase">Ajustar</button>
                <button type="button" class="btn btn-danger w-100" id="btnClean">Limpiar</button>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        