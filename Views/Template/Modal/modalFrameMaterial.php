<div class="modal fade" id="modalMaterial">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Asignar materiales</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idOption">
                <div class="d-flex">
                    <select class="form-control" aria-label="Default select example" id="selectMaterial"></select>
                    <button type="button" class="btn btn-primary" onclick="addMaterial()"><i class="fas fa-plus"></i></button>
                </div>
                <div class="table-responsive overflow-y" style="max-height:30vh">
                    <table class="table">
                        <thead>
                            <th>Nombre</th>
                            <th>Opciones</th>
                        </thead>
                        <tbody id="tableMaterial"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fas fa-save"></i> Guardar</button>
                <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>