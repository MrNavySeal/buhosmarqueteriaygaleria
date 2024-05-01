<?php headerAdmin($data)?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end">
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <a href="<?=BASE_URL."/productos/producto"?>" class="btn btn-primary d-none" id="btnNew" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
        <?php
        }
        ?>
    </div>
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>ID</th>
                <th>Portada</th>
                <th>Nombre</th>
                <th>Referencia</th>
                <th>Categoria</th>
                <th>Subcategoria</th>
                <th class="text-nowrap">Precio de compra</th>
                <th class="text-nowrap">Precio de venta</th>
                <th class="text-nowrap">Precio de oferta</th>
                <th>Stock</th>
                <th>Producto</th>
                <th>Insumo</th>
                <th class="text-nowrap">Servicio/Receta/Combo</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>         