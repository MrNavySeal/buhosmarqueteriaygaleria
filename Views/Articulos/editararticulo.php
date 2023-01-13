<?php 
    headerAdmin($data);
    $article = $data['article'];
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card mb-4">
            <div class="card-body">
                <form id="formItem" name="formItem" class="mb-4">  
                    <input type="hidden" id="idArticle" name="idArticle" value="<?=$article['idarticle']?>">
                    <div class="mb-3 uploadImg">
                        <img src="<?=$article['picture']?>">
                        <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                        <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                    </div>
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Título <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtName" name="txtName" value="<?=$article['name']?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="txtShortDescription" class="form-label">Descripción corta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="txtShortDescription" name="txtShortDescription" value="<?=$article['shortdescription']?>" placeholder="Max 140 carácteres" required></input>
                    </div>
                    <div class="mb-3">
                        <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                            <?php
                                $html="";
                                if($article['status'] == 1){
                                    $html ='<option value="1" selected>Activo</option><option value="2">Inactivo</option>';
                                }else{
                                    $html ='<option value="1" >Activo</option><option value="2" selected>Inactivo</option>';
                                }
                            ?>
                            <?=$html?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="txtDescription" class="form-label">Descripcion <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"><?=$article['description']?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary me-2" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                            <a href="<?=BASE_URL?>/articulos/articulos" class="btn btn-secondary text-white"> Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php footerAdmin($data)?>     
</script> 
