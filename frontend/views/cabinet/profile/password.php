<?php
    
    
    use core\edit\forms\User\PasswordForm;
    use core\helpers\PrintHelper;
    use kartik\form\ActiveForm;
    use yii\base\InvalidConfigException;
    use yii\base\Object;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model PasswordForm */
    
    $layoutId = 'frontend_cabinet_profile_password';
    $this->title = 'Изменить пароль';
    $this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['cabinet/default/index']];
    $this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];


?>
<div class='col-md-6 offset-md-3 col-sm-12 content-body mb-4'>
    
    <?php
        $form = ActiveForm::begin(); ?>
    <div class="card">
        <div class="card-header text-center">
            <strong><?= Html::encode($this->title) ?></strong>
        </div>
        <div class="card-body">
            <?php
                try {
                    echo
                    $form->field($model, 'password')->passwordInput()->label('Введите новый пароль');
                }
                catch (InvalidConfigException $e) {
                    PrintHelper::exception($layoutId, $e);
                } ?>
            <?php
                try {
                    echo
                    $form->field($model, 'passwordRepeat')->passwordInput()->label('Повторите пароль');
                }
                catch (InvalidConfigException $e) {
                    PrintHelper::exception($layoutId, $e);
                } ?>
        </div>

        <div class="card-footer text-center">
            
            <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary']) ?>

        </div>
    </div>
    <?php
        ActiveForm::end(); ?>

</div>
