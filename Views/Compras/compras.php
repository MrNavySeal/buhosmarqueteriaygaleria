<?php headerAdmin($data)?>
<div id="modalItem"></div>

<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab" aria-controls="purchase" aria-selected="true">Nueva compra</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="navPurchase-tab" data-bs-toggle="tab" data-bs-target="#navPurchase" type="button" role="tab" aria-controls="navPurchase" aria-selected="true">Historial de compras</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="purchase">
                        <div class="row mt-3">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="selectSupplier" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                            <select class="form-control" aria-label="Default select example" id="selectSupplier" name="selectSupplier" required>
                                                <?=$data['proveedores']?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="txtProduct" class="form-label">Producto <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="txtProduct" name="txtProduct">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="intQty" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="intQty" name="intQty">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="intPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="intPrice" name="intPrice">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary" id="btnAddProduct">Agregar</button>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="" class="form-label">Fecha de compra <span class="text-danger">*</span></label>
                                    <input type="date" name="txtDate" id="txtDate" class="form-control">
                                </div>
                                <div class="scroll-y container mb-3 mt-3" id="buyProducts"></div>
                                <p class="fw-bold text-center fs-5">Total: <span id="total"><?=formatNum(0,false)?></span></p>
                                <button type="button" class="btn btn-primary w-100" id="btnPurchase">Procesar</button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navPurchase">
                        <button type="button" class="mt-3 btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                        <div class="row mb-3">
                            <div class="col-md-12 mt-3">
                                <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search" name="search">
                            </div>
                        </div>
                        <div class="scroll-y">
                            <table class="table items align-middle" id="table<?=$data['page_title']?>">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Proveedor</th>
                                        <th>Total</th>
                                        <th>Fecha</th>
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
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        