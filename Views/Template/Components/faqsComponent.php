
<div class="accordion accordion-flush" id="accordionFlushSection">
    <?php 
        foreach ($data as $section) {
            $faqs = $section['faqs'];
            $id = $section['id'];
    ?>
    <div class="accordion-item">
        <h2 class="accordion-header" id="flush-section<?= $id ?>">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseSection<?= $id ?>" aria-expanded="false" aria-controls="flush-collapseSection<?= $id ?>">
            <strong class="fs-5"><?= $section['name']?></strong>
        </button>
        </h2>
        <div id="flush-collapseSection<?= $id ?>" class="accordion-collapse collapse show" aria-labelledby="flush-section<?= $id ?>" data-bs-parent="#accordionFlushSection">
            <div class="accordion-body">
                <div class="accordion accordion-flush" id="accordionFlushFaq">
                    <?php 
                        foreach ($faqs as $faq) { 
                            $idFaq = $faq['id'];
                    ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-faq">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?=$idFaq?>" aria-expanded="false" aria-controls="flush-collapse<?=$idFaq?>">
                                <?= $faq['question']?>
                            </button>
                        </h2>
                        <div id="flush-collapse<?=$idFaq?>" class="accordion-collapse collapse show" aria-labelledby="flush-faq" data-bs-parent="#accordionFlushFaq">
                            <div class="accordion-body px-4"> <p class="text-secondary"><?= $faq['answer']?></p> </div>
                        </div>
                    </div>
                    <?php }?>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
</div>
