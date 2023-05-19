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
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <h2 class="text-center"><?=$data['page_title']?></h2>
                <?php if($_SESSION['permitsModule']['w']){?>
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
                                                    <th>Referencia</th>
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
                                        <button type="button" class="btn btn-primary" onclick="addProduct(null,null,this)"><i class="fas fa-plus"></i></button>
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
                                            if($arrProducts[$i]['topic'] == 2){
                                                if($arrProducts[$i]['producttype'] == 2){
                                                    $total += $arrProducts[$i]['qty'] * $arrProducts[$i]['variant']['price'];
                                                }else{
                                                    $total += $arrProducts[$i]['qty'] * $arrProducts[$i]['price'];
                                                }
                                            }else{
                                                $total += $arrProducts[$i]['qty'] * $arrProducts[$i]['price'];
                                            }
                                            
                                            
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
                                                    <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                                    <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                                </div>
                                                <p class="m-0 mt-1 fw-bold text-end productTotal"><?=formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false)?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php }else if($arrProducts[$i]['topic'] == 2){?>
                                        <?php if($arrProducts[$i]['producttype'] == 1){?>
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
                                                        <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                    <p class="m-0 mt-1 fw-bold text-end productTotal" ><?=formatNum($arrProducts[$i]['price']*$arrProducts[$i]['qty'],false)?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }else{ ?>
                                            <div class="position-relative" data-id="<?=$arrProducts[$i]['id']?>" data-topic ="<?=$arrProducts[$i]['topic']?>" data-variant="<?=$arrProducts[$i]['variant']['id_product_variant']?>">
                                            <button class="btn text-danger p-0 rounded-circle position-absolute top-0 end-0 fs-5" onclick="delProduct(this)"><i class="fas fa-times-circle"></i></button>
                                            <div class="p-1">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex">
                                                        <img src="<?=$arrProducts[$i]['image']?>" alt="" class="me-1" height="60px" width="60px" >
                                                        <div class="text-start">
                                                            <div style="height:25px" class="overflow-hidden"><p class="m-0" ><?=$arrProducts[$i]['reference']." ".$arrProducts[$i]['name']?></p></div>
                                                            <p class="m-0" >Tamaño: <?=$arrProducts[$i]['variant']['width']."x".$arrProducts[$i]['variant']['height']?></p>
                                                            <p class="m-0 productData">
                                                                <span class="qtyProduct"><?=$arrProducts[$i]['qty']?></span> x <?=formatNum($arrProducts[$i]['variant']['price'],false)?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-1">
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-secondary p-1 text-white productDec" onclick="productDec(this)"><i class="fas fa-minus"></i></button>
                                                        <button type="button" class="btn btn-sm btn-success p-1 text-white productInc" onclick="productInc(this)"><i class="fas fa-plus"></i></button>
                                                    </div>
                                                    <p class="m-0 mt-1 fw-bold text-end productTotal" ><?=formatNum($arrProducts[$i]['variant']['price']*$arrProducts[$i]['qty'],false)?></p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php }?>
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
                <?php }?>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        