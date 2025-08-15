<?php
    
    use core\edit\forms\User\FeedbackForm;
    use core\tools\Constant;
    use yii\bootstrap5\ActiveForm;
    use yii\bootstrap5\Html;
    use yii\bootstrap5\Modal;
    use yii\web\View;
    
    /* @var $feedbackForm core\edit\forms\User\FeedbackForm */
    /* @var $model array */
    
    Modal::begin([
        'id'            => 'feedbackModal',
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
        ],
        'options'       => [
            'aria-hidden' => 'false',
        ],
    ]);
    
    $form = ActiveForm::begin([
        'enableAjaxValidation' => false,
        'action'               => ['feedback/create'],
    ]);
?>

<div class="card feedback-widget">

    <div class="card-header feedback-widget__header">
        <h4>
            <span class="feedback-widget__tariff tariffName"></span>
        </h4>
    </div>

    <div class="card-body feedback-widget__body small">
        
        <?= Html::activeHiddenInput($feedbackForm, 'site_id', [
            'value' => Constant::SITE_ID,
        ]) ?>
        
        <?= $form->field($feedbackForm, 'userName')
                 ->textInput(['maxlength' => true, 'class' => 'form-control feedback-widget__input'])
                 ->label('Как к Вам обращаться', ['class' => 'form-label feedback-widget__label']) ?>
        
        <?= $form->field($feedbackForm, 'userCompany')
                 ->textInput(['maxlength' => true, 'class' => 'form-control feedback-widget__input'])
                 ->label('Название компании (при необходимости)', ['class' => 'form-label feedback-widget__label']) ?>
        
        <?= $form->field($feedbackForm, 'email')
                 ->textInput(['maxlength' => true, 'class' => 'form-control feedback-widget__input'])
                 ->label('Ваш email', ['class' => 'form-label feedback-widget__label']) ?>
        
        <?= $form->field($feedbackForm, 'phone')
                 ->textInput(['maxlength' => true, 'class' => 'form-control feedback-widget__input'])
                 ->label('Ваш телефон (+ код страны)', ['class' => 'form-label feedback-widget__label']) ?>
        
        
        <?php
            // Радио-группа со значками для выбора способа связи
            $contactTypeOptions = FeedbackForm::getContactTypeOptions();
            $contactTypeIcons   = [
                Constant::CONTACT_TYPE_PHONE    => 'bi bi-phone text-light',
                Constant::CONTACT_TYPE_TELEGRAM => 'bi bi-telegram text-info',
                Constant::CONTACT_TYPE_WHATSAPP => 'bi bi-whatsapp text-success',
            ];
        ?>

        <div class="col-12 mb-3">
            <?= $form->field($feedbackForm, 'contactType', [
                'options' => ['class' => ''],
            ])->radioList(
                $contactTypeOptions,
                [
                    'item' => function ($index, $label, $name, $checked, $value) use ($contactTypeIcons) {
                        $checked = $checked ? 'checked' : '';
                        $icon    = isset($contactTypeIcons[$value]) ? $contactTypeIcons[$value] : '';
                        
                        return "
                        <div class='form-check p-2 contact-type-radio mb-2'>
                            <input type='radio' id='{$name}_{$index}' name='{$name}' value='{$value}' class='form-check-input' {$checked}>
                            <label class='form-check-label' for='{$name}_{$index}'>
                                <i class='{$icon} me-2'></i> {$label}
                            </label>
                        </div>
                    ";
                    },
                ],
            )->label('Выберите способ связи с нами') ?>
        </div>
        
        <?= Html::activeHiddenInput($feedbackForm, 'feedbackType', [
            'value' => Constant::FEEDBACK_TYPE_ORDER,
        ]) ?>
        <?= Html::activeHiddenInput($feedbackForm, 'name', [
            'value' => 'Заказ на ' . $model['name'],
        ]) ?>
        
        <?= $form->field($feedbackForm, 'body')
                 ->textarea(['rows' => 4, 'class' => 'form-control feedback-widget__input'])
            ->label('Заметки к заявке', ['class' => 'form-label feedback-widget__label']) ?>
        
        <?= Html::activeHiddenInput($feedbackForm, 'textType', [
            'value' => $model['array_type'],
        ]) ?>
        
        <?= Html::activeHiddenInput($feedbackForm, 'parentId', [
            'value' => $model['id'],
        ]) ?>
        
        <?= Html::activeHiddenInput($feedbackForm, 'description', [
            'value' => 'Заявка на' . $model['name'],
        ]) ?>
        
        <?= Html::activeHiddenInput($feedbackForm, 'link', [
            'value' => 'Заявка по ссылке' . $model['link'],
        ]) ?>
        
        <?= Html::activeHiddenInput($feedbackForm, 'status', [
            'value' => Constant::STATUS_ROOT,
        ]) ?>

    </div>

    <div class="card-footer feedback-widget__note">
        <p class="small">
            Укажите Ваш E-mail или номер вашего контактного телефона.
            В случае, если вы укажете номер телефона, обозначьте в заметках к заявке время, когда Вам желательно
            получить от нас звонок.
            Если вы не укажете номер телефона, мы отправим необходимые инструкции на Вашу электронную почту.
        </p>
    </div>

    <div class="modal-footer feedback-widget__footer">
        <button
                type='button'
                class='feedback-widget__btn btn btn-sm btn-danger text-white'
                data-bs-dismiss='modal'
        >Закрыть
        </button>
        <?= Html::submitButton('Отправить заявку', [
            'class' => 'feedback-widget__btn btn btn-info text-white',
        ]) ?>
    </div>

</div>

<?php
    ActiveForm::end();
    Modal::end();
    
    $js = <<<JS
const feedbackModal = document.getElementById('feedbackModal');
if (feedbackModal) {
    feedbackModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        if (!button) return;

        const recipient = button.getAttribute('data-bs-feedback') || '';

        const modalFields = {
            name: feedbackModal.querySelector('#feedbackform-name'),
            title: feedbackModal.querySelector('.tariffName'),
            description: feedbackModal.querySelector('#feedbackform-description'),
            link: feedbackModal.querySelector('#feedbackform-link')
        };

        if (modalFields.name) {
            modalFields.name.value = recipient;
        }

        if (modalFields.title) {
            modalFields.title.textContent = recipient;
        }

        if (modalFields.description) {
            modalFields.description.value = 'Заявка от: ' + recipient;
        }

        if (modalFields.link) {
            modalFields.link.value = 'Заявка по ссылке' + window.location.pathname;
        }
    });
}
JS;
    
    $this->registerJs($js, View::POS_READY);
?>
