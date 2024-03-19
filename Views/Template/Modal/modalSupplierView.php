<div class="modal fade" id="modalViewElement">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Datos de proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <h5 class="modal-title">Información inicial</h5>
                    <div class="table-responsive">
                        <table class="table align-middle text-break">
                            <tbody>
                                <tr><td><img id="strImage" class="rounded-circle" style="height:100px;width:100px;"></td></tr>
                                <tr>
                                    <td><strong>Categoría: </strong></td>
                                    <td id="strCategory"></td>
                                </tr>
                                <tr>
                                    <td><strong>NIT: </strong></td>
                                    <td id="strNit"></td>
                                </tr>
                                <tr>
                                    <td><strong>Nombre: </strong></td>
                                    <td id="strName"></td>
                                </tr>
                                <tr>
                                    <td><strong>Teléfono: </strong></td>
                                    <td id="strPhone"></td>
                                </tr>
                                <tr>
                                    <td><strong>Correo: </strong></td>
                                    <td id="strEmail"></td>
                                </tr>
                                <tr>
                                    <td><strong>Sitio web: </strong></td>
                                    <td id="strWeb"></td>
                                </tr>
                                <tr>
                                    <td><strong>Dirección: </strong></td>
                                    <td id="strAddress"></td>
                                </tr>
                                <tr>
                                    <td><strong>Estado: </strong></td>
                                    <td id="strStatus"></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha creación: </strong></td>
                                    <td id="strCreated"></td>
                                </tr>
                                <tr>
                                    <td><strong>Fecha actualización: </strong></td>
                                    <td id="strUpdated"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div>
                    <h5 class="modal-title">Datos de contacto adicionales</h5>
                    <div class="table-responsive">
                        <table class="table align-middle text-break">
                            <thead>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th></th>
                            </thead>
                            <tbody id="dataContacts"></tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>