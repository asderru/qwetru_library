<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    
    /* @var $root core\edit\entities\Blog\Category */
    /* @var $models core\edit\entities\Blog\Category[] */
    
    $layoutId = '#frontend_view_category_index';
    $mainTitle = FormatHelper::asHtml($root->title);
    
    $request = Yii::$app->request;
    $fullUrl = $request->absoluteUrl;
    
    $this->title = $mainTitle;
    
    $this->params['breadcrumbs'][] = $root->name;
    $this->params['title']         = $mainTitle;
    $this->params['preload']       = $root->getWebPhotoColumn(12);

?>
<div class='row inner-area__content' itemscope itemtype='http://schema.org/WebPage'>

    <meta itemprop='name' content="<?= $root->name ?>">
    <meta itemprop='description' content="<?= FormatHelper::asHtml($root->description) ?>">
    <meta itemprop='mainEntityOfPage' content='<?= $fullUrl ?>'>
    <meta itemprop='dateModified' content='<?php
        try {
            echo FormatHelper::asDateTime($root->updated_at, 'php:Y-m-d\TH:i:s');
        }
        catch (InvalidConfigException $e) {
            PrintHelper::saveDbDummy($layoutId, $e);
        } ?>'>

    <div class='col-lg-9'>

        <article class='main-article'>

            <div class='main-article__title'>

                <h1 itemprop='headline' class='h1'>
                    <?= Html::encode($mainTitle) ?>
                </h1>

            </div>

            <div class='article-content__body' itemprop='articleBody'>
                
                <?= FormatHelper::asHtml($root->text) ?>

            </div>

        </article>

        <section class='additional-article__content'
                 itemscope itemtype='http://schema.org/ItemList'>

            <div class='article-section__title'>
                <h3 itemprop='name'>
                    Темы блога
                </h3>
            </div>
            
            <?php
                $i = 0;
                foreach (
                    $models as $model
                ) {
                    $i++; ?>
                    
                    <?= $this->render(
                        '@app/views/layouts/partials/_model',
                        [
                            'model' => $model,
                            'url' => 'category',
                            'i'     => $i,
                            'label' => 'Перейти к теме ' . $model->name,
                        ],
                    ) ?>
                    <?php
                } ?>

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
