<?php

	use core\helpers\FormatHelper;
	use yii\base\Model;
	use yii\helpers\Html;

	/* @var $model Model */
	/* @var $url string */
?>


<div class='article-section__body'>
	<div class='row'>
		<div class='col-md-4'>
			<div class='section-body__image'>
				<?= Html::a(
					Html::img(
						$model->getCachedPhoto(3),
						[
							'class' => 'img-fluid',
						]
					)
				)
				?>
			</div>

		</div>
		<div class="col-md-8">

			<div class='section-body__title'>
				<?= Html::encode($model->title) ?>
			</div>

			<div class='section-body__description'>
				<?= FormatHelper::asHtml(
					$model->description
				)
				?>
			</div>
			<div class='section-body__reference'>

				<?= Html::a(
					'Ознакомиться',
					[
						$url . '/view',
						'id' => $model->id,
					],
					[
						'class' => 'btn-info',
					]
				)
				?>
			</div>
		</div>
	</div>
</div>
