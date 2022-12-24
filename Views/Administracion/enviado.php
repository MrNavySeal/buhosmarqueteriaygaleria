<?php headerAdmin($data); ?>

<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div class="container-lg">
        <div class="card">
            <div class="card-body"> 
                <h2 class="fs-5"><?=$data['message']['subject']?></h2>
                <div class="d-flex justify-content-between flex-wrap">
                    <p class="m-0"><?=$data['message']['email']?></p>
                    <p class="m-0"><?=$data['message']['date']?></p>
                </div>
                <hr>
                <label for="" class="fw-bold">Mensaje:</label>
                <p><?=$data['message']['message']?></p>
                <hr>
                <div class="row">
                    <div class="col-12 text-start">
                        <a href="<?=base_url()?>/administracion/correo" class="btn btn-secondary text-white mb-4"><i class="fas fa-arrow-circle-left"></i> Regresar</a>   
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        