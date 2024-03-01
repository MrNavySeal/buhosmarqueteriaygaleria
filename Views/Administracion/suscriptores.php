<?php 
    headerPage($data);
    $subscribers = $data['subscribers'];
?>
<div id="modalItem"></div>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                <div class="scroll-y">
                    <table class="table text-center items align-middle" id="table<?=$data['page_title']?>">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody id="listItem">
                            <?php 
                                if(!empty($subscribers)){

                                for ($i=0; $i < count($subscribers); $i++) { 
                            ?>
                                <tr class="item">
                                    <td data-label="Correo: "><?=$subscribers[$i]['email']?></td>
                                    <td data-label="Fecha: "><?=$subscribers[$i]['date']?></td>
                                </tr>
                            <?php } }else{?>
                                <tr>
                                    <td colspan=2>No hay datos</td>
                                </tr>
                            <?php }?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>  
<?php footerPage($data)?>        