<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    
    
    /* @var $root core\edit\entities\Library\Book */
    /* @var $books core\edit\entities\Library\Book[] */
    
    $this->title = $root->title;
    
    $this->params['breadcrumbs'][] = $this->title;
    
    $this->params['title'] = $this->title;

?>
<div class='book-area_header bg-image__<?= $root->id ?>'>

    <div class='container'>

        <!-- Start breadcrumb area -->
        <div class='breadcrumb__inner'>
            <h2 class='breadcrumb-title'><?= Constant::AUTHOR_LABEL ?></h2>
        </div>
        <!-- End breadcrumb area -->

    </div>

</div>
<!-- Start Shop Page -->
<div class='container'>
    <div class='row'>
        
        <aside class='col-lg-3 sidebar-area order-lg-1 order-lg-1'>
            <?= $this->render(
                '@app/views/layouts/aside/_advertWidget.php', [
                'model' => $root->content_id,
            ],
            ) ?>
        </aside>

        <article class="col-lg-9 article-area">

            <div class='article-area__content'>
                <?= FormatHelper::asHtml(
                    $root->text,
                )
                ?>
            </div>

        </article>

    </div>
</div>
