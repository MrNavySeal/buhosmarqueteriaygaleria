<div class="modal fade" id="modalInventoryAdjustment">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nuevo ajuste de inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="perPageProduct" class="form-label">Por página</label>
                                        <select class="form-control" aria-label="Default select example" id="perPageProduct" name="perPageProduct">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                            <option value="1000">1000</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="mb-3">
                                        <label for="txtSearchProduct" class="form-label">Buscar</label>
                                        <input type="text" class="form-control" id="txtSearchProduct" name="txtSearchProduct">
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
                                        </tr>
                                    </thead>
                                    <tbody id="tableDataProducts"></tbody>
                                </table>
                            </div>
                            <p id="totalRecordsProduct" class="text-center m-0 mb-1"><strong>Total de registros: </strong> 0</p>
                            <nav aria-label="Page navigation example" class="d-flex justify-content-center w-100">
                                <ul class="pagination" id="paginationProduct">
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
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-2">
                                        <label for="statusList" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                            <option value="1">adición</option>
                                            <option value="2">Reducción</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtQtyAdjust" class="form-label">Cantidad a ajustar <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control"  id="txtQtyAdjust" name="txtQtyAdjust" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="txtQtyActual" class="form-label">Cantidad actual</label>
                                        <input type="number" class="form-control" id="txtQtyActual" disabled>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="txtDescription" class="form-label">Observación </label>
                                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>