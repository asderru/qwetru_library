<?php
    
    use backend\helpers\ModalHelper;
    use core\edit\forms\UploadForm;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    
    /* @var $model Model */
    /* @var $uploadForm UploadForm */

?>
<div class='card mb-3'>

    <div class='card-header bg-light'>
        <strong>
            Аватар
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
            <div class="card-footer">
                <?= Html::a(
                    'Удалить аватарку',
                    [
                        'delete-photo',
                        'id' => $model->id,
                    ]
                    ,
                    [
                        'class' => 'btn btn-sm btn-danger',
                    ],
                )
                ?>
            </div>
        
        <?php
        else: ?>

            <div class='card-body p-4'>
                
                <?= ModalHelper::uploadImage(
                    $model->id,
                    'Загрузить аватарку',
                ) ?>
                
                <?= $this->render(
                    '@app/views/layouts/cached/_uploadForm.php',
                    [
                        'model'      => $model,
                        'uploadForm' => $uploadForm,
                    ],
                )
                ?>
            </div>
        
        <?php
        endif ?>
</div>
