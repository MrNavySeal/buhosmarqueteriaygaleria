<?php
    headerPage($data);
?>
<div id="modalLogin"></div>
<main>
<div id="<?=$data['page_name']?>">
    <div class="container">
        <div class="d-flex justify-content-center align-items-center">
            <div class="login">
                <form id="formRecovery">
                    <input type="hidden" id="idUser" name="idUser" value="<?= $data['idperson']; ?>" required >
                    <input type="hidden" id="txtEmailRecovery" name="txtEmail" value="<?= $data['email']; ?>" required >
                    <input type="hidden" id="txtToken" name="txtToken" value="<?= $data['token']; ?>" required >
                    <h2 class="mb-4">Actualizar contraseña</h2>
                    <div class="mb-3 d-flex">
                        <div class="d-flex justify-content-center align-items p-3 bg-color-2 text-white text-white"><i class="fas fa-lock"></i></div>
                        <input id="txtPasswordRecovery" name="txtPassword" class="form-control" type="password" placeholder="Nueva contraseña" required >
                    </div>
                    <div class="mb-3 d-flex">
                        <div class="d-flex justify-content-center align-items p-3 bg-color-2 text-white text-white"><i class="fas fa-lock"></i></div>
                        <input id="txtPasswordConfirmRecovery" name="txtPasswordConfirm" class="form-control" type="password" placeholder="Confirmar contraseña" required >
                    </div>
                    <button type="submit" id="recoverySubmit" class="btn btn-bg-2 w-100 mb-4" >Actualizar</button>
                </form>
            </div>
        </div>
    </div>
</div>
</main>
<?php
    footerPage($data);
?>