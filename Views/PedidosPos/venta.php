<?php 
headerAdmin($data);
$tipos = $data['tipos'];
$total = 0;
$active="d-none";

if($_SESSION['permitsModule']['w']){
    getModal("modalOrder");
    getModal("modalPurchaseVariant");
}

?>
<div id="modalItem"></div>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <img src="..." class="rounded me-2" alt="..." height="20" width="20">
        <strong class="me-auto" id="toastProduct"></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        Hello, world! This is a toast message.
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <?php if($_SESSION['permitsModule']['w']){?>
        <ul class="nav nav-pills mb-3" id="product-tab">
            <li class="nav-item">
                <button class="nav-link active" id="navEnmarcar-tab" data-bs-toggle="tab" data-bs-target="#navEnmarcar" type="button" role="tab" aria-controls="navEnmarcar" aria-selected="true">Enmarcar</button>
            </li>
            <li class="nav-item">
                <button class="nav-link " id="navTienda-tab" data-bs-toggle="tab" data-bs-target="#navTienda" type="button" role="tab" aria-controls="navTienda" aria-selected="true">Tienda</button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="navOtros-tab" data-bs-toggle="tab" data-bs-target="#navOtros" type="button" role="tab" aria-controls="navOtros" aria-selected="true">Otros</button>
            </li>
        </ul>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navEnmarcar">
                        <div class="row">
                            <?php
                                for ($i=0; $i < count($tipos); $i++) { 
                                    $url = base_url()."/marcos/personalizar/".$tipos[$i]['route'];
                                    //$img = media()."/images/uploads/".$tipos[$i]['image'];
                            ?>
                            <div class="col-6 col-lg-4">
                                <div class="card--product">
                                    <a href="<?=$url?>" class="t-color-2">
                                        <div class="card--product-info mt-3">
                                            <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="tab-pane fade " id="navTienda">
                        <div class="mt-3">
                            <table class="table align-middle" id="tableData">
                                <thead>
                                    <tr>
                                        <th>Portada</th>
                                        <th>Stock</th>
                                        <th>Artículo</th>
                                        <th>Precio</th>
                                        <th>Opciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="navOtros">
                        <div class="mt-3">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Descripción del servicio</label>
                                <textarea rows="4" class="form-control" id="txtService" name="txtService"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Cantidad</label>
                                        <input type="number" class="form-control" id="intQty" name="intQty">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exampleFormControlInput1" class="form-label">Precio</label>
                                        <input type="number" class="form-control" id="intPrice" name="intPrice">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary" onclick="addProduct(null,3)"><i class="fas fa-plus"></i> Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <h3 class="bg-primary p-1 mb-0 text-center text-white">Atendiendo</h3>
                <div class="table-responsive overflow-y" style="max-height:50vh">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-nowrap">Stock</th>
                                <th class="text-nowrap">Artículo</th>
                                <th class="text-nowrap">Cantidad</th>
                                <th class="text-nowrap">Precio</th>
                                <th class="text-nowrap">Oferta</th>
                                <th class="text-nowrap">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tablePurchase"></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end" id="subtotalProducts">$0</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Descuento:</td>
                                <td class="text-end" id="discountProducts">$0</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="text-end fw-bold">Total:</td>
                                <td class="text-end" id="totalProducts">$0</td>
                            </tr>
                        </tfoot>
                        </table>
                    </table>
                </div>
                <div class="d-flex mt-2">
                    <button type="button" class="btn btn-primary w-100" id="btnPurchase">Pagar</button>
                    <button type="button" class="btn btn-danger w-100" id="btnClean">Limpiar</button>
                </div>
            </div>
        </div> 
    <?php }?>
</div>
<?php footerAdmin($data)?>        