<?php
    
    use core\read\auth\LoginForm;
    use frontend\assets\AppAsset;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $model LoginForm */
    
    AppAsset::register($this);
    
    $this->title = 'Вход в Личный кабинет';

?>
<article class='main-article'>

    <div class='main-article__content auth-area'>

        <div class='alert-area'>
            
            <?= $this->render(
                '@app/views/layouts/partials/_messages',
            ) ?>
        
    </div>
        <div class='auth-area__card card'>

            <div class='auth-card__header card-header'>

                <h1 class='h4'>
                    <i class='bi bi-unlock-fill'></i> Вход в аккаунт
                </h1>

            </div>

            <div class='auth-card__body card-body'>
                <form method="POST" class="register-form">
                    <?php
                        $form = ActiveForm::begin([
                            'id'     => 'model-form',
                            'method' => 'POST',
                        ]); ?>
                    <?= $form->field($model, 'username')->textInput(['placeholder' => 'логин'])->label(false) ?>
                    <?= $form->field($model, 'password')->label(false)->passwordInput(['placeholder' => 'пароль']) ?>
                    <?= $form->field($model, 'rememberMe')->checkbox(['value' => '1', 'checked ' => true])->label('запомнить на 30 дней') ?>
                    <div class="text-center">
                        <?= Html::submitButton(
                            'Войти',
                            [
                                'class' => 'btn btn-sm btn-dark',
                                'name'  => 'model-button',
                            ],
                        ) ?>
                    </div>
                    
                    <?php
                        ActiveForm::end(); ?>
                </form>
            </div>

            <div class='auth-card__footer card-footer'>
                
                <?= Html::a(
                    '<i class="bi bi-person-bounding-box"></i> Регистрация',
                    [
                        'auth/signup/request',
                    ],
                    [
                        'class' => 'auth-card__link',
                    ],
                )
                ?>

            </div>
        </div>

    </div>

</article>
