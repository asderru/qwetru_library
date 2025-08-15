<?php
    
    use core\helpers\FormatHelper;
    use yii\helpers\Html;
    
    /* @var $model core\edit\entities\Library\Book */
    $url = $model['url'];
?>


<div class='card book-card h-100'>
    <!-- Start Single Product -->
    <div class="book-card__image">
        <?= Html::a(
            Html::img(
                $model['picture_url'],
                [
                    'class' => 'book-card__img',
                    'alt'   => $model['title'],
                    'title' => $model['title'],
                ],
            ),
            $url,
        )
        ?>
    </div>
    <div class='card-body book-card__body'>
        <div class="book-card__title">
            <?= Html::a(
                Html::encode($model['title']),
                $url,
            )
            ?>
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
