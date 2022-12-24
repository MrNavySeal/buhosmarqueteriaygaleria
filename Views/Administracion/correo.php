<?php 
    headerAdmin($data);
    $inbox = array();
    $sent = $data['sent'];
    $total ="";
    if($data['inbox']['status']){
        $inbox = $data['inbox'];
        $total ='('.$inbox['total'].')';
        if($inbox['total'] != 0){
            $total = '<span class="badge bg-danger">'.$inbox['total'].'</span>';
        }
    }
?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="new-tab" data-bs-toggle="tab" data-bs-target="#new" type="button" role="tab" aria-controls="new" aria-selected="true">Enviar mensaje</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="inbox-tab" data-bs-toggle="tab" data-bs-target="#inbox" type="button" role="tab" aria-controls="inbox" aria-selected="true">Bandeja de entrada <?=$total?></button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sent-tab" data-bs-toggle="tab" data-bs-target="#sent" type="button" role="tab" aria-controls="sent" aria-selected="false">Enviados</button>
                    </li>
                </ul>
                
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade" id="new" role="tabpanel" aria-labelledby="new-tab">
                        <form id="formEmail" name="formEmail" class="mb-4 mt-4">
                            <div class="mb-3">
                                <label for="txtEmail" class="form-label">Para: <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="txtEmail" name="txtEmail" value="" placeholder="Escribe un correo" required>
                            </div>
                            <div class="mb-3">
                                <label for="txtEmailCC" class="form-label">CC:</label>
                                <input type="email" class="form-control" id="txtEmailCC" name="txtEmailCC" placeholder="Escribe un correo" value="">
                            </div>
                            <div class="mb-3">
                                <label for="txtSubject" class="form-label">Asunto:</label>
                                <input type="text" class="form-control" id="txtSubject" name="txtSubject" placeholder="Escribe un asunto" value="">
                            </div>
                            <div class="mb-3">
                                <label for="txtMessage" class="form-label">Mensaje: <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="txtMessage" name="txtMessage" rows="5" placeholder="Escribe tu mensaje"></textarea>
                            </div>
                            <?php if($_SESSION['permitsModule']['w']){?>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-paper-plane"></i> Enviar</button>
                            </div>
                            <?php }?>
                        </form>
                    </div>
                    <div class="tab-pane show active" id="inbox" role="tabpanel" aria-labelledby="inbox-tab">
                        <div class="scroll-y">
                            <?php if(isset($inbox['total'])){?>
                                <?=$inbox['data']?>
                            <?php }else{?>
                                <div class="mail-item d-flex justify-content-center align-items-center">
                                    <p class="m-0">No hay datos</p>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="sent" role="tabpanel" aria-labelledby="sent-tab">
                        <div class="scroll-y">
                            <?php if($sent['status']){?>
                                <?=$sent['data']?>
                            <?php }else{?>
                                <div class="mail-item d-flex justify-content-center align-items-center">
                                    <p class="m-0"><?=$sent['msg']?></p>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        