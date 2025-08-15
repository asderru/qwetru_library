<?php
    
    use core\edit\entities\User\Profile;
    use yii\bootstrap5\Html;
    
    /* @var $this yii\web\View */
    /* @var $userName string */
    /* @var $profile Profile */
    
    $this->title                   = 'Личный кабинет';
    $this->params['breadcrumbs'][] = $this->title;

?>
<article class='main-article'>

    <div class='main-article__title'>
        <h1 class='h3'> Приветствуем,
            <?php
                if ($userName): echo Html::encode($userName);
                endif; ?>

        </h1>
    </div>

    <div class='main-article__content '>


        <div class='article-content__body'>
            <p>Платформа не хранит никаких персональных данных. Поэтому в личном кабинете никакой информации не
                хранится.</p>
            <p>Если Вам кажется, что пришло время изменить псевдоним, контактный e-mail или пароль, то мешать не будем.
                Это единственная информация о Вас, которую мы храним* и которую Вы можете поменять в любой момент. </p>
            <?php
                if (!$profile): ?>
                    <p>Для удобного общения на платформе предлагаем создать "Личный профиль", который можете заполнить
                        любой
                        информацией, кажущейся Вам не лишней.
                    </p>
                <?php
                endif; ?>

            <hr>
            <small>*Пароли мы храним в хэше. Если потеряете, восстановить не сможем.</small>

        </div>
    </div>
</article>
