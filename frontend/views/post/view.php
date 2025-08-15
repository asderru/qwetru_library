<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use frontend\widgets\Blog\CommentsWidget;
    use frontend\widgets\PagerWidget;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $root core\edit\entities\Blog\Post */
    /* @var $model core\edit\entities\Blog\Post */
    
    $layoutId = '#frontend_views_post_view';
    
    $request = Yii::$app->request;
    $fullUrl = $request->absoluteUrl;
    
    $this->title = $model->title;
    $category    = $model->category;
    
    $this->params['breadcrumbs'][] = [
        'label' => $category->name,
        'url'   => [
            'category/view',
            'id' => $category->id,
        ],
    ];
    $this->params['breadcrumbs'][] = $model->name;
    $this->params['preload']       = $model->getWebPhotoColumn(12);

?>


<div class='row inner-area__content'>

    <div class='col-lg-9'>

        <article class='main-article' itemscope itemtype='http://schema.org/Article'>

            <meta itemprop='description' content="<?= FormatHelper::stripTags($model->description) ?>">
            <meta itemprop='mainEntityOfPage' content='<?= $fullUrl ?>'>
            <meta itemprop='dateModified' content='<?php
                try {
                    echo FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s');
                }
                catch (InvalidConfigException $e) {
                    PrintHelper::saveDbDummy($layoutId, $e);
                } ?>'>
            <div class='main-article__title'>
                <h1 itemprop='headline' class='h1'>
                    <?= Html::encode($model->title) ?>
                </h1>

            </div>
            
            <?php
                if ($model->photos): ?>

                    <div class='main-article__media'>

                        <div class='article-media__image' itemprop='image'>
                            <?php
                                try {
                                    echo
                                    $model->getFullImage(
                                        'img-fluid',
                                    );
                                }
                                catch (ImagickException $e) {
                                    PrintHelper::saveDbDummy(
                                        $layoutId, $e,
                                    );
                                }
                            ?>
                        </div>

                    </div>
                
                <?php
                endif; ?>

            <div class='main-article__content'>

                <div class="d-flex justify-content-between">

                    <div class='article-content__meta tags-area' itemprop='keywords'>
                        <?php
                            if ($model->tags) { ?>
                                Метки:
                                <?php
                                foreach ($model->tags as $tag) { ?>
                                    
                                    <?= Html::a(
                                        Html::encode($tag->name),
                                        [
                                            'tag/view',
                                            'slug' => $tag->slug,
                                        ],
                                        [
                                            'class' => 'tag-link',
                                        ],
                                    )
                                    ?>
                                    
                                    <?php
                                } ?>
                                
                                <?php
                            } ?>
                    </div>

                    <div class="article-content__date" itemprop='datePublished'>
                        
                        <?php
                            try {
                                echo
                                FormatHelper::asDateTime($model->created_at, 'd MMMM yyyy hh:mm:ss');
                            }
                            catch (InvalidConfigException $e) {
                                PrintHelper::saveDbDummy($layoutId, $e);
                            } ?>

                        <meta itemprop='dateCreated' content='<?php
                            try {
                                echo
                                FormatHelper::asDateTime($model->created_at, 'php:Y-m-d\TH:i:s');
                            }
                            catch (InvalidConfigException $e) {
                                PrintHelper::saveDbDummy($layoutId, $e);
                            } ?>'>
                        <meta itemprop='dateModified' content='<?php
                            try {
                                echo
                                FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s');
                            }
                            catch (InvalidConfigException $e) {
                                PrintHelper::saveDbDummy($layoutId, $e);
                            } ?>'>
                    </div>

                </div>
                <div itemprop='articleBody' class='article-content__body'>
                    
                    <?= FormatHelper::asHtml($model->text) ?>

                </div>
                <div class='article-content__author' itemprop='author'>
                    <?= Html::encode($model->profile->first_name) ?>
                    <?= Html::encode($model->profile->last_name) ?>
                </div>

            </div>

        </article>
        <div class='pagination-section row' itemscope itemtype='http://schema.org/SiteNavigationElement'>
            
            <?php
                try {
                    echo
                    PagerWidget::widget(
                        [
                            'model'  => $model,
                            'folder' => true,
                        ],
                    );
                }
                catch (Throwable $e) {
                }
            ?>
        </div>


        <section class='comment-area'>
            <?= CommentsWidget::widget([
                'content' => $model->content,
            ]) ?>
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
                '@app/views/layouts/cached/_category-widget.php',
                [
                    'categoryId' => $model->category_id,
                ],
            )
        ?>
        <?=
            $this->render(
                '@app/views/layouts/cached/_post-widget.php',
                [
                    'postId' => $model->id,
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
                '@app/views/layouts/cached/_tag-widget.php',
                [
                    'tagId' => null,
                ],
            )
        ?>

    </aside>

</div>

<!--###### End of Main ##################################################-->
