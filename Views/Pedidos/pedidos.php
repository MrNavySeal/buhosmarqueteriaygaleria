<?php headerPage($data)?>
<div id="modalItem"></div>
<?php if($_SESSION['permitsModule']['r']){?>
<div class="modal fade" tabindex="-1" id="modalPos">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Punto de venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div>
                    <label for="" class="form-label">Cliente</label>
                    <input class="form-control" type="search" placeholder="Buscar" aria-label="Search" id="searchCustomers" name="searchCustomers">
                </div>
                <div class="position-relative" id="selectCustomers">
                    <div id="customers" class="bg-white position-absolute w-100" style="overflow-y:scroll; max-height:30vh;"></div>
                </div>
                <div id="selectedCustomer"></div>
                <form id="formSetOrder">
                    <input type="hidden" id="idOrder" name="idOrder" value="">
                    <input type="hidden" name="id" id="idCustomer" value ="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Fecha</label>
                                <input type="date" name="strDate" id="txtDate" class="form-control">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Notas</label>
                                <textarea rows="3" name="strNote" id="txtNotePos" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mt-3 mb-3">
                                <label for="" class="form-label">Tipo de pago <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="paymentList" name="paymentList" required>
                                    <?php
                                        $pago="";
                                        for ($i=0; $i < count(PAGO) ; $i++) { 
                                            $pago .='<option value="'.$i.'">'.PAGO[$i].'</option>';
                                        }
                                    ?>
                                    <?=$pago?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="typeList" class="form-label">Estado de pago <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusList" name="statusList" required>
                                    <option value="1">aprobado</option>
                                    <option value="2">pendiente</option>
                                    <option value="3">cancelado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mt-3 mb-3">
                                <label for="typeList" class="form-label">Estado de pedido <span class="text-danger">*</span></label>
                                <select class="form-control" aria-label="Default select example" id="statusOrder" name="statusOrder" required>
                                    <?php
                                        $status="";
                                        for ($i=0; $i < count(STATUS) ; $i++) { 
                                            $status .='<option value="'.$i.'">'.STATUS[$i].'</option>';
                                        }
                                    ?>
                                    <?=$status?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="" class="form-label">Dinero recibido <span class="text-danger">*</span></label>
                        <input type="number" name="received" id="moneyReceived" class="form-control">
                    </div>
                    <div class="mt-3 mb-3">
                        <label for="" class="form-label">Descuento (opcional)</label>
                        <input type="number" name="discount" id="discount" class="form-control">
                    </div>
                    <p id="saleValue"></p>
                    <p id="moneyBack"></p>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnAddPos">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php }?>
<main class="addFilter container mb-3" id="<?=$data['page_name']?>">
    <div class="row">
        <?php require_once('Views/Template/nav_admin.php');?>
        <div class="col-12 col-lg-9 col-md-12">
            <div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
                    <?php
                        if($_SESSION['permitsModule']['w']){
                    ?>
                    <button class="btn btn-primary d-none" type="button" id="btnNew">Agregar <?= $data['page_tag']?> <i class="fas fa-plus"></i></button>
                    <?php
                    }
                    ?>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6 mt-3">
                        <form autocomplete="off">
                            <input class="form-control" type="search" placeholder="Search" aria-label="Search" id="search" name="search">
                        </form>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="row">
                            <div class="col-md-3 d-flex align-items-center text-end">
                                <span>Ordenar por: </span>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control" aria-label="Default select example" id="sortBy" name="sortBy" required>
                                    <option value="1">Más reciente</option>
                                    <option value="2">Estados de pago desc</option>
                                    <option value="3">Estados de pago asc</option>
                                    <option value="4">Estados de pedido desc</option>
                                    <option value="5">Estados de pedido asc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="scroll-y">
                    <table class="table items align-middle" id="table<?=$data['page_title']?>">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Transacción</th>
                                <th>Fecha</th>
                                <th>Monto</th>
                                <th>Tipo de pago</th>
                                <th>Estado de pago</th>
                                <th>Estado de pedido</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody id="listItem">
                            <?=$data['data']['data']?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>
<?php footerPage($data)?>         
