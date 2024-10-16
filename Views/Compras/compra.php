<?php 
    headerAdmin($data);
    getModal("modalPurchaseVariant");
    getModal("modalPurchase");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="d-flex align-items-center mb-4">
        <a href="<?=base_url()?>/compras" class="btn btn-primary me-2"><i class="fas fa-arrow-circle-left"></i></a>
        <h2 class="text-center m-0"><?=$data['page_title']?></h2>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="mt-3">
                <input class="form-control" type="search" id="searchProduct" placeholder="Buscar artículo">
                <div class="table-responsive overflow-y" style="max-height:50vh">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Stock</th>
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
                            <th class="text-nowrap">Descuento (%)</th>
                            <th class="text-nowrap">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tablePurchase"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="8" class="text-end fw-bold">Subtotal:</td>
                            <td class="text-end" id="subtotalProducts">$0</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end fw-bold">IVA:</td>
                            <td class="text-end" id="ivaProducts">$0</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end fw-bold">Descuento:</td>
                            <td class="text-end" id="discountProducts">$0</td>
                        </tr>
                        <tr>
                            <td colspan="8" class="text-end fw-bold">Total:</td>
                            <td class="text-end" id="totalProducts">$0</td>
                        </tr>
                    </tfoot>
                    </table>
                </table>
            </div>
            <div class="d-flex mt-2">
                <button type="button" class="btn btn-primary w-100" id="btnPurchase">Comprar</button>
                <button type="button" class="btn btn-danger w-100" id="btnClean">Limpiar</button>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        