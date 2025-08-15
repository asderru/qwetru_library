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
    
    $this->title                   = $model->title;
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
                            <?= FormatHelper::asDescription($model, 70)
                            ?>
                            <div class="text-end p-4">
                                <?= Html::a(
                                    'подробнее', '#bio',
                                    [
                                        'class' => 'read-more__link',
                                    ],
                                ) ?>
                            </div>
                        </div>
                    </div>

                </div>
                
                <?php
                    if ($dataProvider):?>
                        <div class='article-area__bottom'>
                            <?= Html::tag('h3', 'Тексты автора',
                            [
                                    'class'=>'text-center'
                            ]) ?>
                            <?= $this->render(
                                '@app/views/author/_books.php',
                                [
                                    'dataProvider' => $dataProvider,
                                ],
                            ) ?>
                        </div>
                    <?php
                    endif;
                ?>

                <div id='bio' class='article-area__bottom'>
                    <?= FormatHelper::asHtml($model->text) ?>
                </div>

            </article>
            
            <aside class='col-lg-3 sidebar-area order-lg-1 order-lg-1'>
                <?= $this->render(
                    '@app/views/layouts/aside/_authorsWidget.php',
                )
                ?>
                
                <?= $this->render(
                    '@app/views/layouts/aside/_advertWidget.php', [
                    'model' => $model->content_id,
                ],
                ) ?>
            </aside>


        </div>
    </div>
</div>
