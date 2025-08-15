<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    /* @var $model common\core\forms\Auth\PasswordResetRequestForm */
    
    use common\core\widgets\Alert;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    $this->title = 'Сброс пароля';
    
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

        <p>Пожалуйста, укажите e-mail, указанный при регистрации. Мы пришлем ссылку для установки нового
            пароля.</p>

        <div class="col-lg-5">
            <?php
                $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>
            
            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Отправить ', ['class' => 'btn btn-primary']) ?>
            </div>
            
            <?php
                ActiveForm::end(); ?>

        </div>
    </div>
</div>
