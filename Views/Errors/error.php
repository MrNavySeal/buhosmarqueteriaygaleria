
<?php
    headerPage($data);
?>
    <main class="container mt-4 mb-4 text-center">
        <div class="d-flex justify-content-center align-items-center p-5 text-center">
            <div>
                <h1 class="fs-1 t-color-2">Error 404</h1>
                <h2 class="fs-2 text-secondary mb-3">¡Uy! Te has perdido.</h2>
                <a href="<?=base_url()?>/tienda" class="btn btn-bg-2">Ver tienda</a>
                <a href="<?=base_url()?>/enmarcar" class="btn btn-bg-2">Enmarcar</a>
            </div>
        </div>
    </main>
<?php
    footerPage($data);
?>