<?php 
    headerAdmin($data);
    getModal("modalPurchaseDetail");
    getModal("modalPurchaseAdvance");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end mb-3">
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <a class="btn btn-primary" href="<?=base_url()."/compras/compra"?>" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
        <?php
        }
        ?>
    </div>
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>Nro Factura</th>
                <th>Factura proveedor</th>
                <th>Fecha</th>
                <th>Nombre proveedor</th>
                <th>Atendió</th>
                <th>Método de pago</th>
                <th>Total</th>
                <th>Total pendiente</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>    
<?php footerAdmin($data)?>