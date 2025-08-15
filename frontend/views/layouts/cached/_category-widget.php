<?php
    
    use frontend\widgets\AsideCategoryWidget;
    
    /* @var $categoryId int */
    
    $layoutId = '#frontend_views_layouts-cached_category_widget';

?>
<div class='main-aside__widget main-aside__razdels'
     itemscope itemtype='http://schema.org/WebPageElement'>

    <div class='aside-widget__header' itemprop='headline'>
        Темы блога
    </div>

    <div class='aside-widget__body'>
        
        
        <?php
            echo
            AsideCategoryWidget::widget(
                [
                    'categoryId' => $categoryId,
                ],
            );
        ?>
    </div>
</div>
