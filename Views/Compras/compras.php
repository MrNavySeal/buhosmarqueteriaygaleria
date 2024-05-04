<?php 
    headerAdmin($data);
    getModal("modalPurchaseVariant");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="d-flex align-items-center mb-4">
        <a href="<?=base_url()?>/compras" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
        <h2 class="text-center m-0"><?=$data['page_title']?></h2>
    </div>
    <div class="row">
        <div class="col-md-4">
            <ul class="nav nav-pills" id="product-tab">
                <li class="nav-item">
                    <button class="nav-link active" id="purchase-tab" data-bs-toggle="tab" data-bs-target="#purchase" type="button" role="tab" aria-controls="purchase" aria-selected="true">Artículos</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="navPurchase-tab" data-bs-toggle="tab" data-bs-target="#navPurchase" type="button" role="tab" aria-controls="navPurchase" aria-selected="true">Otros</button>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="purchase">
                    <div class="mt-3">
                        <input class="form-control" type="search" id="searchProduct" placeholder="Buscar artículo">
                        <div class="table-responsive overflow-y" style="max-height:50vh">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Stock</th>
                                        <th>Referencia</th>
                                        <th>Artículo</th>
                                        <th>Costo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="tableProducts"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navPurchase"></div>
            </div>
        </div>
        <div class="col-md-8">
            <h3 class="bg-primary p-1 mb-0 text-center text-white">Información de la compra</h3>
            <div class="table-responsive overflow-y" style="max-height:50vh">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-nowrap">Stock</th>
                            <th class="text-nowrap">Artículo</th>
                            <th class="text-nowrap">Cantidad</th>
                            <th class="text-nowrap">Valor base</th>
                            <th class="text-nowrap">IVA</th>
                            <th class="text-nowrap">Valor compra</th>
                            <th class="text-nowrap">Valor venta</th>
                            <th class="text-nowrap">Descuento</th>
                            <th class="text-nowrap">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tablePurchase"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        