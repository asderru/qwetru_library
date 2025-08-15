<?php
    
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use frontend\widgets\AsidePageWidget;
    
    /* @var $pageId int */
    
    $layoutId = '#frontend_views_layouts_cached_page_widget';

?>
<div class='main-aside__widget main-aside__pages' itemscope itemtype='http://schema.org/WebPageElement'>

    <div class='aside-widget__header' itemprop='headline'>
        <?= Constant::PAGE_LABEL ?>
    </div>
    <div class='row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-1 g-2'>
        <?php
            try {
                echo
                AsidePageWidget::widget(
                    [
                        'pageId' => $pageId,
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
</div>
