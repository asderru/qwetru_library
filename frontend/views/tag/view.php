<?php

	use core\helpers\PrintHelper;
	use core\tools\Constant;
	use frontend\assets\GalleryAsset;
	use frontend\widgets\AsidePageWidget;
	use frontend\widgets\AsideRazdelWidget;
	use frontend\widgets\AsideTagWidget;
	use yii\helpers\Html;

	GalleryAsset::register($this);

	/* @var $this yii\web\View */
	/* @var $root core\edit\entities\Shop\Product\Product */
	/* @var $model core\edit\entities\Content\Tag */
	/* @var $pages core\edit\entities\Content\Page[] */
	/* @var $razdels core\edit\entities\Shop\Razdel[] */
	/* @var $products core\edit\entities\Shop\Product\Product[] */

	$layoutId    = '#tag-view';
	$mainTitle   = 'Метки';
	$this->title = $model->name;
	$modelId     = $model->id;

	$this->params['breadcrumbs'][] = [
		'label' => $mainTitle,
		'url'   => [
			'tag/index',
		],
	];
	$this->params['breadcrumbs'][] = $model->name;
	$this->params['title']         = $this->title;

?>

<div class='row'>
	<article class='col-lg-9 main-article'>

		<div class='main-article__title'>
			<h1 class='h1'>
				<?= Html::encode($model->name) ?>
			</h1>

		</div>

		<div class='main-article__description'>

		</div>

		<section class='article-section__body'>

			<div class='article-section__title'>
				<?= Html::a(
					Html::encode(Constant::RAZDEL_LABEL),
					[
						'razdel/index',
					]
				) ?>
			</div>
			<div class='article-section__body'>
				<?php
					foreach (
						$razdels

						as $razdel
					) { ?>



						<?= $this->render(
							'/layouts/partials/_model',
							[
								'model' => $razdel,
								'url'   => 'razdel',
							]
						) ?>
					<?php
					} ?>
			</div>

		</section>

		<section class='article-section__body'>

			<div class='article-section__title'>
				<?= Html::a(
					Html::encode(Constant::PRODUCT_LABEL),
					[
						'product/index',
					]
				) ?>
			</div>
			<div class='article-section__body'>
				<?php
					foreach ($products as $product) { ?>

						<?= $this->render(
							'/layouts/partials/_model',
							[
								'model' => $product,
								'url'   => 'product',
							]
						) ?>
						<?php
					} ?>
			</div>

		</section>

		<section class='article-section__body'>

			<div class='article-section__title'>
				<?= Html::a(
					Html::encode(Constant::PAGE_LABEL),
					[
						'page/index',
					]
				) ?>
			</div>
			<div class='article-section__body'>
				<?php
					foreach ($pages as $page) { ?>

						<?= $this->render(
							'/layouts/partials/_model',
							[
								'model' => $page,
								'url'   => 'page',
							]
						) ?>

						<?php
					} ?>
			</div>

		</section>

	</article>

	<aside class='col-lg-3 main-aside'>

		<div class='main-aside_widget main-aside__models'>

			<div class='aside-widget__header'>
				<h3 class="h3"><?= Constant::PRODUCT_LABEL ?></h3>
			</div>

			<div class='aside-widget__body'>
				<?php
					try {
						echo
						AsideRazdelWidget::widget(
							[
								'razdelId' => $model->razdel_id,
							]
						);
					}
					catch (Throwable $e) {
						PrintHelper::saveDbDummy(
							'AsideRazdelWidget ' . $layoutId, $e
						);
					}
				?>
			</div>
		</div>

		<div class='main-aside_widget main-aside__pages'>
			<div class='aside-widget__header'>
				<h3 class="h3">Страницы</h3>
			</div>

			<div class='aside-widget__body'>

				<?php
					try {
						echo
						AsidePageWidget::widget(
							[
								'pageId' => null,
							]
						);
					}
					catch (Throwable $e) {
						PrintHelper::saveDbDummy(
							'AsidePageWidget ' . $layoutId, $e
						);
					}
				?>
			</div>
		</div>

		<div class='main-aside_widget main-aside__tags'>
			<div class='aside-widget__header'>
				<h3 class="h3">Метки</h3>
			</div>

			<div class='aside-widget__body'>
				<?php
					try {
						echo
						AsideTagWidget::widget(
							[
								'tagId' => null,
							]
						);
					}
					catch (Throwable $e) {
						PrintHelper::saveDbDummy(
							'AsideTagWidget ' . $layoutId, $e
						);
					}
				?>
			</div>
		</div>

	</aside>

</div>
