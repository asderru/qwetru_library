<?php

	use core\helpers\PrintHelper;
	use yii\bootstrap5\Html;


	/* @var $root core\edit\entities\Content\Page */
	/* @var $models core\edit\entities\Content\Page[] */

	$this->title = $root->title;

	$this->params['breadcrumbs'][] = $this->title;

	$this->params['title'] = $this->title;

?>


<article class='major'>

	<section class='content'>

		<div class='content-block'>

			<div
					class='col-xl-8 offset-xl-2 col-lg-10 offset-lg-1
				col-md-12 content-added-blank'
			>

				<?php
					foreach (
							$models

							as $model
					):
						?>

						<div class='button-block'>
							<?= Html::a(
									$model->name,
									[
											'page/view',
											'id' => $model->id,
									],
							) ?>
						</div>
						<?php
					endforeach;
				?>

			</div>


		</div>

	</section>
</article>
