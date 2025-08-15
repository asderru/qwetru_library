<?php

	use backend\assets\EditAsset;
	use backend\tools\TinyHelper;
	use core\helpers\ButtonHelper;
	use core\helpers\FutureHelper;
	use core\helpers\PrintHelper;
	use yii\bootstrap5\ActiveForm;
	use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\edit\entities\User\Feedback */

	$layoutId = 'feedback-update';
	
$this->title = 'Правка Feedback: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Feedbacks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Правка';
?>

<div class='container-fluid p-0'>

	<div class='card bg-light'>

		<div class='card-header bg-gray'>
			<h3>
		<?= Html::encode($this->title) ?>
			</h3>
		</div>


	    <?= $this->render('_form', [
	        'model' => $model,
	    ]) ?>

	</div>
</div>

<?=  TinyHelper::getDescriptionSmall() ?>

<?=  TinyHelper::getText() ?>
