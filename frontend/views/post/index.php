<?php
    
    use core\edit\entities\Blog\Post;
    use core\helpers\FormatHelper;
    use core\helpers\ParametrHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\grid\GridView;
    
    /* @var $category core\edit\entities\Blog\Post */
    /* @var $models core\edit\entities\Blog\Post[] */
    /* @var $dataProvider yii\data\ActiveDataProvider */
    
    $layoutId  = '#frontend_view_post_index';
    $mainTitle = FormatHelper::asHtml($category->title);
    
    $request = Yii::$app->request;
    $fullUrl = $request->absoluteUrl;
    
    $this->title = $mainTitle;
    
    $this->params['breadcrumbs'][] = $category->name;
    $this->params['preload']       = $category->getWebPhotoColumn(12);


?>
<div class='row inner-area__content' itemscope itemtype='http://schema.org/WebPage'>

    <meta itemprop='name' content="<?= $category->name ?>">
    <meta itemprop='mainEntityOfPage' content='<?= $fullUrl ?>'>
    <meta itemprop='dateModified' content='<?php
        try {
            echo FormatHelper::asDateTime($category->updated_at, 'php:Y-m-d\TH:i:s');
        }
        catch (InvalidConfigException $e) {
            PrintHelper::saveDbDummy($layoutId, $e);
        } ?>'>

    <div class='col-lg-9'>

        <article class='main-article'>

            <div class='main-article__title'>

                <h1 itemprop='name' class='h1'>
                    <?= Html::encode($mainTitle) ?>
                </h1>

            </div>

            <div class='article-content__body' itemprop='description'>
                
                <?= FormatHelper::asHtml($category->description) ?>

            </div>

        </article>

        <section class='additional-article__content'>
            <?php
                    echo GridView::widget([
                        'pager'          => [
                            'firstPageLabel' => 'в начало',
                            'lastPageLabel'  => 'в конец',
                        ],
                        'dataProvider'   => $dataProvider,
                        'caption'        => Constant::POST_LABEL,
                        'captionOptions' => ['class' => 'text-start p-2'],
                        'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
                        'summaryOptions' => ['class' => 'bg-secondary text-white p-1'],
                        'tableOptions'   => ['class' => 'table table-striped table-bordered'],
                        'columns'        => [
                            [
                                'attribute' => 'sort',
                                'label'     => '#',
                            ],
                            [
                                'format'         => 'raw',
                                'value'          => static function (Post $model) {
                                    return $model->mainPhoto
                                        ? Html::tag('img', '', [
                                            'src'      => $model->getCachedPhoto(3),
                                            'class'    => 'img-fluid',
                                        ])
                                        : '<i class="bi bi-eye-fill"></i>';
                                },
                                'label'          => false,
                                'contentOptions' => ['style' => 'width:200px; white-space: normal;'],
                            ],
                            [
                                'attribute' => 'name',
                                'label'     => 'Пост',
                                'format'    => 'raw',
                                'value'     => static function (Post $model) {
                                    return Html::a(
                                        Html::tag('span', Html::encode($model->title), ['itemprop' => 'name']),
                                        ['view', 'id' => $model->id, 'itemprop' => 'url'],
                                    );
                                },
                            ],
                            [
                                'attribute' => 'category_id',
                                'label'     => 'Блог',
                                'format'    => 'raw',
                                'filter'    => ParametrHelper::getCategoriesMap(),
                                'value'     => static function (Post $model) {
                                    return Html::a($model->category->getDepthName(1), ['category/view', 'id' => $model->category_id]);
                                },
                            ],
                        ],
                        'options' => ['itemscope' => true, 'itemtype' => 'http://schema.org/Table'],
                    ]);
            ?>
        </section>

    </div>

    <aside class='col-lg-3 main-aside' itemscope itemtype='http://schema.org/WPSideBar'>
        
        
        <?=
            $this->render(
                '@app/views/layouts/cached/_razdel-widget.php',
                [
                    'razdelId' => null,
                ],
            )
        ?>
        
        <?=
            $this->render(
                '@app/views/layouts/cached/_page-widget.php',
                [
                    'pageId' => null,
                ],
            )
        ?>
        <?=
            $this->render(
                '@app/views/layouts/cached/_category-widget.php',
                [
                    'categoryId' => null,
                ],
            )
        ?>
        
        <?=
            $this->render(
                '@app/views/layouts/cached/_post-widget.php',
                [
                    'postId' => null,
                ],
            )
        ?>
        <?=
            $this->render(
                '@app/views/layouts/cached/_tag-widget.php',
                [
                    'tagId' => null,
                ],
            )
        ?>

    </aside>


</div>
