<?php

	use core\helpers\PrintHelper;
	use core\read\widgets\AlertWidget;

	foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
		//Проверим, существует ли сессия по ключу
		if (
			Yii::$app->getSession()
			         ->hasFlash($key)
		) {
			try {
				echo AlertWidget::widget(
					[
						'options' => [
							//В данном случае $key - это имя сообщения, а содержание - $message
							//Если имя сообщения не соответствует четырем названиям классов в alerts стилях, то
							//создаем один из классов как стиль по умолчанию.
							'class' => (in_array(
								$key,
								[
									'success', 'info', 'warning', 'danger',
								]
							) ? 'alert-' . $key : 'alert-info'),
						],
						'body'    => $message,
					]
				);
			}
			catch (Exception|Throwable $e) {
				PrintHelper::exception('AlertWidget', $e);
			}
		}
	}
