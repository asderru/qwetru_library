<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    /* @var $model SignupForm */
    
    use core\read\auth\SignupForm;
    use kartik\form\ActiveForm;
    use yii\bootstrap5\Html;
    
    $this->title = 'Регистрация';

?>

<article class='main-article'>

    <div class='main-article__content auth-area'>

        <div class='auth-area__card card'>

            <div class='auth-card__header card-header'>
                <h1 class='h4'>
                    <i class='bi bi-person-bounding-box'></i> Регистрация
                </h1>

            </div>

            <div class='auth-card__body card-body'>
                <?php
                    $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username')
                         ->label('Уникальный логин для входа на сайт')
                         ->textInput(
                             [
                                 'autofocus' => true,
                             ],
                         )
                ?>
                <?= $form->field($model, 'email')->label('Почта, на которую придет уведомление о регистрации') ?>
                <?= $form->field($model, 'password')->label('Пароль')->passwordInput() ?>


                <div class='text-center'>
                    <?= Html::submitButton(
                        'Зарегистрироваться',
                        [
                            'class' => 'btn btn-sm btn-dark',
                            'name'  => 'signup-button',
                        ],
                    )
                    ?>
                </div>
                <?php
                    ActiveForm::end(); ?>
            </div>

            <div class='auth-card__footer card-footer'>
                После регистрации в системе Вы получите письмо со ссылкой для подтверждения указанного Вами e-mail.
                <br>
                Скорее всего это письмо попадет в папку "Спам". Проверьте эту папку тоже.

            </div>
        </div>
    </div>
</article>
