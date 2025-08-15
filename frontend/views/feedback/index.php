<?php

	use core\helpers\ButtonHelper;
	use core\helpers\PrintHelper;
	use yii\grid\ActionColumn;
	use yii\grid\GridView;
	use yii\helpers\Html;

	/* @var $this yii\web\View */
	/* @var $searchModel \core\edit\search\User\FeedbackSearch */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$layoutId = 'feedback-index';

	$this->title                   = 'Feedbacks';
	$this->params['breadcrumbs'][] = $this->title;

?>
<div class='container-fluid p-0'>
	<small>
		feedback
	</small>

	<div class='card bg-light'>

		<div class='card-header bg-gray d-flex justify-content-between'>
			<div class='h4'>
				<?= Html::encode($this->title) ?>
			</div>
			<div>
				<?= ButtonHelper::create() ?>
				<?= ButtonHelper::refresh() ?>
				<?= ButtonHelper::collapse() ?>
			</div>
		</div>

		<div class='card-body collapse' id='collapseButtons'>
			<span class='small strong'>Упорядочить номенклатуру в разделах:
				</span>

		</div>
	</div>

	<div class='table-responsive'>
		<?php
			try {
				echo
				GridView::widget(
						[
								'pager'          => [
										'firstPageLabel' => 'в начало',
										'lastPageLabel'  => 'в конец',
								],
								'dataProvider'   => $dataProvider,
								'filterModel'    => $searchModel,
								'caption'        => Html::encode($this->title),
								'captionOptions' => [
										'class' => 'bg-secondary text-white p-2',
								],
								'layout'         => "{errors}\n{summary}\n{pager}\n{items}\n{pager}",
								'summaryOptions' => [
										'class' => 'bg-secondary text-white p-1',
								],
								'tableOptions'   => ['class' => 'table table-striped table-bordered'],
								'columns'        => [
										['class' => 'yii\grid\SerialColumn'],

										[
												'attribute' => 'name',
												'label'     => 'Название',
												'value'     => static function (
														core\edit\entities\User\Feedback $model
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

										'id',
										'site_id',
										'name',
										'theme',
										'subject',
										//'email:email',
										//'phone',
										//'type_id',
										//'parent_id',
										//'created_at',
										//'updated_at',
										//'notes',
										//'status',
										[
												'class'    => ActionColumn::class,
												'template' => '{update} {delete}',
										],
								],
						]
				);
			}
			catch (Throwable $e) {
				PrintHelper::exception(
						'GridView-widget ' . $layoutId, $e
				);
			} ?>
	</div>
</div>
