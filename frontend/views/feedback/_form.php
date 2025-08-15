<?php

	use backend\assets\EditAsset;
	use backend\tools\TinyHelper;
	use core\helpers\ButtonHelper;
	use core\tools\Constant;
	use yii\helpers\Html;
	use yii\bootstrap5\ActiveForm;
	use Yii;

	/* @var $this yii\web\View */
	/* @var $model core\edit\entities\User\Feedback */
	/* @var $form yii\widgets\ActiveForm */

	$layoutId = 'feedback-form';

?>


<div class='card-body'>

	<?php
		$form = ActiveForm::begin(
				[
						'options'     => [
								'enctype' => 'multipart/form-data',
						],
						'fieldConfig' => [
								'errorOptions' => [
										'encode' => false,
										'class'  => 'help-block',
								],
						],
				]
		) ?>

	<div class='card-header bg-light'>
		<strong>
			Общая информация
		</strong>
	</div>
	<div class='card-body'>

		<?= $form->field($model, 'site_id')->textInput() ?>

		<?= $form->field($model, 'typeId')->textInput() ?>

		<?= $form->field($model, 'parentId')->textInput() ?>

		<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'theme')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

		<?= $form->field($model, 'status')->textInput() ?>

	</div>

	<?= ButtonHelper::submit() ?>

<?php
	ActiveForm::end(); ?>

</div>

<?= TinyHelper::getDescription() ?>

<?= TinyHelper::getText() ?>
