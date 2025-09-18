<?php
    headerPage($data);
    $order = $data['data']['order'];
    $status = "";
    if($order['status'] == "approved"){
        $status ='<span class="badge bg-success text-white">aprobado</span>';;
    }else if($order['status'] == "pendent"){
        $status ='<span class="badge bg-warning text-black">pendiente</span>';;
    }else if($order['status'] == "canceled"){
        $status ='<span class="badge bg-danger text-white">cancelado</span>';;
    }
?>
    <main>
        <div class="container mt-4 mb-4 p-5">
            <?php if($order['status'] == "canceled"){?>
            <p class="text-center p-2 bg-danger text-white fw-bold">Oops! el pago de tu pedido fue cancelado.</p>
            <p class="text-center">Te invitamos a intentarlo nuevamente.</p>
            <?php }else if($order['status'] == "pendent"){?>
            <p class="text-center p-2 bg-warning fw-bold">El pago de tu pedido se encuentra pendiente y en espera de confirmación.</p>
            <p class="text-center">Su pedido será aprobado una vez realizado el pago.</p>
            <?php }else if($order['status'] == "approved"){?>
            <p class="text-center p-2 bg-success text-white fw-bold">Gracias por tu compra!.</p>
            <p class="text-center">El pago de su pedido fue realizado correctamente.</p>
            <?php }?>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="w-25  fw-bold">Estado</td>
                            <td><?=$status?></td>
                        </tr>
                        <tr>
                            <td class="w-25  fw-bold">Factura de venta</td>
                            <td><?=$order['idorder']?></td>
                        </tr>
                        <tr>
                            <td class="w-25  fw-bold">Transacción</td>
                            <td><?=$order['idtransaction']?></td>
                        </tr>
                        <tr>
                            <td class="w-25  fw-bold">Fecha de transacción</td>
                            <td><?=$order['date']?></td>
                        </tr>
                        <tr>
                            <td class="w-25  fw-bold">Monto</td>
                            <td><?=formatNum($order['amount'])?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <p class="m-0 mb-3 text-center">Puedes ver los pedidos en tu perfil de usuario</p>
            <div class="d-flex gap-2 justify-content-center">
                <?php if($order['status'] == "canceled"){?>
                <button type="button" onclick="modalCheckout(this)" class="btn btn-bg-1 btnModalCheckout">Pagar</button>
                <?php }?>
                <a href="<?=base_url()?>/tienda" class="btn btn-bg-2">Ir a la tienda</a>
                <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-2">Enmarcar</a>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>