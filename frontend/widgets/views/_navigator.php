<?php
    
    use core\tools\Constant;
    
    /* @var $books core\edit\entities\Library\Book[] */
    /* @var $categories core\edit\entities\Blog\Category[] */
    /* @var $pages core\edit\entities\Content\Page[] */
    /* @var $razdels core\edit\entities\Shop\Razdel[] */
    /* @var $activeId int */
    /* @var $textType int */
    
    $layoutId = '#frontend_views_layouts_partials_navigator';
    $textType = $this->params['textType'] ?? Constant::SITE_TYPE;
    $parentId = $this->params['parentId'] ?? Constant::SITE_ID;
?>
<ul class='navbar-nav'>
    <li class='nav-item dropdown'>
        <a class='nav-link dropdown-toggle'
           href='/<?= Constant::RAZDEL_PREFIX ?>' id='navbarDropdown1'
           role='button'
           data-bs-toggle='dropdown'
           aria-expanded='false'>
            Уровни
        </a>

        <ul class='dropdown-menu' aria-labelledby='navbarDropdown1'>
            <?php
                foreach ($razdels as $razdel):
                        ?>
                        <li>
                            <a class="dropdown-item" href="<?= $razdel['link'] ?>">
                                <?= $razdel['name'] ?>
                            </a>
                        </li>
                    <?php
                endforeach; ?>
        </ul>
    </li>
    <li class='nav-item dropdown'>
        <a class='nav-link dropdown-toggle'
           href='/<?= Constant::CATEGORY_PREFIX ?>' id='navbarDropdown2'
           role='button'
           data-bs-toggle='dropdown'
           aria-expanded='false'>
            Блоги
        </a>

        <ul class='dropdown-menu' aria-labelledby='navbarDropdown2'>
            <?php
                foreach ($categories as $category):
                    if ($category['depth'] === 1):
                        $active = ($textType === Constant::CATEGORY_TYPE && $category['id'] === $parentId) ? ' active' : '';
                        ?>
                        <li>
                            <a class="dropdown-item<?= $active ?>" href="<?= $category['link'] ?>">
                                <?= $category['name'] ?>
                            </a>
                        </li>
                    <?php
                    endif;
                endforeach; ?>
        </ul>
    </li>
    <li class='nav-item dropdown'>
        <a class='nav-link dropdown-toggle'
           href='/<?= Constant::BOOK_PREFIX ?>' id='navbarDropdown3'
           role='button'
           data-bs-toggle='dropdown'
           aria-expanded='false'>
            Библиотека
        </a>

        <ul class='dropdown-menu' aria-labelledby='navbarDropdown3'>
            <?php
                foreach ($books as $book):
                    if ($book['depth'] === 1):
                        $active = ($textType === Constant::BOOK_TYPE && $book['id'] === $parentId) ? ' active' : '';
                        ?>
                        <li>
                            <a class="dropdown-item<?= $active ?>" href="<?= $book['link'] ?>">
                                <?= $book['name'] ?>
                            </a>
                        </li>
                    <?php
                    endif;
                endforeach; ?>
            <li>
                <a class="dropdown-item<?= $active ?>" href="/<?= Constant::BOOK_PREFIX ?>">
                    Все книги по СЕО
                </a>
            </li>
        </ul>
    </li>
    <li class='nav-item dropdown'>
        <a class='nav-link dropdown-toggle'
           href='/services' id='navbarDropdown4'
           role='button'
           data-bs-toggle='dropdown'
           aria-expanded='false'>
            Информация
        </a>

        <ul class='dropdown-menu' aria-labelledby='navbarDropdown4'>
            <?php
                foreach ($pages as $page):
                    if ($page['depth'] === 1):
                        $active = ($textType === Constant::PAGE_TYPE && $page['id'] === $parentId) ? ' active' : '';
                        ?>
                        <li>
                            <a class="dropdown-item<?= $active ?>" href="<?= $page['link'] ?>">
                                <?= $page['name'] ?>
                            </a>
                        </li>
                    <?php
                    endif;
                endforeach; ?>
        </ul>
    </li>
</ul>
