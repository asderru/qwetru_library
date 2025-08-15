<?php

	use core\helpers\FormatHelper;
	use core\helpers\PrintHelper;
	use frontend\assets\AppAsset;
	use frontend\extensions\forms\ContactForm;
	use frontend\widgets\FeedbackWidget;
	use yii\bootstrap5\ActiveForm;
	use yii\helpers\Html;
	use yii\helpers\StringHelper;
	use yii\web\View;
	use yiidreamteam\upload\ImageUploadBehavior;
	use yii\helpers\Url;

	AppAsset::register($this);

	/* @var $this View */
	/* @var $model core\edit\entities\Admin\Information */
	/* @var $contactForm ContactForm */
	/* @var $id int */
	/* @mixin ImageUploadBehavior
	 */

	$this->title = 'Контакты';

	$this->params['title'] = $this->title;

?>

<div class='alert-box'>
	<?= $this->render(
			'/layouts/partials/_messages',
	) ?>
</div>

<!-- ###### Contact Section ###### -->
<section id='contact' class='contact section-bg'>
	<div class='container'>

		<div class='section-title'>
			<h2>Контакты</h2>
			<p>Всегда на связи в социальных сетях, мессенджерах и по телефону.
			   Для личной встречи предварительно заказывайте пропуск.</p>
		</div>

		<div class='row mt-5 justify-content-center'>
			<div class='col-lg-10'>
				<form
						action='forms/contact.php' method='POST' role='form'
						class='php-email-form'
				>
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

					<div class='row'>
						<div class='col-md-6 form-group'>

							<?= $form->field
							(
									$contactForm, 'name'
							)
							         ->textInput(
									         [
											         'maxlength'   => true,
											         'placeholder' => 'Ваше имя',
									         ]
							         )
							         ->label(false) ?>

						</div>
						<div class='col-md-6 form-group mt-3 mt-md-0'>
							<?= $form->field
							(
									$contactForm, 'email'
							)
							         ->textInput(
									         [
											         'maxlength'   => true,
											         'placeholder' => 'Ваше email',
									         ]
							         )
							         ->label(false) ?>
						</div>
					</div>
					<div class='form-group mt-3'>
						<?= $form->field
						(
								$contactForm, 'subject'
						)
						         ->textInput(
								         [
										         'maxlength'   => true,
										         'placeholder' => 'Тема сообщения',
								         ]
						         )
						         ->label(false) ?>
					</div>
					<div class='form-group mt-3'>
						<?= $form->field
						(
								$contactForm, 'subject'
						)
						         ->textarea(
								         [
										         'rows'        => 6,
										         'placeholder' => 'Сообщение',
								         ]
						         )
						         ->label(false) ?>
					</div>
					<div class='text-center'>
						<button type='submit'>Отправить сообщение!</button>
					</div>

					<?php
						ActiveForm::end(); ?>

				</form>
			</div>

		</div>

		<div class='row mt-5 justify-content-center'>

			<div class='col-lg-10'>

				<div class='info-wrap'>
					<div class='row'>
						<div class='col-lg-4 info'>
							<i class='bi bi-geo-alt'></i>
							<h4>Адрес:</h4>
							<p>Москва,<br>ул.Кантемировская, 59А</p>
						</div>

						<div class='col-lg-4 info mt-4 mt-lg-0'>
							<i class='bi bi-envelope'></i>
							<h4>Email:</h4>
							<p>info@britain.qwetru.ru<br>contact@britain.qwetru.ru</p>
						</div>

						<div class='col-lg-4 info mt-4 mt-lg-0'>
							<i class='bi bi-phone'></i>
							<h4>телефон:</h4>
							<p>+7 939 343 90 28</p>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

		<div class='map'>
		<iframe
				src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1126.0063872115675!2d37.643963890778736!3d55.636591898421315!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x414ab30769b18c1f%3A0xa7c09cd924eba4a9!2z0JrQsNC90YLQtdC80LjRgNC-0LLRgdC60LDRjyDRg9C7LiwgNTnQkCwg0JzQvtGB0LrQstCwLCAxMTU0Nzc!5e0!3m2!1sru!2sru!4v1678469048623!5m2!1sru!2sru'
				width='100%' height='350' style='border:0;' allowfullscreen=''
				loading='lazy' referrerpolicy='no-referrer-when-downgrade'
		></iframe>
	</div>
</section>

<!-- End Contact Section -->


<!--###### End of Main ######################################################-->
