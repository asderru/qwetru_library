<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use frontend\extensions\search\BookSearch;
    use yii\bootstrap5\Html;
    
    
    /* @var $root core\edit\entities\Library\Book */
    /* @var $books core\edit\entities\Library\Book[] */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $searchModel BookSearch */
    /* @var $actionId string */
    
    $layoutId = '#frontend_book_index';
    
    $this->title = $root->title;
    
    $this->params['breadcrumbs'][] = $this->title;

?>
<div class='book-area_header bg-image__<?= $root->id ?>'>

    <div class='container'>

        <!-- Start breadcrumb area -->
        <div class='breadcrumb__inner'>
            <h2 class='breadcrumb-title'><?= Constant::BOOK_LABEL ?></h2>
        </div>
        <!-- End breadcrumb area -->

    </div>

</div>
<!-- Start Shop Page -->
<div class='container'>
    <div class='row'>
        
        <aside class='col-lg-3 sidebar-area'>
            <?= $this->render(
                '@app/views/layouts/aside/_authorsWidget.php',
            ) ?>
            
            <?= $this->render(
                '@app/views/layouts/aside/_advertWidget.php', [
                'model' => $root->content_id,
            ],
            ) ?>
        </aside>

        <article class="col-lg-9 article-area">

            <div class='article-area__content'>
                                <?php
                    if ($dataProvider):?>
                        <div class='article-area__bottom'>
                            <?= $this->render(
                                '@app/views/book/_books.php',
                                [
                                    'dataProvider' => $dataProvider,
                                    'searchModel' => $searchModel,
                                ],
                            ) ?>
                        </div>
                    <?php
                    endif;
                ?>
            </div>

            <div class='article-area__bottom'>
                
                <?= FormatHelper::asHtml(
                    $root->text,
                )
                ?>
            </div>

        </article>

    </div>
</div>
