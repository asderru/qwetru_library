<?php
    
    use yii\helpers\Url;

?>
<div class='white-navbar grey-border'>
    <nav class='navbar sns-navbar navbar-expand-lg' itemscope itemtype='https://schema.org/WPHeader'>
        <div class='container'>
            <a href='<?= Url::home(true) ?>' aria-label='на главную страницу'>
                <img class='navbar-logo img-fluid' src='/img/logo-medium.webp' width='256' height='50'
                     alt='asder es logo'>
            </a>
            <button class='navbar-toggler' type='button' data-bs-toggle='offcanvas' data-bs-target='#offcanvasNavbar'
                    aria-controls='offcanvasNavbar'
                    aria-label='Открыть меню навигации'>
                <svg xmlns='http://www.w3.org/2000/svg' width='60' height='60' fill='currentColor' class='bi bi-list'
                     viewBox='0 0 16 16' style='color: black;' aria-hidden='true'>
                    <path fill-rule='evenodd'
                          d='M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z'/>
                </svg>
            </button>
            <div class='offcanvas offcanvas-end' tabindex='-1' id='offcanvasNavbar'
                 aria-labelledby='offcanvasNavbarLabel'>
                <div class='offcanvas-header'>
                    <h5 class='offcanvas-title' id='offcanvasNavbarLabel'>Меню</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='offcanvas' aria-label='Закрыть'></button>
                </div>
                <div class='offcanvas-body'>
                    <ul id='navigator' class='nav navbar-nav ms-auto w-100 justify-content-end' itemscope
                        itemtype='https://schema.org/SiteNavigationElement'>

                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='/library/roman-peskov'
                               id='book2' data-bs-toggle='dropdown' aria-expanded='false' itemprop='url'>
                                    <span itemprop='name'>
                            Роман Песков</span>
                            </a>
                            <ul class='dropdown-menu' aria-labelledby='book2'>
                                <li>
                                    <a class='nav-link dropdown-item'
                                       href='/texts/prologue/na-poslednej-stranice/'
                                       itemprop='url'>
                                        <span itemprop='name'>пролог</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item'
                                       href='/texts/tom-i/nachalo-bylo-kislym/'
                                       itemprop='url'>
                                        <span itemprop='name'>Том I</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/texts/tom-ii/daleko-pozadi/'
                                       itemprop='url'>
                                        <span itemprop='name'>Том II</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item'
                                       href='/texts/tom-iii/proshlo-pyat-let/'
                                       itemprop='url'>
                                        <span itemprop='name'>Том III</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' href='/library/action'
                               id='book7' data-bs-toggle='dropdown' aria-expanded='false' itemprop='url'>
                                    <span itemprop='name'>
                            Экшн
                        </span>
                            </a>
                            <ul class='dropdown-menu' aria-labelledby='book7'>
                                <li>
                                    <a class='nav-link dropdown-item' href='/texts/1997/bank/'
                                       itemprop='url'>
                                        <span itemprop='name'>1997</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/texts/1999/ostrov/'
                                       itemprop='url'>
                                        <span itemprop='name'>1999</span>
                                    </a>
                                </li>
                            </ul>

                        </li>

                        <li class='nav-item'>
                            <a itemprop='url' class='nav-link' href='/library/teksty'>
                                <span itemprop='name'>Тексты</span>
                            </a>
                        </li>

                        <li class='nav-item dropdown'>
                            <a class='nav-link dropdown-toggle' id='info'
                               href='#'
                               data-bs-toggle='dropdown'
                               aria-expanded='false'
                               itemprop='url'>
                                <span itemprop='name'>info</span>
                            </a>
                            <ul class='dropdown-menu last-list__menu' aria-labelledby='info'>
                                <li>
                                    <a class='nav-link dropdown-item' href='/library/' itemprop='url'>
                                        <span itemprop='name'>Библиотека</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/faq/' itemprop='url'>
                                        <span itemprop='name'>Комментарии / FAQ</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/footnotes/' itemprop='url'>
                                        <span itemprop='name'>Заметки и примечания</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/threads/discussion/'
                                       itemprop='url'>
                                        <span itemprop='name'>Обсуждение</span>
                                    </a>
                                </li>
                                <li>
                                    <hr class='dropdown-divider'>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item'
                                       href='/chitat-online-besplatno/'
                                       itemprop='url'>
                                        <span itemprop='name'>О проекте</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/cooperation/' itemprop='url'>
                                        <span itemprop='name'>Сотрудничество</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/contact/' itemprop='url'>
                                        <span itemprop='name'>Контакты</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/cabinet/' itemprop='url'>
                                        <span itemprop='name'>Личный кабинет</span>
                                    </a>
                                </li>
                                <li>
                                    <a class='nav-link dropdown-item' href='/logout/'
                                       itemprop='url'>
                                        <span itemprop='name'>Выйти</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
    
    <?= $this->render(
        '@app/views/layouts/partials/_breadcrumbs',
    ) ?>
</div>
