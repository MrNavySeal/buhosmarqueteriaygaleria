<?php 
    headerAdmin($data);
    if($_SESSION['permitsModule']['u']){
        getModal("modalOrderAdvance");
        getModal("modalOrderEdit");
    }
    getModal("modalOrderDetail");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end mb-3">
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <a class="btn btn-primary" href="<?=base_url()."//PedidosPos/venta"?>" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
        <?php
        }
        ?>
    </div>
    <table class="table" id="tableData">
        <thead>
            <tr>
                <th>Id</th>
                <th>Transacción</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Teléfono</th>
                <th>CC/NIT</th>
                <th>Método de pago</th>
                <th>Total</th>
                <th>Total pendiente</th>
                <th>Estado de pago</th>
                <th>Estado de pedido</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>         
