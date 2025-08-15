<?php
    
    use core\helpers\PrintHelper;
    use frontend\widgets\AsideTagWidget;
    
    /* @var $tagId int|null */
    
    $layoutId = '#frontend_views_layouts_cached_tagWidget';
?>

<div class='main-aside__widget main-aside__tags'
	 itemscope itemtype='http://schema.org/WPHeader'>
	<div class='aside-widget__header' itemprop='name'>
		Метки
	</div>
	
	<div class='aside-widget__body'>
		<?php
			try {
				echo
				AsideTagWidget::widget(
					[
						'tagId' => $tagId,
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
