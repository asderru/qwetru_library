<?php
    
    use core\edit\search\Library\ChapterSearch;
    use core\helpers\FormatHelper;
    use core\tools\Constant;
    use yii\helpers\Html;
    
    
    /* @var $model core\edit\entities\Library\Book */
    /* @var $searchModel ChapterSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    /* @var $actionId string */
    
    $layoutId = '#frontend_chapter_index';
    
    $this->title = 'Оглавление';
    
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
    $this->params['breadcrumbs'][] = [
        'label' => $model->name,
        'url'   => [
            '/book/view',
            'id' => $model->id,
        ],
    ];
    $this->params['breadcrumbs'][] = $this->title;

?>


<div class='book-area__header'>
    <div class='breadcrumb__image'
         style="background-image: url(<?= $model->getImgUrl(12) ?>);"></div>
    <div class='breadcrumb__area'>
        <div class='container'>
            <!-- Start breadcrumb area -->
            <div class='breadcrumb__inner'>
                <h1 class='breadcrumb-title'>
                    <?=
                        Html::a(
                            Html::encode($model->title),
                            [
                                'book/view',
                                'id' => $model->id,
                            ],
                        ) ?>
                </h1>
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

                <div class="article-area__content text-center">
                    <strong>Оглавление</strong>
                    <h2><?= Html::encode($model->title) ?></h2>
                </div>
                <div class='article-area__content'>
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

            </article>

        </div>
    </div>
</div>
