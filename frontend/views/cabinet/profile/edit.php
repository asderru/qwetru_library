<?php
    
    /* @var $this yii\web\View */
    /* @var $model common\core\forms\User\UserEditForm */
    /* @var $user common\core\entities\User\User */
    
    use kartik\form\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    $this->title = 'Изменить данные';
    
    $this->registerMetaTag(['name' => 'description', 'content' => $this->title]);
    $this->registerMetaTag(['name' => 'keywords', 'content' => $this->title]);
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
    
    $this->params['breadcrumbs'][] = ['label' => 'Личный кабинет', 'url' => ['cabinet/default/index']];
    $this->params['breadcrumbs'][] = 'Данные профиля';


?>
<div class="first-title">
    <h1><?= Html::encode($this->title) ?></h1>
</div>

<div class="col-md-8 offset-md-2 col-sm-12 content-body">
    <?php
        $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'nickname')->textInput(['maxLength' => true])->label('Сменить псевдоним на сайте') ?>
    <?= $form->field($model, 'email')->textInput(['maxLength' => true])->label('Сменить контактный емейл') ?>
    <hr>
    
    <?= Html::submitButton('Сохранить изменения', ['class' => 'btn btn-primary']) ?>
    <?php
        ActiveForm::end(); ?>
</div>
