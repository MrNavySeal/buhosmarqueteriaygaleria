<?php headerAdmin($data)?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="scroll-y">
        <table class="table items align-middle" id="table<?=$data['page_title']?>">
            <thead>
                    <th>Articulo</th>
                    <th>Fecha de creación</th>
                    <th>Fecha de actualización</th>
                    <th>Estado</th>
                    <th>Opciones</th>
            </thead>
            <tbody id="listItem">
                <?=$data['data']['data']?>
            </tbody>
        </table>
    </div>
</div>   
<?php footerAdmin($data)?> 