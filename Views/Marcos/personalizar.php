<?php
    headerAdmin($data);
?>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <img src="..." class="rounded me-2" alt="..." height="20" width="20">
        <strong class="me-auto" id="toastProduct"></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        Hello, world! This is a toast message.
        </div>
    </div>
</div>
<div class="card mb-4">
    <div class="card-body">
        <a href="<?=base_url()?>/pedidos" class="btn btn-primary"><i class="fas fa-arrow-circle-left"></i> Regresar</a>
    <?=$data['option']?>
    </div>
</div>
<?php
    footerAdmin($data);
?>

