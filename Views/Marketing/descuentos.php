<?php 
    headerAdmin($data);
    getModal("modalDiscount",$data['categories']);
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="scroll-y">
        <table class="table items align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
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