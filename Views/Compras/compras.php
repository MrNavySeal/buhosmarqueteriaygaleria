<?php headerPage($data)?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <ul class="nav nav-pills" id="product-tab">
                    <li class="nav-item">
                        <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab" aria-controls="purchase" aria-selected="true">Nueva compra</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="navPurchase-tab" data-bs-toggle="tab" data-bs-target="#navPurchase" type="button" role="tab" aria-controls="navPurchase" aria-selected="true">Historial de compras</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="purchase">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mt-4 mb-3">
                                    <label for="txtDate" class="form-label">Fecha de compra</label>
                                    <input type="date" name="txtDate" id="txtDate" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-4 mb-3">
                                    <label for="selectSupplier" class="form-label">Proveedor <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="selectSupplier" name="selectSupplier" required>
                                        <?=$data['proveedores']?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mt-4 mb-3">
                                    <label for="selectType" class="form-label">Tipo de producto <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="selectType" name="selectType" required>
                                        <option value ="1" selected>Simple</option>
                                        <option value ="2">Variante</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="setSimple">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="selectProduct" class="form-label">Producto <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="selectProduct" name="selectProduct" required>
                                        <option value ="0">Seleccione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="intQty" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="intQty" name="intQty" placeholder="Cantidad">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="intDiscount" class="form-label">Descuento (%)</label>
                                    <input type="number" class="form-control" id="intDiscount" name="intDiscount">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button type="button" class="btn btn-primary w-100 mt-3 btnAdd" disabled onclick="addProduct()">Agregar</button>
                            </div>
                        </div>
                        <div class="row d-none" id="setCustom">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="customProduct" class="form-label">Producto <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="customProduct" name="customProduct" placeholder="Nombre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="customQty" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="customQty" name="customQty" placeholder="Cantidad">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="customPrice" class="form-label">Precio <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="customPrice" name="customPrice" placeholder="Precio">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-center">
                                <button type="button" class="btn btn-primary w-100 mt-3" onclick="addProduct()">Agregar</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr class="fw-bold">
                                        <th>Referencia</th>
                                        <th>Descripcion</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>IVA</th>
                                        <th>Precio IVA</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="listProducts"></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-end">Subtotal:</th>
                                        <td class="text-start" id="txtSubtotal"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Descuento:</th>
                                        <td class="text-start" id="txtDiscount"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">IVA:</th>
                                        <td class="text-start" id="txtIva"></td>
                                    </tr>
                                    <tr>
                                        <th colspan="6" class="text-end">Total:</th>
                                        <td class="text-start" id="txtTotal"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2" id="btnPurchase"><i class="fas fa-save"></i> Guardar compra</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navPurchase">
                        <table class="table align-middle" id="tableData">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Proveedor</th>
                                    <th>Total</th>
                                    <th>Fecha</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?>        