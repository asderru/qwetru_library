<?php
    
    use core\helpers\PrintHelper;
    use frontend\widgets\AsidePostWidget;
    
    /* @var $postId int */
    
    $layoutId = '#frontend_views_layouts-cached_post_widget';

?>
<div class='main-aside__widget main-aside__posts'
     itemscope itemtype='http://schema.org/WebPageElement'>

    <div class='aside-widget__header' itemprop='headline'>
        Посты
    </div>
    
    
    <?php
        try {
            echo
            AsidePostWidget::widget(
                [
                    'postId' => $postId,
                ],
            );
        }
        catch (Throwable $e) {
            PrintHelper::saveDbDummy(
                $layoutId, $e,
            );
        }
    ?>
</div>
