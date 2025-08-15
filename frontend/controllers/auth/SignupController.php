<?php
    
    namespace frontend\controllers\auth;
    
    use core\edit\useCases\Auth\SignupService;
    use core\read\auth\SignupForm;
    use DomainException;
    use Throwable;
    use Yii;
    use yii\base\Exception;
    use yii\filters\AccessControl;
    use yii\web\Response;
    
    class SignupController extends MainController
    {
        public $layout = 'auth';
        
        private SignupService $service;
        
        public function __construct($id, $module, SignupService $service, $config = [])
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        public function behaviors(): array
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'only'  => ['index'],
                    'rules' => [
                        [
                            'actions' => ['index', 'request'],
                            'allow'   => true,
                            'roles'   => ['?'],
                        ],
                    ],
                ],
            ];
        }
        
        /**
         * @return Response|string
         * @throws Throwable
         */
        public function actionRequest(): Response|string
        {
            $form = new SignupForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $this->service->signup($form);
                    Yii::$app->session->setFlash(
                        'success', '
                    Проверьте email. Там будет письмо от нас со ссылкой для подтверждения.
                    Скорее всего оно попадет в спам. Следуйте инструкциям в письме.',
                    );
                    return $this->render('congrat', [
                        'model' => $form,
                    ]);
                }
                catch (DomainException|Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
            return $this->render(
                '@app/views/auth/signup/request',
                [
                    'model' => $form,
                ],
            );
        }
        
        /**
         * @param string $token
         * @return Response
         * @throws Exception
         */
        public function actionConfirm(string $token): Response
        {
            try {
                $this->service->confirm($token);
                Yii::$app->session->setFlash('success', 'Ваш е-мейл подтвержден!');
                return $this->redirect(['auth/auth/login']);
            }
            catch (DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('danger', $e->getMessage());
            }
            return $this->goHome();
        }
    }
