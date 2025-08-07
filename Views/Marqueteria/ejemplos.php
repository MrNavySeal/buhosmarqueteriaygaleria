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