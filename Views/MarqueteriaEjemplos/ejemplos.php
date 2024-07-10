<?php 
headerAdmin($data);
getModal("modalFrameExampleView");
if($_SESSION['permitsModule']['w']){
    getModal("modalFrameExample");
    getModal("modalFrameCustom");
    getModal("modalFrameSetExample");
}
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-end">
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
                <th>Foto</th>
                <th>Categoría</th>
                <th>Cliente</th>
                <th>Ubicación</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>Orden</th>
                <th>Cliente Visible</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?> 