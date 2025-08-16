<?php

    use core\helpers\ImageHelper;
    use core\tools\Constant;
    use frontend\assets\AppAsset;
    use yii\web\View;

    AppAsset::register($this);
    
    /* @var $this View */
    /* @var $site core\edit\entities\Admin\Information */
    /* @var $bookOfDay core\edit\entities\Library\Book */
    /* @var $mainBooks core\edit\entities\Library\Book[] */
    /* @var $pages core\edit\entities\Content\Page[] */
    /* @var $id int */

    // PrintHelper::print($bookOfDay);
?>


<section id='billboard'>

    <div class='container'>
        Книга дня
        <div class='row'>
            <div class='col-md-12'>

                <button class='prev slick-arrow'>
                    <i class='icon icon-arrow-left'></i>
                </button>

                <div class='main-slider pattern-overlay'>
                    <div class='slider-item'>
                        <div class='banner-content'>
                            <h2 class='banner-title'><?= $bookOfDay['name'] ?></h2>
                            <p><?= $bookOfDay['description'] ?></p>
                            <div class='btn-wrap'>
                                <a href='<?= $bookOfDay['link'] ?>' class='btn btn-outline-accent btn-accent-arrow'>читать<i
                                            class='icon icon-ns-arrow-right'></i></a>
                            </div>
                        </div><!--banner-content-->
                        <?= ImageHelper::getPicture($bookOfDay, 'banner-image', 'eager', 'portrait') ?>
                    </div><!--slider-item-->

                </div><!--slider-->

            </div>
        </div>
    </div>

</section>


<section id='featured-books' class='py-5 my-5'>
    <div class='container'>
        <div class='row'>
            <div class='col-md-12'>

                <div class='section-header align-center'>
                    <div class='title'>
                        <span>избранное</span>
                    </div>
                    <h2 class='section-title'>Самые читаемые</h2>
                </div>

                <div class='product-list' data-aos='fade-up'>
                    <div class='row'>

                        <?php
                            foreach ($mainBooks as $mainBook): ?>
                        <div class='col-md-3'>
                            <div class='product-item'>
                                <figure class='product-style'>
                                    <?= ImageHelper::getPicture($mainBook, 'product-item', null, 'portrait') ?>
                                </figure>
                                <figcaption>
                                    <h3><?= $mainBook['name'] ?></h3>
                                    <span><?= $mainBook['author_name'] ?></span>
                                </figcaption>
                            </div>
                        </div>
                            <?php
                            endforeach; ?>

                    </div><!--ft-books-slider-->
                </div><!--grid-->


            </div><!--inner-content-->
        </div>

        <div class='row'>
            <div class='col-md-12'>

                <div class='btn-wrap align-right'>
                    <a href='<?= Constant::BOOK_PREFIX ?>' class='btn-accent-arrow'>смотреть все книги <i
                                class='icon icon-ns-arrow-right'></i></a>
                </div>

            </div>
        </div>
    </div>
</section>
