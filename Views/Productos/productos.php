<?php headerPage($data)?>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <div class="d-flex justify-content-end">
                    <?php
                        if($_SESSION['permitsModule']['w']){
                    ?>
                    <a href="<?=BASE_URL."/Inventario/producto"?>" class="btn btn-primary d-none" id="btnNew" >Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></a>
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
                            <th>Descuento</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?>         