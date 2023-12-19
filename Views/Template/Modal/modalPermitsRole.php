<?php
    $permit = $data['permit'];
    $module = $data['module'];
?>
<div class="modal fade" id="modalPermits">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Permisos de usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPermits">
                    <input type="hidden" name="idRol" value ="<?=$data['idRol']?>">
                    <div class="table-responsive">
                        <table class="table text-center align-middle">
                            <thead>
                                <tr>
                                    <th class="text-start">Modulo</th>
                                    <th>Leer</th>
                                    <th>Crear</th>
                                    <th>Actualizar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="modules">
                                <?php
                                    for ($i=0; $i < count($module); $i++) { 

                                ?>
                                <tr>
                                    <td class="text-start" >
                                        <div>
                                            <input type="hidden" name="module[<?=$i?>][idmodule]?>" value = "<?=$module[$i]['idmodule']?>">
                                            <?=$module[$i]['name']?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                                            <input class="form-check-input" type="checkbox" role="switch" name="module[<?=$i?>][r]?>" <?=$permit[$i]['r'] == 1 ? "checked" : ""?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                                            <input class="form-check-input" type="checkbox" role="switch" name="module[<?=$i?>][w]?>" <?=$permit[$i]['w'] == 1 ? "checked" : ""?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                                            <input class="form-check-input" type="checkbox" role="switch" name="module[<?=$i?>][u]?>" <?=$permit[$i]['u'] == 1 ? "checked" : ""?>>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch d-flex justify-content-center" style="width:100px;">
                                            <input class="form-check-input" type="checkbox" role="switch" name="module[<?=$i?>][d]?>" <?=$permit[$i]['d'] == 1 ? "checked" : ""?>>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="btnPermit"><i class="fas fa-save"></i> Guardar</button>
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>