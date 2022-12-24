<?php 
headerAdmin($data);
?>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <form id="formPage" name="formPage" class="mb-4 mt-4">
                    <div class="mb-3 uploadImg">
                        <img src="<?=$data['page']['picture']?>">
                        <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                        <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                    </div>
                    <input type="hidden" name="id" value=<?=$data['page']['id']?>>
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Título</label>
                        <input type="text" class="form-control" id="txtName" name="txtName" value="<?=$data['page']['name']?>">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statusList" class="form-label">Tipo de página <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="typeList" name="typeList" required>
                                <?=$data['page']['optionT']?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <?=$data['page']['optionS']?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" id="txtDescription" name="txtDescription" rows="5"><?=$data['page']['description']?></textarea>
                    </div>
                    <div class="modal-footer">
                        <a href="<?=base_url()."/paginas"?>" class="btn btn-secondary text-white">Regresar</a>
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="far fa-edit"></i> Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        