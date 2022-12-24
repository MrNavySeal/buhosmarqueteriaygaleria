<?php
    headerPage($data);
?>
    <main>
        <div class="container mt-4 mb-4 text-center">
            <h2 class="fs-1 text-secondary">Gracias por tu compra!</h2>
            <p class="m-0">Tu pedido ha sido realizado</p>
            <p class="m-0">Número de orden: <?=openssl_decrypt($data['orderData']['order'],METHOD,KEY)?></p>
            <p class="m-0">Transacción: <?=openssl_decrypt($data['orderData']['transaction'],METHOD,KEY)?></p>
            <hr>
            <div class="mt-3">
                <p class="m-0">Nos pondremos en contacto en breve para organizar la entrega</p>
                <p class="m-0 mb-3">Puedes ver tu pedido en tu perfil de usuario</p>
                <a href="<?=base_url()?>" class="btn btn-bg-1">Continuar</a>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>