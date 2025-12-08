<div class="modal fade" id="modalElement">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Nueva opción de propiedad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-pills" id="product-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button" role="tab" aria-controls="specifications" aria-selected="false">Propiedades desactivadas</button>
                    </li>
                    <li class="nav-item" role="presentation" >
                        <button class="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab" aria-controls="ingredients" aria-selected="false">Insumos</button>
                    </li>
                </ul>
                <div class="tab-content mb-3" id="myTabContent">
                    <div class="tab-pane show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                        <input type="hidden" id="id" name="id">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtName" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="txtName" name="txtName" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Propiedades <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="propList" name="propList" required></select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtTag" class="form-label">Etiqueta</label>
                                    <input type="text" class="form-control" id="txtTag" name="txtTag" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="txtTag" class="form-label">Etiqueta doblemarco</label>
                                    <input type="text" class="form-control" id="txtTagFrame" name="txtTagFrame" >
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Estado <span class="text-danger">*</span></label>
                                    <select class="form-control" aria-label="Default select example" id="statusList" name="statusList">
                                        <option value="1">Activo</option>
                                        <option value="2">Inactivo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="isVisible">
                                <label class="form-check-label" for="isVisible">Habilitar si es visible en la tienda virtual</label> 
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isColor">
                                        <label class="form-check-label" for="isColor">Habilitar colores</label> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isDblFrame">
                                        <label class="form-check-label" for="isDblFrame">Habilitar doblemarco</label> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isBocel">
                                        <label class="form-check-label" for="isBocel">Habilitar bocel</label> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="isMargin">
                                        <label class="form-check-label" for="isMargin">Habilitar margen</label> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="mb-3 d-none" id="divMargin">
                            <label for="txtMargin" class="form-label">Margen máximo (cm)</label>
                            <input type="number" class="form-control" id="txtMargin" value="5">
                        </div>
                    </div>
                    <div class="tab-pane fade" id="specifications" role="tabpanel" aria-labelledby="specifications-tab">
                        <p class="text-secondary">Agrega las propiedades que se desactivan al seleccionar esta opción de propiedad</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="statusList" class="form-label">Propiedades <span class="text-danger">*</span></label>
                                    <div class="d-flex">
                                        <select class="form-control" aria-label="Default select example" id="selectDisableProp" required></select>
                                        <button type="button" class="btn btn-primary" onclick="addItem('props')"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive overflow-y" style="max-height:30vh">
                            <table class="table">
                                <thead>
                                    <th>Nombre</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody id="tableProps"></tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="ingredients" role="tabpanel" aria-labelledby="ingredients-tab">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="selectMaterial" class="form-label">Materiales</label>
                                    <select class="form-control" aria-label="Default select example" id="selectMaterial"></select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="selectCalc" class="form-label">Calcular por:</label>
                                    <div class="d-flex">
                                        <select class="form-control" aria-label="Default select example" id="selectCalc">
                                            <option value="area">Área</option>
                                            <option value="perimetro">Perímetro</option>
                                        </select>
                                        <select class="form-control" aria-label="Default select example" id="selectType">
                                            <option value="completo">Completo</option>
                                            <option value="imagen">imagen</option>
                                        </select>
                                        <input type="number" class="form-control" id="txtNumber" placeholder="Digite un factor o multiplicador" value=1>
                                        <button type="button" class="btn btn-primary" onclick="addItem('material')"><i class="fas fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive overflow-y" style="max-height:30vh">
                            <table class="table">
                                <thead>
                                    <th>Nombre</th>
                                    <th>Cálculo</th>
                                    <th>Tipo</th>
                                    <th>Factor</th>
                                    <th>Opciones</th>
                                </thead>
                                <tbody id="tableMaterial"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="save(this)"><i class="fas fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>