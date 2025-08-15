<?php

	use core\edit\entities\Shop\Product\Product;
	use core\helpers\ParametrHelper;
	use core\helpers\PrintHelper;
	use core\tools\Constant;
	use yii\bootstrap5\Html;
	use yii\grid\GridView;
	use yii\grid\SerialColumn;

	/* @var $root core\edit\entities\Content\Page */
	/* @var $models core\edit\entities\Content\Page[] */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$layoutId    = '#product-index';
	$this->title = $root->title;

	$this->params['breadcrumbs'][] = $this->title;

	$this->params['title'] = $this->title;

	try {
		echo
		GridView::widget(
			[
				'pager'          => [
					'firstPageLabel' => 'в начало',
					'lastPageLabel'  => 'в конец',
				],
				'dataProvider'   => $dataProvider,
				'caption'        => Constant::PRODUCT_LABEL,
				'captionOptions' => [
					'class' => 'text-start p-2',
				],
				'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
				'summaryOptions' => [
					'class' => 'bg-secondary text-white p-1',
				],
				'tableOptions'   => ['class' => 'table table-striped table-bordered'],
				'columns'        => [
					[
						'class' =>
							SerialColumn::class,
					],
					[
						'value'          => static function (Product $model) {
							return $model->mainPhoto
								?
								Html::img(
									$model->getCachedPhoto(3),
									[
										'class' => 'img-fluid',
									]
								)
								:
								'<i class="bi bi-eye-fill"></i>';
						},
						'label'          => false,
						'format'         => 'raw',
						'contentOptions' => [
							'style' => 'width:200px; white-space: normal;',
						],
					],
					[
						'attribute' => 'name',
						'label'     => 'Тариф',
						'value'     => static function (
							Product $model
						) {
							return Html::a(
								Html::encode
								(
									$model->name
								),
								[
									'view',
									'id' => $model->id,
								]
							);

						},
						'format'    => 'raw',
					],
					[
						'attribute' => 'razdel_id',
						'label'     => false,
						'filter'    =>
							ParametrHelper::getRazdelsMap(),
						'value'     => static function (Product $model) {
							return
								Html::a(
									$model->razdel->getDepthName(),
									[
										'razdel/view',
										'id' => $model->razdel_id,
									]
								);
						},
						'format'    => 'raw',
					],
					[
						'attribute' => 'price',
						'label'     => 'Цена',
					],
				],
			]
		);
	}
	catch (Throwable $e) {
		PrintHelper::exception(
			'GridView-widget ' . $layoutId, $e
		);
	}
