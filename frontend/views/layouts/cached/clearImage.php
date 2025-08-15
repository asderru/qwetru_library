<?php
    
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    
    $layoutId = '#frontend_layouts_widgets_clearImage';

?>
<div class='card mb-3'>

    <div class='card-header'>
        <strong>
            Изображение
        </strong>
    </div>
    
    <?php
        if ($model->photo) : ?>
            <div class='card-body'>
                <?=
                    Html::a(
                        Html::img(
                            $model->getThumbFileUrl(
                                'photo',
                                'col-6',
                            ),
                            [
                                'class' => 'img-fluid',
                            ],
                        ),
                        $model->getUploadedFileUrl(
                            'photo',
                        ),
                        [
                            'target' => '_blank',
                        ],
                    ) ?>
            </div>
        <?php
        endif ?>
</div>
