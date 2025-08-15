<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\ImageHelper;
    use yii\base\InvalidConfigException;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    use yii\web\View;
    
    /* @var $this View */
    /* @var $models core\edit\entities\Blog\Post[] */
    /* @var $post core\edit\entities\Blog\Post */
    
    $layoutId = '#frontend_widgets_aside_views_posts';
?>

<?php
    foreach (
        $models
        
        as $model
    ): ?>

        <div class='aside-widget__body'>
            <div class='row g-0'>
                <div class='col-md-5'>
                    <?= ImageHelper::getThumb($model, 'img-fluid rounded-start') ?>
                </div>
                <div class='col-md-7'>
                    <div class='widget-body__date'>
                        <?php
                            try {
                                echo
                                FormatHelper::asDate($model['updated_at'], 'd MMMM yyyy');
                            }
                            catch (InvalidConfigException $e) {
                            } ?>
                    </div>
                    <div class='widget-body__meta'>
                        блог: <?php
                            echo ($model['category_id'] === 6)
                                ?
                                Html::a('Сайты', '/seo-blog/seo-optimization/')
                                :
                                Html::a('Продвижение', '/seo-blog/sayty//')
                        ?>
                    </div>
                </div>
            </div>

            <div class="widget-body__title">
                
                <?= Html::a(
                    Html::encode(
                        $model['name'],
                    ),
                    Url::to($model['link'], true),
                    [
                        'class'      => 'body-title__link',
                        'aria-label' => $model['name'],
                    ],
                )
                ?>
            </div>

            <div class='widget-body__content'>
                <?= FormatHelper::truncateWords($model['description'], 10) ?>
            </div>

            <div class='widget-body__reference'>
                <?= Html::a(
                    'читать',
                    Url::to($model['link'], true),
                    [
                        'class'      => 'widget-body__link',
                        'aria-label' => $model['name'],
                    ],
                )
                ?>
            </div>

        </div>
    <?php
    endforeach; ?>
