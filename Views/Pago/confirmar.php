<?php
    headerPage($data);
?>
    <main>
        <div class="container mt-4 mb-4 text-center">
            <h2 class="fs-1 text-secondary">Gracias por tu pedido!</h2>
            <p class="m-0">Detalles de la transferenca</p>
            <hr>
            <div class="mt-3">
                <p class="m-0 mb-3">Puedes ver el pedido en tu perfil de usuario</p>
                <a href="<?=base_url()?>" class="btn btn-bg-1">Continuar</a>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>