<?php
    
    use backend\assets\EditAsset;
    use backend\helpers\ButtonHelper;
    use backend\tools\TinyHelper;
    use yii\bootstrap5\ActiveForm;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\entities\Addon\Panel */
    /* @var $actionId string */
    
    EditAsset::register($this);
    
    $layoutId = '#cabinet_q-panel__update';
    
    $this->title                   = $model->name . '. Правка';
    $this->params['breadcrumbs'][] = ['label' => 'Панели', 'url' => ['index']];
    $this->params['breadcrumbs'][] = [
        'label' => $model->name,
        'url'   => [
            'view',
            'id' => $model->id,
        ],
    ];
    $this->params['breadcrumbs'][] = 'Правка';
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
                            Краткое описание
                        </strong>
                    </div>

                    <div class='card-body'>
                        
                        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
                        
                        <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

                    </div>
                </div>

            </div>

            <div class='col-xl-6'>

                <div class='card h-100'>
                    <div class='card-header bg-light'>
                        <strong>
                            Общая информация
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




<?= TinyHelper::getDescription(200) ?>

<?= TinyHelper::getText() ?>
