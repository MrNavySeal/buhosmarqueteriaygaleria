<?php 
    headerAdmin($data);
    $tipos = $data['tipos'];
    $total = 0;
    $active="d-none";
    //unset($_SESSION['arrPOS']);
    //dep($_SESSION['arrPOS']);exit;
?>
<div id="modalItem"></div>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <img src="..." class="rounded me-2" alt="..." height="20" width="20">
        <strong class="me-auto" id="toastProduct"></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
        Hello, world! This is a toast message.
        </div>
    </div>
</div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <?php if($_SESSION['permitsModule']['w']){?>
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
                        <input type="hidden" name="id" id="idCustomer" value ="0">
                        <div class="mt-3 mb-3">
                            <label for="" class="form-label">Nro factura <span class="text-danger">*</span></label>
                            <input type="number" name="txtTransaction" id="txtTransaction" class="form-control">
                        </div>
                        <div class="mt-3 mb-3">
                            <label for="" class="form-label">Fecha</label>
                            <input type="date" name="strDate" id="txtDate" class="form-control">
                        </div>
                        <div class="mt-3 mb-3">
                            <label for="" class="form-label">Notas <span class="text-danger">*</span></label>
                            <textarea rows="5" name="strNote" id="txtNotePos" class="form-control"></textarea>
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
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <button class="nav-link active" id="navOrders-tab" data-bs-toggle="tab" data-bs-target="#navOrders" type="button" role="tab" aria-controls="navOrders" aria-selected="true">Pedidos</button>
                    </li>
                    <?php if($_SESSION['permitsModule']['w']){?>
                    <li class="nav-item">
                        <button class="nav-link" id="quickSale-tab" data-bs-toggle="tab" data-bs-target="#quickSale" type="button" role="tab" aria-controls="quickSale" aria-selected="true">Punto de venta</button>
                    </li>
                    <?php }?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="navOrders">
                        <h2 class="text-center"><?=$data['page_title']?></h2>
                        <button type="button" class="btn btn-success text-white" id="exportExcel" data-name="table<?=$data['page_title']?>" title="Export to excel" ><i class="fas fa-file-excel"></i></button>
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
                                <?=$data['orders']['data']?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($_SESSION['permitsModule']['w']){?>
                    <div class="tab-pane fade" id="quickSale">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <button class="nav-link active" id="navEnmarcar-tab" data-bs-toggle="tab" data-bs-target="#navEnmarcar" type="button" role="tab" aria-controls="navEnmarcar" aria-selected="true">Enmarcar</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link " id="navTienda-tab" data-bs-toggle="tab" data-bs-target="#navTienda" type="button" role="tab" aria-controls="navTienda" aria-selected="true">Tienda</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="navOtros-tab" data-bs-toggle="tab" data-bs-target="#navOtros" type="button" role="tab" aria-controls="navOtros" aria-selected="true">Otros</button>
                            </li>
                        </ul>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="navEnmarcar">
                                        <div class="row">
                                            <?php
                                            for ($i=0; $i < count($tipos); $i++) { 
                                                $url = base_url()."/marcos/personalizar/".$tipos[$i]['route'];
                                                $img = media()."/images/uploads/".$tipos[$i]['image'];
                                            ?>
                                            <div class="col-6 col-lg-4 col-md-6 mb-3">
                                                <div class="card--enmarcar w-100 hover">
                                                    <div class="card--enmarcar-img">
                                                        <a href="<?=$url?>"><img src="<?=$img?>" alt="Enmarcar <?=$tipos[$i]['name']?>"></a>
                                                    </div>
                                                    <div class="card--enmarcar-info">
                                                        <a href="<?=$url?>">
                                                            <h2 class="enmarcar--title"><?=$tipos[$i]['name']?></h2>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }?>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="navTienda">
                                        <form autocomplete="off">
                                            <input class="form-control" type="search" placeholder="Buscar" aria-label="Search" id="searchProducts" name="searchProducts">
                                        </form>
                                        <div class="scroll-y">
                                            <table class="table items align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Portada</th>
                                                        <th>Nombre</th>
                                                        <th>Precio</th>
                                                        <th>Descuento</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody id="listProducts">
                                                    <?=$data['products']['data']?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="navOtros">
                                        <div class="mt-3">
                                            <div class="mb-3">
                                                <label for="exampleFormControlInput1" class="form-label">Descripción del servicio</label>
                                                <textarea rows="4" class="form-control" id="txtService" name="txtService" placeholder="Pintar marco..."></textarea>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Cantidad</label>
                                                        <input type="number" class="form-control" id="intQty" name="intQty">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="exampleFormControlInput1" class="form-label">Precio</label>
                                                        <input type="number" class="form-control" id="intPrice" name="intPrice">
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-primary" onclick="addProduct(null,this)">Agregar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center mb-3"><i class="fs-4 text-primary fas fa-store"></i> <div class="fs-4 d-inline">Punto de venta</div>
                                <div class="scroll-y container mb-3 mt-3" id="posProducts">
                                    <?php 
                                        if(isset($_SESSION['arrPOS']) && !empty($_SESSION['arrPOS'])){
                                            $active ="";
                                            $arrProducts = $_SESSION['arrPOS'];
                                            for ($i=0; $i < count($arrProducts) ; $i++) { 
                                                $total += $arrProducts[$i]['qty'] * $arrProducts[$i]['price'];
                                                
                                    ?>
                                        <?php 
                                            if($arrProducts[$i]['topic'] == 1){
                                                $photo = $arrProducts[$i]['photo'] != "" ? media()."/images/uploads/".$arrProducts[$i]['photo'] : $arrProducts[$i]['img'];
                                        ?>
                                        <div class="position-relative" data-id="<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>" data-h="<?=$arrProducts[$i]['height']?>"
                                            data-w="<?=$arrProducts[$i]['width']?>" data-m="<?=$arrProducts[$i]['margin']?>" data-s="<?=$arrProducts[$i]['style']?>" 
                                            data-mc="<?=$arrProducts[$i]['colormargin']?>" data-bc="<?=$arrProducts[$i]['colorborder']?>" data-t="<?=$arrProducts[$i]['idType']?>" data-f="<?=$arrProducts[$i]['photo']?>"
                                            data-r="<?=$arrProducts[$i]['reference']?>">
                                            <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                            <div class="p-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <img src="<?=$photo?>" alt="" class="me-1" height="60px" width="60px" >
                                                        <div class="text-start">
                                                            <div style="height:25px" class="overflow-hidden"><p class="m-0" ><?=$arrProducts[$i]['name']?></p></div>
                                                            <p class="m-0 productData">
                                                                <span class="qtyProduct"><?=$arrProducts[$i]['qty']?></span> x <?=formatNum($arrProducts[$i]['price'],false)?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-1">
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec"><i class="fas fa-minus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success p-1 text-white productInc"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                    <p class="m-0 mt-1 fw-bold text-end productTotal"><?=formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false)?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }else if($arrProducts[$i]['topic'] == 2){?>
                                        <div class="position-relative" data-id="<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>">
                                            <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                            <div class="p-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <img src="<?=$arrProducts[$i]['image']?>" alt="" class="me-1" height="60px" width="60px" >
                                                        <div class="text-start">
                                                            <div style="height:25px" class="overflow-hidden"><p class="m-0" ><?=$arrProducts[$i]['name']?></p></div>
                                                            <p class="m-0 productData">
                                                                <span class="qtyProduct"><?=$arrProducts[$i]['qty']?></span> x <?=formatNum($arrProducts[$i]['price'],false)?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-1">
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec"><i class="fas fa-minus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success p-1 text-white productInc"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                    <p class="m-0 mt-1 fw-bold text-end productTotal" ><?=formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false)?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }else if($arrProducts[$i]['topic'] == 3){?>
                                        <div class="position-relative" data-id="<?=$arrProducts[$i]['id']?>" data-name="<?=$arrProducts[$i]['name']?>" data-topic ="<?=$arrProducts[$i]['topic']?>">
                                            <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                            <div class="p-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <img src="<?=media()?>/images/uploads/category.jpg" alt="" class="me-1" height="60px" width="60px" >
                                                        <div class="text-start">
                                                            <div style="height:25px" class="overflow-hidden"><p class="m-0" ><?=$arrProducts[$i]['name']?></p></div>
                                                            <p class="m-0 productData">
                                                                <span class="qtyProduct"><?=$arrProducts[$i]['qty']?></span> x <?=formatNum($arrProducts[$i]['price'],false)?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-1">
                                                    <p class="m-0 mt-1 fw-bold text-end productTotal" ><?=formatNum($arrProducts[$i]['price'],false)?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
                                    <?php } }?>
                                </div>
                                <p class="fw-bold text-center fs-5">Total: <span id="total" data-value="<?=floor($total)?>"><?=formatNum($total)?></span></p>
                                <button type="button" class="btn btn-primary <?=$active?>" id="btnPos" onclick="openModalOrder()">Continuar</button>
                            </div>
                        </div> 
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        