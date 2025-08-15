<?php

	use yii\bootstrap5\Html;

	/* @var $this yii\web\View */
	/* @var $content array */
	/* @var $motto string */
	/* @var $razdel core\edit\entities\Shop\Razdel */
	/* @var $dataProvider yii\data\ActiveDataProvider */

	$title  = $this->params['title'];
	$this->params['title'] = $title;

?>

<?php

	$this->beginContent('@frontend/views/layouts/blank.php')

?>

	<?= $this->render(
			'/layouts/partials/_breadcrumbs'
	) ?>



<div class="container">
	<?= $this->render(
			'/layouts/partials/_messages'
	) ?>
</div>

<!--Razdel ################################################################# -->


<section class='inner-razdel'>
	<div class='container'>

		<?= $content ?>

	</div>
</section>

<!--End of Razdel ########################################################## -->


<?php
	$this->endContent() ?>
