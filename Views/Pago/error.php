<?php
    headerPage($data);
?>
    <main>
        <div class="container mt-4 mb-4 text-center">
            <h2 class="fs-1 text-secondary">Oops! Ha ocurrido un error con tu pedido :(</h2>
            <p class="m-0">Tu pedido ha sido rechazado</p>
            <hr>
            <div class="mt-3">
                <a href="<?=base_url()?>/pago" class="btn btn-bg-1">Intentar de nuevo</a>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>