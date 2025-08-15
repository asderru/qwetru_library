<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    /* @var $model common\core\forms\Auth\ResetPasswordForm */
    
    use common\core\widgets\Alert;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    $this->title = 'Установка нового пароля';
    
    $this->registerMetaTag(['name' => 'description', 'content' => Html::encode($this->title)]);
    $this->registerMetaTag(['name' => 'keywords', 'content' => Html::encode($this->title)]);
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
    
    $this->params['breadcrumbs'][] = $this->title;

?>

<div class="container">
    <div class="first-title">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    
    <?= Alert::widget() ?>
    <div class="content-body">

        <p>Пожалуйста, установите новый пароль:</p>

        <div class="col-lg-5">
            <?php
                $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
            
            <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Установить', ['class' => 'btn btn-primary']) ?>
            </div>
            
            <?php
                ActiveForm::end(); ?>
        </div>
    </div>
</div>
