<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use frontend\extensions\helpers\BreadCrumbHelper;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $root core\edit\entities\Blog\Category */
    /* @var $model core\edit\entities\Blog\Category */
    /* @var $childs core\edit\entities\Blog\Category[] */
    /* @var $posts core\edit\entities\Blog\Post */
    /* @var $hasPosts bool */
    
    $layoutId = '#frontend_views_category_view';
    
    $request = Yii::$app->request;
    $fullUrl = $request->absoluteUrl;
    
    $this->title = FormatHelper::asHtml($model->title);
    
    $this->params['breadcrumbs'][] = BreadCrumbHelper::index(Constant::CATEGORY_LABEL);
    foreach ($model->parents()->all() as $parent) {
        if ($parent->depth > Constant::THIS_ROOT) {
            $this->params['breadcrumbs'][] = [
                'label' => $parent->name,
                'url'   => [
                    'category/view',
                    'id' => $parent->id,
                ],
            ];
        }
    }
    $this->params['breadcrumbs'][] = $model->name;
    $this->params['preload']       = $model->getWebPhotoColumn(12);
?>
<div class='row inner-area__content'>

    <div class='col-lg-9'>

        <article class='main-article' itemscope itemtype='http://schema.org/Article'>

            <div class='main-article__title'>
                <h1 itemprop='headline' class='h1'>
                    <?= Html::encode($model->title) ?>
                </h1>

            </div>
            <?php
                if ($childs) { ?>
                    <div class='main-article__description'>
                        <?php
                            foreach ($childs as $child) { ?>
                                &nbsp;&middot; <?= Html::a(
                                    Html::encode($child->name),
                                    [
                                        'category/view',
                                        'id' => $child->id,
                                    ],
                                    [
                                        'aria-label' => $child->name,
                                    ],
                                ) ?>
                                
                                <?php
                            }
                        ?>
                    </div>
                    <?php
                }
            ?>

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

            <div class='main-article__content'>

                <div itemprop='articleBody' class='article-content__body'>
                    
                    <?= FormatHelper::asHtml($model->text) ?>
                </div>

            </div>

            <meta itemprop='description' content="<?= FormatHelper::stripTags($model->description) ?>">
            <meta itemprop='mainEntityOfPage' content='<?= $fullUrl ?>'>
            <meta itemprop='dateModified' content='<?php
                try {
                    echo FormatHelper::asDateTime($model->updated_at, 'php:Y-m-d\TH:i:s');
                }
                catch (InvalidConfigException $e) {
                    PrintHelper::saveDbDummy($layoutId, $e);
                } ?>'>
        </article>

        <hr>
        
        <?php
            if ($posts) { ?>
                <section class='additional-article__content'
                         itemscope itemtype='http://schema.org/ItemList'>

                    <div class='article-section__title' itemprop='name'>
                        <h3>
                            Посты в блоге
                        </h3>
                        <p class='additional-article__description'>
                            «<strong><?= Html::encode($model->title) ?></strong>»‎
                    </div>
                    
                    <?php
                        $i = 0;
                        foreach (
                            $posts as $post
                        ) {
                            $i++; ?>
                            <?= $this->render(
                                '@app/views/layouts/partials/_model',
                                [
                                    'model' => $post,
                                    'url'   => 'post',
                                    'i' => $i,
                                ],
                            ) ?>
                            <?php
                        } ?>

                </section>
                <hr>
                <?php
            } ?>

        <section class='additional-article__content text-center'>
            <?= Html::a(
                'Все посты блога «' . $model->name . '»‎',
                [
                    'post/index',
                    'id' => $model->id,
                ],
            )
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
