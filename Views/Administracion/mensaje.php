<?php 
    headerAdmin($data);
?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body"> 
                <h2 class="fs-5"><?=$data['message']['subject']?></h2>
                <div class="d-flex justify-content-between flex-wrap">
                    <p class="m-0"><?=$data['message']['name']." (".$data['message']['email'].", ".$data['message']['phone'].")"?></p>
                    <p class="m-0"><?=$data['message']['date']?></p>
                </div>
                <hr>
                <label for="" class="fw-bold">Mensaje:</label>
                <p><?=$data['message']['message']?></p>
                <hr>
                <?php
                    if(!empty($data['message']['reply'])){
                        
                ?>
                <label for="" class="fw-bold">Respuesta:</label>
                <div class="mb-3">
                    <p class="m-0 mt-2"><?=$data['message']['dateupdated']?></p>
                    <p><?=$data['message']['reply']?></p>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 text-start">
                        <a href="<?=base_url()?>/administracion/correo" class="btn btn-secondary text-white mb-4"><i class="fas fa-arrow-circle-left"></i> Regresar</a>   
                    </div>
                </div>
                <?php }else{?>
                <form id="formReply">
                    <input type="hidden" id="idMessage" name="idMessage" value="<?=$data['message']['id']?>">
                    <input type="hidden" id="txtEmail" name="txtEmail" value="<?=$data['message']['email']?>">
                    <input type="hidden" id="txtName" name="txtName" value="<?=$data['message']['name']?>">
                    <div class="mb-3">
                        <textarea class="form-control" id="txtMessage" name="txtMessage" rows="5" placeholder="Escribe tu respuesta"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 text-start">
                            <a href="<?=base_url()?>/administracion/correo" class="btn btn-secondary text-white mb-4"><i class="fas fa-arrow-circle-left"></i> Regresar</a>   
                        </div>
                        <?php if($_SESSION['permitsModule']['w']){?>
                        <div class="col-6 text-end">
                            <button type="submit" id="btnSubmit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Responder</button>
                        </div>
                        <?php }?>
                    </div>
                </form>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        