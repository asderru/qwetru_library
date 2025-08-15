<?php
    
    use core\tools\Constant;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $models core\edit\entities\Content\Tag[] */
    /* @var $model core\edit\entities\Content\Tag */
    
    $layoutId = '#frontend_widgets_aside_views_models';

?>

<ul class='aside-widget__list tags-widget'>
    <?php
        foreach ($models as $model) {
            ?>
            <li class='list-inline-item'>

                &middot;&nbsp;<?=
                    Html::a(
                        Html::encode($model['name']),
                        Url::home(true) . Constant::TAG_PREFIX . '/' . $model['slug'],
                        [
                            'class'      => 'aside-models__link',
                            'aria-label' => $model['name'],
                        ],
                    )
                ?>

            </li>
            <?php
        } ?>
</ul>
