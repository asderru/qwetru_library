<?php

	/* @var $this yii\web\View */
	/* @var $title string */
	/* @var $content string */

	$title                 = $this->params['title'];
	$this->params['title'] = $title;

?>

<?php

	$this->beginContent('@frontend/views/layouts/blank.php')

?>

	<?= $this->render(
			'/layouts/partials/_breadcrumbs'
	) ?>


<!--Page ################################################################# -->

<div class="container">
	<?= $this->render(
			'/layouts/partials/_messages'
	) ?>
</div>

<section class='inner-page'>
	<div class='container'>

		<?= $content ?>

	</div>
</section>

<!--End of Page ########################################################## -->

<?php
	$this->endContent() ?>
