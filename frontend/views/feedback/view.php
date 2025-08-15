<?php

	use backend\widgets\UploadImageWidget;
	use backend\widgets\NoteWidget;
	use backend\widgets\PhotoWidget;
	use core\edit\forms\PhotosForm;
	use core\helpers\ButtonHelper;
	use core\helpers\FormatHelper;
	use core\helpers\PrintHelper;
	use core\helpers\StatusHelper;
	use core\tools\Constant;
	use yii\helpers\ArrayHelper;
	use yii\helpers\Html;
	use yii\widgets\DetailView;

	/* @var $this yii\web\View */
	/* @var $model core\edit\entities\User\Feedback */

	$layoutId = 'feedback-view';

	$this->title                   = $model->name;
	$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
	$this->params['breadcrumbs'][] = $this->title;

?>
<div class='container-fluid p-0'>

	<div class='card mb-3'>

		<div class='card-header bg-gray d-flex justify-content-between'>
			<div class='h4'>
				<?= Html::encode($this->title) ?>
			</div>
			<div>
				<?php
					try {
						echo
						StatusHelper::statusLabel
						(
								$model->status
						);
					}
					catch (Exception $e) {
						PrintHelper::exception('StatusHelper ' . $layoutId, $e);
					} ?>
				<?= ButtonHelper::update($model) ?>
				<?= ButtonHelper::collapse() ?>
			</div>
		</div>

		<div class='card-body collapse' id='collapseButtons'>


			<?= ButtonHelper::update($model, 'Редактировать') ?>
			<?= ButtonHelper::create() ?>
			<?= ButtonHelper::delete($model) ?>
		</div>

		<div class="card-body">

			<div class='row'>

				<div class='col-md-6'>
					<div class='card mb-3'>

						<div class='card-header bg-gray'>
							<strong>
								Информация
							</strong>
						</div>
						<div class='card-body'>

							<div class='table-responsive'>

								<?php
									try {
										echo DetailView::widget(
												[
														'model'      => $model,
														'attributes' => [
																'id',
																'site_id',
																'name',
																'theme',
																'subject',
																'email:email',
																'phone',
																'type_id',
																'parent_id',
																'created_at',
																'updated_at',
																'notes',
																'status',
														],
												]
										);
									}
									catch (Throwable $e) {
										PrintHelper::exception(
												'DetailView-widget ' . $layoutId, $e
										);
									} ?>
							</div>
						</div>
					</div>
					<div class='col-md-6'>

					</div>
				</div>

			</div>
		</div>

	</div>

</div>
