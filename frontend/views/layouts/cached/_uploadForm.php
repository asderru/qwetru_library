<?php
    
    use backend\helpers\ButtonHelper;
    use consynki\yii\input\ImageInput;
    use core\edit\forms\UploadForm;
    use core\helpers\PrintHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\widgets\ActiveForm;
    
    /* @var $model Model */
    /* @var $uploadForm UploadForm */
    
    $layoutId = '#frontend_views_cached_uploadForm';

?>

<?php
    $form = ActiveForm::begin(
        [
            'options' => [
                'class'   => 'active__form',
                'enctype' => 'multipart/form-data',
            ],
        ],
    ) ?>

<div
        class='modal fade' id='uploadModal' data-bs-backdrop='static'
        data-bs-keyboard='false' tabindex='-1'
        aria-labelledby='uploadModalLabel' aria-hidden='true'
>
    <div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>

        <div class='modal-content'>
            <div class='modal-header'>
                <p class='text-center'>Загрузить аватарку для <br>
                    <?= Html::encode($model->name) ?>
                    <br>
                    Размеры: ширина -
                    <strong><?= $model->photoSize->width
                        ?></strong> пикселей, высота
                    <strong><?= $model->photoSize->height ?></strong>
                    пикселей.
                </p>
            </div>

            <div class='modal-body'>
                
                <?php
                    try {
                        echo
                        $form->field($uploadForm, 'imageFile')->widget(ImageInput::class, [
                            'options' => ['accept' => 'image/*'], // Опции виджета, например, тип файлов
                        ])->label(false);
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            '#consynki\yii\input\ImageInput', $e,
                        );
                    } ?>

            </div>

            <div class='modal-footer'>
                
                <?= ButtonHelper::submit() ?>
                <button
                        type='button'
                        class='btn-sm btn-close'
                        data-bs-dismiss='modal'
                        aria-label='Закрыть'
                >
                </button>
            </div>
        </div>
    </div>
</div>

<?php
    ActiveForm::end() ?>
