<?php


?>


<?php
    
    use core\helpers\FormatHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    /* @var $model core\edit\entities\Library\Author */
    $url = Url::to(
        [
            'author/view',
            'id' => $model->id,
        ],
    );

?>

<div class='card author-card'>
    <!-- Start Single Product -->
    <div class="row">

        <div class="col-sm-6 author-card__image">
            <?= Html::a(
                Html::img(
                    $model->getImgUrl(3),
                    [
                        'class' => 'img-fluid',
                    ],
                ),
                $url,
            )
            ?>
        </div>
        <div class='col-sm-6 author-card__title'>
            <?= Html::a(
                Html::encode($model->title),
                $url,
            )
            ?>
        </div>
    </div>
    <div class="author-card__description">
        <?= FormatHelper::asDescription(
            $model, 10,
        )
        ?>
    </div>
    <div class='author-card__reference'>
        <?= Html::a(
            'подробнее',
            $url,
            [
                'class' => 'read-more__link',
            ],
        )
        ?>
    </div>
</div>
