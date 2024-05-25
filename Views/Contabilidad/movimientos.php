<?php 
headerAdmin($data);
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="row">
        <div class="col-md-4 mb-2">
            <h3>Resumen</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Método de pago</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="tableResume">

                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-end fw-bold">Total:</td>
                        <td id="total"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="col-md-8">
            <h3>Detalle</h3>
            <table class="table align-middle" id="tableData">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Categoría</th>
                        <th>Concepto</th>
                        <th>Fecha</th>
                        <th>Método de pago</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>         