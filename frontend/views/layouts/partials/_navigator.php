<?php

    use core\tools\Constant;

    $layoutId = '#frontend_layouts_partials_navigator';
?>

<div id='header-wrap'>
    <header id='header'>
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-2'>
                    <div class='main-logo'>
                        <a href='index.html'><img src='/img/logo.png' alt='logo'></a>
                    </div>
                </div>
                <div class='col-md-10'>
                    <nav id='navbar'>
                        <div class='main-menu stellarnav'>
                            <ul class='menu-list'>
                                <li class='menu-item active'><a href='/'>Главная</a></li>
                                <li class='menu-item'><a href='/<?= Constant::BOOK_LABEL ?>/'>Книги</a></li>
                                <li class='menu-item'><a href='/<?= Constant::AUTHOR_LABEL ?>/'>Авторы</a></li>
                                <li class='menu-item'><a href='/contact/'>Контакты</a></li>
                            </ul>
                            <div class='hamburger'>
                                <span class='bar'></span>
                                <span class='bar'></span>
                                <span class='bar'></span>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>
</div><!--header-wrap-->
