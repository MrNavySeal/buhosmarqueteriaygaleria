<div class="modal fade" id="modalElement">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Nueva moldura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formFile" name="formFile">
                        <div class="row scrolly" id="upload-multiple">
                            <div class="col-6 col-lg-3">
                                <div class="mb-3 upload-images">
                                    <label for="txtImg" class="text-primary text-center d-flex justify-content-center align-items-center">
                                        <div>
                                            <i class="far fa-images fs-1"></i>
                                            <p class="m-0">Subir imágen</p>
                                        </div>
                                    </label>
                                    <input class="d-none" type="file" id="txtImg" name="txtImg[]" multiple accept="image/*"> 
                                </div>
                            </div>
                        </div>
                    </form>
                    <form id="formItem" name="formItem" class="mb-4">  
                        <input type="hidden" id="idProduct" name="idProduct" value="">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtReference" class="form-label">Referencia</label>
                                    <input type="text" class="form-control" id="txtReference" name="txtReference">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="molduraList" class="form-label">Tipo de moldura <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="molduraList" name="molduraList" required>
                                        <option value="1">Madera</option>
                                        <option value="3">Madera diseño único</option>
                                        <option value="2">Poliestireno</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtWaste" class="form-label">Desperdicio (cm)</label>
                                    <input type="number" class="form-control" id="txtWaste" name="txtWaste">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtPrice" class="form-label">Costo x cm <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" min ="1" id="txtPrice" name="txtPrice">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtDiscount" class="form-label">Descuento</label>
                                    <input type="number" class="form-control"  max="99" id="txtDiscount" name="txtDiscount">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="txtFrame" class="form-label">Imágen de marco <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="txtFrame" name="txtFrame" accept="image/*"> 
                        </div>
                        
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                            <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>