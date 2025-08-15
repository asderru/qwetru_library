<?php
    
    namespace frontend\controllers\auth;
    
    use common\auth\Identity;
    use core\edit\useCases\Auth\AuthService;
    use core\helpers\ParametrHelper;
    use core\read\auth\LoginForm;
    use DomainException;
    use Exception;
    use Yii;
    use yii\web\Response;
    
    class AuthController extends MainController
    {
        public $layout = 'main';
        
        private AuthService $service;
        
        public function __construct(
            $id,
            $module,
            AuthService $service,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        /**
         * @throws Exception
         */
        public function actionLogin(): Response|string
        {
            if (!Yii::$app->user->isGuest) {
                return $this->redirect('/cabinet/default/index');
            }
            
            $site      = ParametrHelper::getInformation();
            $mainTitle = 'Вход в личный кабинет. Сайт ' . $site->name;
            
            $form = new LoginForm();
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $user = $this->service->auth($form);
                    Yii::$app->user
                        ->login(
                            new Identity($user), $form->rememberMe
                            ?
                            Yii::$app->params['user.rememberMeDuration']
                            :
                            0,
                        )
                    ;
                    return $this->redirect('/cabinet/default/index');
                }
                catch (DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->
                    setFlash(
                        'danger', $e->getMessage(),
                    );
                }
            }
            return $this->render(
                'login',
                [
                    'model'     => $form,
                    'site'      => $site,
                    'mainTitle' => $mainTitle,
                    'textType'  => self::TEXT_TYPE,
                ],
            );
        }
        
        public function actionLogout(): Response
        {
            Yii::$app->user->logout();
            return $this->redirect('login');
        }
    }
