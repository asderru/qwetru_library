<?php
    
    use core\helpers\ImageHelper;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $model array
     * */
    
    $layoutId = '#frontend_widgets_aside_views_anons';

?>

<div class='aside-widget__body'>

    <div class='widget-body__title'>
        <?= ImageHelper::getThumb($model) ?>
    </div>

    <div class='widget-body__title'>
        
        <?= Html::a(
            Html::encode(
                $model['name'],
            ),
            Url::to($model['reference'], true),
            [
                'class'      => 'body-title__link',
                'aria-label' => $model['name'],
            ],
        )
        ?>
    </div>

    <div class='widget-body__content'>
        <?= $model['description'] ?>
    </div>

    <div class='widget-body__reference'>
        <?= Html::a(
            'узнать',
            Url::to($model['reference'], true),
            [
                'class'      => 'widget-body__link',
                'aria-label' => $model['name'],
            ],
        )
        ?>
    </div>

</div>
