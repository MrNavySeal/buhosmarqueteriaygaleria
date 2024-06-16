<?php $companyData = getCompanyInfo();?>
<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Configurar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formItem" name="formItem" class="mb-4">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-3">
                        <label for="txtName" class="form-label">Imagen por defecto de la aplicación</label>
                        <div class="uploadImg">
                            <img src="<?=media()."/images/uploads/".$companyData['logo']?>">
                            <label for="txtImg"><a class="btn btn-info text-white"><i class="fas fa-camera"></i></a></label>
                            <input class="d-none" type="file" id="txtImg" name="txtImg" accept="image/*"> 
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isFrame" checked>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Visualizar enmarcación</label> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isPrint">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Habilitar impresión de imagen</label> 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="isCost" checked>
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Habilitar modo costos</label> 
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Habilitar tipo de molduras</label>
                                <div class="table-responsive overflow-y" style="max-height:30vh">
                                    <table class="table">
                                        <thead>
                                            <th>Nombre</th>
                                            <th>Habilitar</th>
                                        </thead>
                                        <tbody id="tableFraming"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Habilitar propiedades</label>
                                <div class="table-responsive overflow-y" style="max-height:30vh">
                                    <table class="table">
                                        <thead>
                                            <th>Nombre</th>
                                            <th>Habilitar</th>
                                        </thead>
                                        <tbody id="tableProps"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
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