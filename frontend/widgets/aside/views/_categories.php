<?php
    
    use core\edit\entities\Blog\Category;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    /* @var $models Category[] */
    /* @var $category Category */
    /* @var $activeIds array */
    /* @var $modelId int */
    
    $layoutId = '#frontend_widgets_aside_views_categories';

?>


<!-- Элемент списка  -->
<?php
    foreach (
        $models
        
        as $model
    ):
        ?>

        <li class="list-group-item list-group-item__first-dropdown<?=
            ($modelId === $model->id) ? ' active' : null ?>"
        >
            <!-- Ссылка на раздел -->
            <?=
                
                ($modelId === $model->id)
                    ?
                    Html::tag(
                        'span',
                        Html::encode
                        (
                            $model->name,
                        ),
                        [
                            'class' => 'list-item__nolink',
                        ],
                    )
                    :
                    Html::a(
                        Html::encode
                        (
                            $model->name,
                        ),
                        Url::to($model['link'], true),
                        [
                            'class'      => 'list-item__link',
                            'aria-label' => $model->name,
                        ],
                    ) ?>

        </li>
    <?php
    endforeach; ?>
