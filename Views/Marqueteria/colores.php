<?php 
    headerAdmin($data);
    getModal("modalFrameColors");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>ID</th>
                <th>Portada</th>
                <th>Nombre</th>
                <th>Código hexadecimal</th>
                <th>Orden</th>
                <th>Visible</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>        