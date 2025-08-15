<?php
    
    namespace frontend\controllers\cabinet;
    
    use core\read\read\models\User\UserModel;
    use Throwable;
    use Yii;
    use yii\filters\AccessControl;
    use yii\web\Response;
    
    class DefaultController extends MainController
    {
        public $layout = '@app/views/layouts/cabinet';
        
        private UserReadRepository $users;
        
        public function __construct(
            $id,
            $module,
            UserReadRepository $users,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->users = $users;
        }
        
        
        public function behaviors(): array
        {
            return [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }
        
        /**
         * @return string|Response
         * @throws Throwable
         */
        public function actionIndex(): string|Response
        {
            $user = Yii::$app->user;
            
            $identity = Yii::$app->user->identity; // Получаем объект Identity
            
            if ($identity) {
                $userName = $identity->getUsername();
            }
            else {
                $user->logout();
                Yii::$app->getSession()
                         ->setFlash(
                             'danger',
                             'Пользователь не определен! Обратитесь к администратору.',
                         )
                ;
            }
            
            $profile = $this->users->findMainPerson();
            
            return $this->render(
                '@app/views/cabinet/default/index',
                [
                    'userName' => $userName ?? 'noname',
                    'profile'  => $profile,
                ],
            );
        }
    }
