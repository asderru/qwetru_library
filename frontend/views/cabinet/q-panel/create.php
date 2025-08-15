<?php
    
    use backend\assets\EditAsset;
    use backend\helpers\ButtonHelper;
    use backend\helpers\SelectHelper;
    use backend\tools\TinyHelper;
    use core\edit\forms\Addon\PanelForm;
    use core\helpers\PrintHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model PanelForm */
    /* @var $actionId string */
    
    EditAsset::register($this);
    
    $layoutId = '#cabinet_q-panel__create';
    
    $this->title                   = 'Создание панели';
    $this->params['breadcrumbs'][] = ['label' => 'Панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = $this->title;
?>


<?php
    $form = ActiveForm::begin(
        [
            'options'     => [
                'class' => 'active__form',
            ],
            'fieldConfig' => [
                'errorOptions' => [
                    'encode' => false,
                    'class'  => 'help-block',
                ],
            ],
        ],
    ) ?>

<div class='card'>
    
    <?= $this->render(
        '@app/views/layouts/partials/_createHeader',
        [
            'title'    => $this->title,
            'actionId' => $actionId,
            'layoutId' => $layoutId,
        ],
    ) ?>


    <div class='card-body'>
        <div class='row mb-3'>

            <div class='col-xl-6'>

                <div class='card h-100'>

                    <div class='card-header bg-light'>
                        <strong>
                            Панель
                        </strong>
                    </div>

                    <div class='card-body'>
                        <?php
                            try {
                                echo
                                SelectHelper::getSites($form, $model, true);
                            }
                            catch (Exception $e) {
                                PrintHelper::exception('Выбор сайта ' . $layoutId, $e);
                            } ?>
                        
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                    </div>
                    <div class="card-footer">
                        <?php
                            try {
                                echo
                                SelectHelper::status($form, $model);
                            }
                            catch (Throwable $e) {
                                PrintHelper::exception(
                                    'SelectHelper status ' .
                                    $layoutId, $e,
                                );
                            } ?>
                    </div>


                </div>
            </div>


            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Краткое описание
                        </strong>
                    </div>
                    <div class='card-body'>
                        <?= $form->field(
                            $model, 'description',
                            [
                                'inputOptions' => [
                                    'id' => 'description-edit-area',
                                ],
                            ],
                        )
                                 ->textarea() ?>


                    </div>
                    <div class='card-footer btn-group-sm d-grid gap-2 d-sm-block'>
                        <?= ButtonHelper::submit() ?>

                    </div>

                </div>

            </div>

        </div>

    </div>
</div>

<?php
    ActiveForm::end(); ?>




<?= TinyHelper::getDescription(100) ?>

<?= TinyHelper::getText() ?>
