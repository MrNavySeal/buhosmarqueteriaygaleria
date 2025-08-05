<?php 
headerAdmin($data);
getModal("modalStorage",$data['suppliers']);
?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>ID</th>
                <th>Referencia</th>
                <th>Nombre</th>
                <th>Proveedor</th>
                <th>Precio</th>
                <th>IVA</th>
                <th>Precio IVA</th>
                <th>Precio Total</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>   
<?php footerAdmin($data)?>