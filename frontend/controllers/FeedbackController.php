<?php
    
    namespace frontend\controllers;
    
    use core\edit\forms\User\FeedbackForm;
    use core\edit\useCases\User\FeedbackManageService;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
    use DomainException;
    use Yii;
    use yii\db\Exception;
    use yii\web\Controller;
    use yii\web\Response;
    
    /**
     * FeedbackController implements the CRUD actions for Feedback model.
     */
    class FeedbackController extends Controller
    {
        private FeedbackManageService $service;
        
        public function init(): void
        {
            parent::init();
            
            $this->enableCsrfValidation = !$this->isLocalEnv();
        }
        
        private function isLocalEnv(): bool
        {
            // Способ 1: через YII_ENV (предпочтительно, если настроено в index.php или .env)
            if (defined('YII_ENV') && YII_ENV === 'dev') {
                return true;
            }
            
            // Способ 2: по IP-адресу
            $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
            return in_array($remoteAddr, ['127.0.0.1', '::1']);
        }
        
        public function __construct(
            $id,
            $module,
            FeedbackManageService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        
        /**
         * Creates a new Feedback model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         */
        public function actionCreate(): string|Response
        {
            $form = new FeedbackForm();
            
            if ($form->load(Yii::$app->request->post())
            ) {
                if (!$form->validate()) {
                    Yii::error($form->getErrors(), 'feedback.service');
                    $errorMessage = json_encode($form->getErrors(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    throw new DomainException('Ошибки валидации: ' . $errorMessage);
                    
                }
                
                try {
                    $this->service->create($form);
                    $message = self::createMessage($form);
                    Yii::$app->session->setFlash('success', $message);
                }
                
                catch (DomainException|Exception $e) {
                    // Логируем детальную информацию об ошибке
                    Yii::error([
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => $e->getTraceAsString(),
                    ], 'feedback.error');
                    
                    PrintHelper::exception('Контроллер', $e);
                    
                    // Показываем ошибку пользователю
                    Yii::$app->session->setFlash(
                        'error',
                        'Произошла ошибка при отправке запроса. Попробуйте позже.',
                    );
                }
            }
            else {
                // Логируем ошибки валидации формы
                if ($form->hasErrors()) {
                    Yii::warning([
                        'errors'    => $form->getErrors(),
                        'post_data' => Yii::$app->request->post(),
                    ], 'feedback.validation');
                }
            }
            
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        /**
         * Creates a new Feedback model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @param int|null $id
         * @return string|Response
         */
        public function actionPartner(): string|Response
        {
            $form = new FeedbackForm();
            
            if ($form->load(Yii::$app->request->post())
            ) {
                if (!$form->validate()) {
                    Yii::error($form->getErrors(), 'feedback.service');
                    $errorMessage = json_encode($form->getErrors(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                    throw new DomainException('Ошибки валидации: ' . $errorMessage);
                    
                }
                
                try {
                    $this->service->applyParnter($form);
                    $message = self::create($form);
                    Yii::$app->session->setFlash('success', $message);
                }
                
                catch (DomainException|Exception $e) {
                    // Логируем детальную информацию об ошибке
                    Yii::error([
                        'message' => $e->getMessage(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => $e->getTraceAsString(),
                    ], 'feedback.error');
                    
                    PrintHelper::exception('Контроллер', $e);
                    
                    // Показываем ошибку пользователю
                    Yii::$app->session->setFlash(
                        'error',
                        'Произошла ошибка при отправке запроса. Попробуйте позже.',
                    );
                }
            }
            else {
                // Логируем ошибки валидации формы
                if ($form->hasErrors()) {
                    Yii::warning([
                        'errors'    => $form->getErrors(),
                        'post_data' => Yii::$app->request->post(),
                    ], 'feedback.validation');
                }
            }
            
            return $this->redirect(Yii::$app->request->referrer);
        }
        
        private static function createMessage(FeedbackForm $form): string
        {
            $baseName    = $form->name ?: null;
            $baseMessage = $baseName
                ? 'Заявка на позицию «' . $baseName . '» успешно отправлена!'
                :
                'Заявка успешно отправлена!';
            
            // Определяем способ связи
            if (!empty($form->phone)) {
                // Если указан телефон, определяем тип контакта
                $contactMessage = match ($form->contactType) {
                    Constant::CONTACT_TYPE_PHONE    => 'Мы с Вами обязательно свяжемся по телефону!',
                    Constant::CONTACT_TYPE_TELEGRAM => 'Мы с Вами обязательно свяжемся в Telegram!',
                    Constant::CONTACT_TYPE_WHATSAPP => 'Мы с Вами обязательно свяжемся в WhatsApp!',
                    default                         => 'Мы с Вами обязательно свяжемся!'
                };
            }
            elseif (!empty($form->email)) {
                // Если есть только email
                $contactMessage = 'Мы напишем письмо на Ваш email!';
            }
            else {
                // Если нет контактных данных
                $contactMessage = 'Мы с Вами обязательно свяжемся!';
            }
            
            return $baseMessage . $contactMessage;
        }
        
    }
