<?php 
    headerAdmin($data);
    getModal("modalVariant");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end mb-3">
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <button class="btn btn-primary d-none" type="button" id="btnNew">Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></button>
        <?php
        }
        ?>
    </div>
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>         