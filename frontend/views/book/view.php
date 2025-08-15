<?php
    
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\FormatHelper;
    use core\tools\Constant;
    use frontend\extensions\helpers\BreadCrumbHelper;
    use yii\helpers\Html;
    
    
    /* @var $model core\edit\entities\Library\Book */
    /* @var $childs core\edit\entities\Library\Book[] */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    $layoutId = '#frontend_book_view';
    
    $this->title = $model->title;
    
    foreach ($model->parents()->all() as $parent) {
        if ($parent->depth > Constant::THIS_FIRST_NODE) {
            $this->params['breadcrumbs'][] = [
                'label' => $parent->name,
                'url'   => [
                    'view',
                    'id' => $parent->id,
                ],
            ];
        }
    }
    $this->params['breadcrumbs'][] = $model->name;

?>

<div class='book-area__header'>
    <div class='breadcrumb__image'
         style="background-image: url(<?= $model->getImgUrl(12) ?>);"></div>
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
<!-- Start Book Page -->
<div class="book-area__content">
    <div class='container'>
        <div class='row'>
            <aside class='col-lg-3 sidebar-area'>
                <?= $this->render(
                    '@app/views/layouts/aside/_authorWidget.php', [
                    'model' => $model->author,
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
                    <div class="row">
                        <div class="col-md-5">
                            <?=
                                Html::img(
                                    $model->getImgUrl(6),
                                    [
                                        'class' => 'img-fluid',
                                    ],
                                )
                            ?></div>
                        <div class="col-md-7">
                            <?= FormatHelper::asDescription(
                                $model, 70
                            )
                            ?>
                        </div>
                    </div>

                </div>

                <div class='article-area__bottom'>
                    
                    <?php
                        if ($childs):
                            foreach ($childs as $childBook): ?>
                                <?= $this->render(
                                    '@app/views/layouts/partials/_childBook.php', [
                                    'model' => $childBook,
                                ],
                                ) ?>
                            <?php
                            endforeach;
                        endif;
                    ?>
                </div>
                
                <?php
                    if ($dataProvider):?>
                        <div class='article-area__bottom'>
                            <?= $this->render(
                                '@app/views/book/_chapters.php',
                                [
                                    'dataProvider' => $dataProvider,
                                ],
                            ) ?>
                        </div>
                    <?php
                    endif;
                ?>

                        <div class='article-area__bottom'>
                            <?= FormatHelper::asHtml(
                                $model->text,
                            )
                            ?>
                        </div>
                
            </article>

        </div>
    </div>
</div>
