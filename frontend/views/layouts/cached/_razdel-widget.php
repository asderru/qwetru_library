<?php
    
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use frontend\widgets\AsideRazdelWidget;
    
    /* @var $razdelId int */
    
    $layoutId = '#frontend_views_layouts_cached_razdelWidget';

?>
<div class='main-aside__widget main-aside__razdels'
     itemscope itemtype='http://schema.org/WebPageElement'>
	
	<div class='aside-widget__header' itemprop='name'>
		<?= Constant::RAZDEL_LABEL ?>
	</div>
	
	<div class='aside-widget__body'>
		
		<?php
            try {
                echo
                AsideRazdelWidget::widget(
                    [
                        'razdelId' => $razdelId,
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
