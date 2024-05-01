<?php headerAdmin($data)?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <h2 class="text-center"><?=$data['page_title']?></h2>
    <div class="d-flex justify-content-between">
        <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
        <?php
            if($_SESSION['permitsModule']['w']){
        ?>
        <button class="btn btn-primary d-none" type="button" id="btnNew">Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></button>
        <?php
        }
        ?>
    </div>
    <div class="scroll-y">
        <table class="table items align-middle">
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Descuento</th>
                    <th>Estado</th>
                    <th>Fecha de creación</th>
                    <th>Fecha de actualización</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody id="listItem">
                
            </tbody>
        </table>
    </div>
</div>
<?php footerAdmin($data)?>        