<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nueva categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="idExample" name="id">
                    <div class="mb-3 uploadImg">
                        <img src="<?= BASE_URL?>/Assets/images/uploads/category.jpg">
                        <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                        <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isVisible">
                                    <label class="form-check-label" for="isVisible">Habilitar si es visible el nombre del cliente</label> 
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label fw-bold">Fecha</label>
                                        <input type="date" name="strDate" id="strDate" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="" class="form-label">Cliente <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="strName" name="strName" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                            <option value="1">Activo</option>
                                            <option value="2">Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="orderList" class="form-label">Orden <span class="text-danger">*</span></label>
                                        <select class="form-control" aria-label="Default select example" id="orderList" name="orderList" required>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="strReview" class="form-label">Descripción</label>
                                <textarea class="form-control" id="strReview" name="strReview" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="form-label fw-bold">Tipo</label>
                                <p class="text-break" id="strType"></p>
                            </div>
                            <div class="row" id="frameDescription"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-info" data-bs-target="#modalFrameSetExample" data-bs-toggle="modal">Categorías</button>
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>