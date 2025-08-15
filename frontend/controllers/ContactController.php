<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Admin\Contact;
    use core\edit\forms\Admin\ContactForm;
    use core\edit\useCases\User\FeedbackManageService;
    use core\helpers\ParametrHelper;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\read\traits\BreadCrumbsTrait;
    use core\read\traits\SchemaTrait;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class ContactController extends MainController
    {
        protected const int        TEXT_TYPE      = Contact::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Contact::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Contact::MODEL_LABEL;
        protected const string     CACHE_TAG      = Contact::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Contact::DEFAULT_FIELDS;
        public                        $layout = '@app/views/layouts/main';
        private FeedbackManageService $service;
        
        public function __construct(
            $id,
            $module,
            FeedbackManageService $service,
            BreadcrumbsService $breadcrumbsService,
            MetaService        $metaService,
            SchemaService      $schemaService,
            $config = [],
        )
        {
            parent::__construct(
                $id, $module,
                $breadcrumbsService,
                $metaService,
                $schemaService,
                $config,
            );
            $this->service = $service;
        }

        public function actions(): array
        {
            return [
                'error' => [
                    'class'  => ErrorAction::class,
                    'layout' => '@app/views/layouts/error',
                ],
            ];
        }
        
        /**
         * @throws Exception|Throwable
         */
        public function actionIndex(): Response|string
        {
            
            $package = $this->sites->getFullPackedSite();
            $model   = ParametrHelper::getSite();
            $contact = $model->contact;
            if (!str_ends_with(Yii::$app->request->getUrl(), '/')) {
                return $this->redirect(Url::to(['contact/index',]));
            }
            $form = new ContactForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->createEmail($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Спасибо за сообщение.
                         Мы свяжемся с Вами в ближайшее время.',
                    );
                }
                catch (Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->
                    setFlash(
                        'danger',
                        'Что-то пошло не так.
                    Случилась ошибка. Попробуйте еще раз!',
                    );
                }
                return $this->refresh();
            }
            $mainTitle = 'Контакты ' . $model->name;
            
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render(
                '@app/views/contact/index',
                [
                    'contact'    => $contact,
                    'schemaData' => $schemaData,
                    'mainTitle'  => $mainTitle,
                    'textType'   => self::TEXT_TYPE,
                ],
            );
        }
    }
