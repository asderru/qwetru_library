<?php
    
    /* @var $this yii\web\View */
    /* @var $form yii\bootstrap5\ActiveForm */
    
    /* @var $model SignupForm */
    
    use core\read\auth\SignupForm;
    use yii\bootstrap5\Html;
    use yii\helpers\Url;
    
    $this->title = 'Регистрация пройдена успешно';
    
    $this->registerLinkTag(['rel' => 'canonical', 'href' => Url::canonical()]);
    
    $this->params['breadcrumbs'][] = $this->title;

?>
<article class='main-article'>

    <div class='main-article__content auth-area'>

        <div class='auth-area__card card'>

            <div class='auth-card__header card-header'>
                <?= Html::encode($this->title) ?>
            </div>

            <div class='auth-card__body card-body alert-area'>
                
                <?= $this->render(
                    '@app/views/layouts/partials/_messages',
                ) ?>
            </div>

            <div class='auth-card__footer card-footer'>
                
                <?= Html::a(
                    'Вход в аккаунт <i class="bi bi-box-arrow-in-right"></i>',
                    '/cabinet',
                    [
                        'class' => 'btn btn-m btn-dark',
                    ],
                )
                ?>
                </p>
            </div>
        </div>
    </div>
</article>
