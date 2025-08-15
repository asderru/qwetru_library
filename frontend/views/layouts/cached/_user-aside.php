<?php
    
    use core\edit\entities\User\User;
    use yii\bootstrap5\Html;
    
    /* @var $user User */
    
    $layoutId = '#frontend_views_layouts_cached_userWidget';

?>
<div class='main-aside__widget main-aside__users'>

    <div class='aside-widget__header'>
        Полезные ссылки
    </div>

    <div class='aside-widget__body'>


        <ul class='aside-widget__list list-group list-group-flush'>

            <li class="list-group-item list-group-item__first">
                <?= Html::a(
                    'Выйти',
                    [
                        '/auth/auth/logout',
                    ],
                    [
                        'class' => 'list-item__link',
                    ],
                ) ?>
            </li>
            <li class="list-group-item list-group-item__first">
                <?= Html::a(
                    'Профиль',
                    [
                        '/cabinet/profile/view',
                    ],
                    [
                        'class' => 'list-item__link',
                    ],
                ) ?>
            </li>

            <li class="list-group-item list-group-item__first">
                
                <?= Html::a(
                    'Сменить пароль',
                    [
                        '/cabinet/profile/password',
                    ],
                    [
                        'class' => 'list-item__link',
                    ],
                ) ?>
            </li>

            <li class="list-group-item list-group-item__first">
                
                <?= Html::a(
                    'Сменить email',
                    [
                        '/cabinet/profile/email',
                    ],
                    [
                        'class' => 'list-item__link',
                    ],
                ) ?>
            </li>

            <li class="list-group-item list-group-item__first">
                
                <?= Html::a(
                    'Новости',
                    [
                        '/account/newsletter',
                    ],
                    [
                        'class' => 'list-item__link',
                    ],
                ) ?>

            </li>
        </ul>

    </div>
</div>
