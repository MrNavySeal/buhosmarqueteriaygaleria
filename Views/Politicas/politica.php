<?php
    headerPage($data);
    $social = getSocialMedia();
?>
    <h1 class="section--title"><?=$data['page']['name']?></h1>
    <?=$data['page']['description']?>
    <div class="d-flex justify-content-between container fw-bold mt-3">
        <p>Vigente desde <?=$data['page']['date']?></p>
        <p>Última actualización <?=$data['page']['dateupdated']?></p>
    </div>
<?php footerPage($data);?>