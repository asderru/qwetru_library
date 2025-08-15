<?php
    
    use core\helpers\FormatHelper;
    use yii\helpers\Html;
    use yii\helpers\Url;
    
    /* @var $model core\edit\entities\Library\Book */
    $url = Url::to(
        [
            'book/view',
            'id' => $model->id,
        ],
    );

?>

<div class='card child-book__card' style='max-width: 640px;'>

    <div class='row g-0'>
        <div class='col-md-4'>
            <div class='child-book__image'>
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
        </div>
        <div class='col-md-8'>
            <div class='card-body book-card__body'>
                <div class='book-card__title'>
                    <?= Html::a(
                        Html::encode($model->title),
                        $url,
                    )
                    ?>
                </div>
                <div class='book-card__description'>
                    <?= FormatHelper::asHtml(
                        $model->description,
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
    </div>
</div>
