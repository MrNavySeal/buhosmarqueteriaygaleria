<?php 
    headerAdmin($data);
    getModal("modalFrame");
    getModal("modalViewFrame");
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <table class="table align-middle" id="tableData">
        <thead>
            <tr>
                <th>Portada</th>
                <th>Referencia</th>
                <th>Tipo</th>
                <th>Desperdicio</th>
                <th>Costo</th>
                <th>Descuento</th>
                <th>Estado</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>
<?php footerAdmin($data)?>    