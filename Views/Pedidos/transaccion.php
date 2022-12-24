<?php 
headerAdmin($data);

$transaction = $data['transaction'];
$details = $transaction->transaction_details;
$refunds = $transaction->refunds;
//dep($transaction);exit;
//dep($refunds);exit;
$idTransaction = $transaction->id;
$status = $transaction->status;
$date = $transaction->date_created;
$payment_type = $transaction->payment_type_id;
$amount = $transaction->transaction_amount;
$comision = $transaction->fee_details[0]->amount;
$net = $amount-$comision;

//Payer
$payer = $transaction->payer;
$name = $payer->first_name." ".$payer->last_name;
$email = $payer->email;
$identification = $payer->identification->type." ".$payer->identification->number;
$phone = $payer->phone->number;
$refund = false;
if(!empty($refunds)){

}
?>
<div id="modalItem"></div>
<div class="body flex-grow-1 px-3" id="<?=$data['page_name']?>">
    <div id="modalItem"></div>
    <div class="container-lg">
        <div class="card">
            <div class="card-body">
                <div id="orderInfo">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <img src="<?=media()?>/images/uploads/mercadopago.jpg" style="width=100px;height:100px;" alt="">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <p class="m-0 mb-2"><span class="fw-bold">Transacción:</span> <?=$idTransaction?></p>
                            <p class="m-0"><span class="fw-bold">Fecha:</span> <?=$date?></p>
                            <p class="m-0"><span class="fw-bold">Estado:</span> <?=$status?></p>
                            <p class="m-0"><span class="fw-bold">Monto:</span> <?=formatNum($amount)?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="m-0 mb-2 fw-bold">Pagador</p>
                            <p class="m-0"><span class="fw-bold">Nombre:</span> <?=$name?></p>
                            <p class="m-0"><span class="fw-bold">Email:</span> <?=$email?></p>
                            <p class="m-0"><span class="fw-bold">Teléfono:</span> <?=$phone?></p>
                            <p class="m-0"><span class="fw-bold">Identificación:</span> <?=$identification?></p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <p class="m-0 fw-bold">Beneficiario</p>
                            <p class="m-0"><span class="fw-bold">Descripción:</span> <?=$transaction->statement_descriptor?></p>
                        </div>
                    </div>
                    <?php if($_SESSION['permitsModule']['r'] && $_SESSION['userData']['roleid'] != 2){?>
                    <div class="row text-start mt-3 mb-3">
                        <p class="m-0 mb-2 fw-bold">Detalles de pago</p>
                        <p class="m-0"><span class="fw-bold">Monto bruto:</span> <?=formatNum($amount)?></p>
                        <p class="m-0"><span class="fw-bold">Comisión de mercadopago:</span> -<?=formatNum($comision)?></p>
                        <p class="m-0"><span class="fw-bold">Monto neto:</span> <?=formatNum($net)?></p>
                    </div>
                    <?php }?>
                </div>
                <div class="row mt-3">
                    <div class="col-6 text-start">
                        <a href="<?=base_url()?>/pedidos" class="btn btn-secondary text-white"><i class="fas fa-arrow-circle-left"></i> Regresar</a>
                    </div>
                    <div class="col-6 text-end">
                    <button type="button" onclick="printJS({ printable: 'orderInfo', type: 'html', targetStyles: ['*']})" class="btn btn-primary"><i class="fas fa-print"></i> Imprimir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php footerAdmin($data)?>        