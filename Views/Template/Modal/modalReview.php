<div class="modal fade" id="modalReview">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="d-flex justify-content-end">
                <button type="button" class="btn-close p-2" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="container">
                <div class="d-flex justify-content-center mb-3">
                    <form id="formReview" class="w-100">
                        <input type="hidden" name="intRate" id="intRate" value="0">
                        <input type="hidden" name="idProduct" id="idProduct" value="<?=openssl_encrypt($data,METHOD,KEY)?>">
                        <h3 class="mb-3 text-center">Comparta su opinión</h3>
                        <p class="text-center t-color-2">Califique el producto <span class="text-danger">*</span></p>
                        <div class="d-flex justify-content-center">
                            <div class="review-rate mb-3">
                                <button type="button" class="starBtn"><i class="far fa-star"></i></button>
                                <button type="button" class="starBtn"><i class="far fa-star"></i></button>
                                <button type="button" class="starBtn"><i class="far fa-star"></i></button>
                                <button type="button" class="starBtn"><i class="far fa-star"></i></button>
                                <button type="button" class="starBtn"><i class="far fa-star"></i></button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <p class="t-color-2">Escriba su opinión <span class="text-danger">*</span></p>
                            <textarea class="form-control" id="txtReview" name="txtReview" rows="5" placeholder="Escriba su opinión"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="addReview" class="btn btn-bg-1">Publicar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>