<?php 
    headerAdmin($data);
    if($_SESSION['permitsModule']['w']){
        getModal("modalPurchaseVariant");
    }
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>

    <div class="row">
            <div class="col-md-6 mb-3">
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
            <div class="col-md-6">
                <h3 class="bg-primary p-1 mb-0 text-center text-white">Insumos relacionados</h3>
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
</div>
<?php footerAdmin($data)?>   