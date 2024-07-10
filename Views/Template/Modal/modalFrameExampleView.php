<div class="modal fade" id="modalElementView">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleFrameView">Datos de enmarcación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <img class="img-fluid" src="<?= BASE_URL?>/Assets/images/uploads/category.jpg" id="imgExampleView">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="text-center">Información general</h2>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">El nombre del cliente es visible</label>
                                    <p class="text-break" id="isVisibleView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Fecha</label>
                                    <p class="text-break" id="strDateView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Cliente</label>
                                    <p class="text-break" id="strNameView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Estado</label>
                                    <p class="text-break" id="statusListView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Orden</label>
                                    <p class="text-break" id="orderListView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Ubicación</label>
                                    <p class="text-break" id="strAddressView"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="strReview" class="form-label fw-bold">Descripción</label>
                            <p class="text-break" id="strReviewView"></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h2 class="text-center">Información de enmarcación</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Tipo</label>
                                    <p class="text-break" id="strTypeView"></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label fw-bold">Total</label>
                                    <p class="text-break" id="strTotalView"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row" id="frameDescriptionView"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>