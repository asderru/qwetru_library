<?php

	use core\helpers\ClearHelper;
	use core\helpers\FormatHelper;
	use core\helpers\PhotoHelper;
	use core\helpers\PrintHelper;
	use core\tools\Constant;
	use frontend\extensions\helpers\BreadCrumbHelper;
	use frontend\widgets\AsidePageWidget;
	use yii\helpers\Html;

	/* @var $this yii\web\View */
	/* @var $root core\edit\entities\Content\Page */
	/* @var $model core\edit\entities\Content\Page */
	/* @var $childs core\edit\entities\Content\Page[] */

	$layoutId    = '#page-view';
	$mainTitle   = Constant::PAGE_LABEL;
	$this->title = FormatHelper::asHtml($model->title);

	$this->params['breadcrumbs'][] = BreadCrumbHelper::index($mainTitle);
	foreach ($model->parents()->all() as $parent) {
		if ($parent->depth > Constant::THIS_ROOT) {
			$this->params['breadcrumbs'][] = [
					'label' => $parent->name,
					'url'   => [
							'view',
							'id' => $parent->id,
					],
			];
		}
	}
	$this->params['breadcrumbs'][] = $model->name;
	$this->params['title']         = $this->title;

?>


<div class='row'>
	<div class='col-lg-9'>
		<article class='main-article'>

			<div class='article-title'>
				<h1 class='h1'>
					<?= $this->title ?> <?= $model->id ?>
				</h1>
			</div>

			<div class='article-media__block'>

				<div class='article-media__image'>

					<?= PhotoHelper::getImage($model, 9, null) ?>

				</div>

			</div>

			<div class='article-content__block'>
				<div class='article-content__title'>

				</div>

				<div class='article-content__body'>

					<?= FormatHelper::asHtml($model->text) ?>
				</div>
			</div>
		</article>
	</div>
	<div class="col-lg-3">
		<aside class='main-aside'>

				<?php
					try {
						echo
						AsidePageWidget::widget(
								[
										'pageId' => $model->id,
								]
						);
					}
					catch (Throwable $e) {
						PrintHelper::saveDbDummy(
								'AsidePageWidget ' . $layoutId, $e
						);
					}
				?>
		</aside>

	</div>

</div>
