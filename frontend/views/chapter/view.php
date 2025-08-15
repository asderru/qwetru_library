<?php
    
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\FormatHelper;
    use core\tools\Constant;
    use frontend\extensions\helpers\BreadCrumbHelper;
    use frontend\widgets\PagerWidget;
    
    
    /* @var $model core\edit\entities\Library\Chapter */
    /* @var $childs core\edit\entities\Library\Chapter[] */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    $book     = $model->book;
    $layoutId = '#frontend_chapter-view';
    
    $this->title = $model->title;
    $this->params['breadcrumbs'][] = BreadCrumbHelper::book($book);
    $this->params['breadcrumbs'][] = $this->title;

?>
<div class='book-area__header'>
    <div class='breadcrumb__image'
         style="background-image: url(<?= $book->getImgUrl(12) ?>);"></div>
    <div class='breadcrumb__area'>
        <div class='container'>
            <!-- Start breadcrumb area -->
            <div class='breadcrumb__inner'>
                <h2 class='breadcrumb-title'><?= $model->title ?></h2>
                
                <?= $this->render(
                    '@app/views/layouts/partials/_breadcrumbs',
                ) ?>

            </div>
        </div>
    </div>
</div>
<!-- Start Chapter Page -->

<div class='book-area__content'>
    <div class='container'>
        <div class='row'>
            <aside class='col-lg-3 sidebar-area'>
                <?= $this->render(
                    '@app/views/layouts/aside/_authorWidget.php', [
                    'model' => $model->book->author,
                ],
                ) ?>
                
                <?= $this->render(
                    '@app/views/layouts/aside/_advertWidget.php', [
                    'model' => $model->content_id,
                ],
                ) ?>
            </aside>

            <article class='col-lg-9 article-area'>

                <div class='article-area__content'>
                    <?= FormatHelper::asHtml(
                        $model->text,
                    )
                    ?>
                    <hr>
                    <div class="pagination-area">
                        <?php
                            try {
                                echo
                                PagerWidget::widget(
                                    [
                                        'model' => $model,
                                    ],
                                );
                            }
                            catch (Throwable $e) {
                            }
                        ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>
