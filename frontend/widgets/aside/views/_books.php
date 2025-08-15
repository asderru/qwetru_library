<?php
    
    use core\helpers\FormatHelper;
    use core\helpers\ImageHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $models core\edit\entities\Library\Book[] */
    /* @var $book core\edit\entities\Library\Book */
    
    $layoutId = '#frontend_widgets_aside_views_books';

?>


<?php
    foreach ($models as $model): ?>

        <div class='aside-widget__body'>

            <div class='widget-body__image'>
                <?= ImageHelper::getThumb($model) ?>
            </div>

            <div class='widget-body__title'>
                
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
