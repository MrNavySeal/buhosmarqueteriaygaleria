<?php 
    headerPage($data);
    if($_SESSION['permitsModule']['u']){
        getModal("modalOrder");
    }
?>
<main class="container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <div class="d-flex justify-content-between">
                    <?php
                        if($_SESSION['permitsModule']['w']){
                    ?>
                    <button class="btn btn-primary d-none" type="button" id="btnNew">Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></button>
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
                            <th>Monto</th>
                            <th>Tipo de pago</th>
                            <th>Estado de pago</th>
                            <th>Estado de pedido</th>
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
