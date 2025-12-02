<?php  headerPage($data); ?>


<div class="container mt-5 mb-3">
    <h1 class="section--title mt-5 pt-3 mb-0"><?= $data['page_title'] ?></h1>
    <?php getComponent("faqsComponent",$data['faqs']); ?>
</div>

<?php  footerPage($data);?>