<?php

	/** @var yii\web\View $this */
	/** @var string $name */
	/** @var string $message */

	/** @var Exception $exception */

	use yii\bootstrap5\Html;

	$this->title = $name;

	$this->params['title']  = $this->title;
	$this->params['bookId'] = null;

?>

<h1 class="error-title">Случилась ошибка!</h1>

<div class="alert alert-danger">
	Сервер не справился с запросом и сообщает: <?= nl2br(
		Html::encode
		(
			$message
		)
	) ?>
</div>

<p>
	Имеет смысл вернуться <?= Html::a(
		'на главную страницу',
		'/'
	)
	?>
	и попробовать все сначала!
</p>
