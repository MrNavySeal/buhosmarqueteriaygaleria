<?php 
    headerAdmin($data);
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end mb-3">
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <a class="btn btn-primary" href="<?=base_url()."/PedidosPos/venta"?>" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
        <?php
        }
        ?>
    </div>
    <table class="table" id="tableData">
        <thead>
            <tr>
                <th>Nro Factura</th>
                <th>Nro transacción</th>
                <th>Fecha</th>
                <th>CC/NIT</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>         
