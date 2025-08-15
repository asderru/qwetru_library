<?php


	use backend\assets\EditAsset;
	use backend\tools\TinyHelper;
	use core\helpers\ButtonHelper;
	use core\helpers\FutureHelper;
	use yii\bootstrap5\Html;
	use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\edit\forms\User\FeedbackForm */


	$layoutId = 'feedback-create';
	
$this->title = 'Создать заявку';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class='container-fluid p-0'>

	<div class='card bg-light'>

		<div class='card-header bg-gray d-flex justify-content-between'>

			<div class='h5'>
		<?= Html::encode($this->title) ?>
			</div>
			<div>
				<?=  ButtonHelper::submit() ?>
			</div>

		</div>
		<div class="card-body">

		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
   
		</div>

	</div>

</div>

<?=  TinyHelper::getDescription(200) ?>

<?=  TinyHelper::getText() ?>
