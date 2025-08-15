<?php

	use yii\bootstrap5\Breadcrumbs;
				echo
				Breadcrumbs::widget(
						[
								'homeLink' => [
										'label' => 'Главная',
										'url'   => '@homepage',
										'title' => 'qwetru.ru',
								],
								'links'    => $this->params['breadcrumbs'] ?? [],
								'options'  => [
										'class' => '',
								],
						]
				);
