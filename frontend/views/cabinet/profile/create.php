<?php
    
    use backend\assets\EditAsset;
    use backend\helpers\ButtonHelper;
    use backend\widgets\DatePicker\DatePicker;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model core\edit\forms\User\ProfileForm */
    /* @var $user core\edit\entities\User\User */
    /* @var $actionId string */
    
    EditAsset::register($this);
    
    $layoutId = '#frontend_cabinet_profile_create';
    
    $this->title                   = 'Создать профиль для пользователя ' .
                                     $user->username;
    $this->params['breadcrumbs'][] = ['label' => 'Кабинет', 'url' => ['/default/index']];
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

<div class='row'>

    <div class='col-xl-6'>

        <div class='card h-100'>
            <div class='card-header bg-light'>
                <strong>
                    Персональная информация
                </strong>
            </div>
            <div class='card-body'>
                <?= ButtonHelper::hidden(
                    $model, 'userId',
                    $user->id,
                ) ?>
                
                <?= ButtonHelper::hidden(
                    $model, 'site_id',
                    $user->site_id,
                ) ?>
                
                <?= $form->field($model, 'firstName')
                         ->textInput(['maxlength' => true])
                         ->label('Имя  (для информационных сообщений платформы)') ?>
                
                <?= $form->field($model, 'lastName')
                         ->textInput(['maxlength' => true])
                         ->label('Фамилия (для информационных сообщений платформы)') ?>
                
                <?= $form->field($model, 'name')
                         ->textInput(['maxlength' => true])
                         ->label('Псевдоним, которым буду подписываться тексты на форуме') ?>
                
                <?php
                    try {
                        echo
                        $form
                            ->field($model, 'dateOfBirth')
                            ->widget(DatePicker::classname(), [
                                'language' => 'ru',
                            ])
                            ->label('Дата рождения (Опционально)')
                        ;
                    }
                    catch (Exception|Throwable $e) {
                        PrintHelper::exception(
                            'DatePicker ' .
                            $layoutId, $e,
                        );
                    }
                
                ?>
            </div>

            <div class="card-footer text-center">
                
                <?= ButtonHelper::submit() ?>

            </div>

        </div>

    </div>

    <div class='col-xl-6'>
        <div class='card h-100'>
            <div class='card-header bg-light'>
                <strong>
                    Прочее
                </strong>
            </div>
            <div class='card-body'>
                
                <?= $form->field($model, 'place')
                         ->textInput(['maxlength' => true]) ?>
                
                <?php
                    try {
                        echo
                        $form
                            ->field($model, 'countryId')
                            ->dropDownList(
                                $model->getMap(),
                                [
                                    'id'      => 'countryId',
                                    'options' => [
                                        '7' => ['selected' => false],
                                    ],
                                ],
                            )
                            ->label('Выбрать страну проживания')
                        ;
                    }
                    catch (Exception $e) {
                        PrintHelper::exception(
                            'SelectSiteWidget ' . $layoutId, $e,
                        );
                    }
                ?>
                
                <?= $form->field(
                    $model, 'description',
                    [
                        'inputOptions' => [
                            'id' => 'description-edit-area',
                        ],
                    ],
                )
                         ->textarea(
                             [
                                 'rows' => 12,
                             ],
                         )
                         ->label('Жизнеписание') ?>


            </div>
        </div>
    </div>

</div>

<?= Html::activeHiddenInput(
    $model, 'status',
    [
        'value' => Constant::STATUS_ACTIVE,
    ],
) ?>

<?php
    ActiveForm::end(); ?>
