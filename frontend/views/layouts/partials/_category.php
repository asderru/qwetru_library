<?php
    
    use core\helpers\FormatHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    /* @var $model core\edit\entities\Blog\Category */
    $url = Url::to(
        [
            'category/view',
            'id' => $model->id,
        ],
    );

?>

<div class='card book-card h-100'>
    <!-- Start Single Product -->
    <div class="book-card__image">
        <?= Html::a(
            Html::img(
                $model->getImgUrl(6),
                [
                    'class' => 'img-fluid',
                ],
            ),
            $url,
        )
        ?>
    </div>
    <div class='card-body'>
        <div class="book-card__title">
            <?= Html::encode($model->title) ?>
        </div>
        <div class="book-card__description">
            <?= FormatHelper::asDescription(
                $model, 10,
            )
            ?>
        </div>
        <div class='book-card__reference'>
            
            <?= Html::a(
                'Читать',
                $url,
                [
                    'class' => 'read-more__link',
                ],
            )
            ?>
        </div>
    </div>
</div>
