<?php
    
    namespace frontend\controllers\cabinet;
    
    use core\edit\entities\User\Inbox;
    use core\edit\entities\User\User;
    use core\edit\forms\User\InboxForm;
    use Yii;
    use yii\db\Exception;
    use yii\filters\AccessControl;
    use yii\web\Response;
    
    class InboxController extends MainController
    {
        public const int        TEXT_TYPE = User::TEXT_TYPE;
        
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
        
        public function actionIndex($userId = null): string
        {
            $userId   = $userId ?? Yii::$app->user->id;
            $messages = Inbox::find()
                             ->where(['receiver_id' => $userId])
                             ->orderBy(['created_at' => SORT_DESC])
                             ->all()
            ;
            
            return $this->render('index', [
                'messages' => $messages,
            ]);
        }
        
        /**
         * @throws Exception
         */
        public function actionView(): Response|string
        {
            $model = new InboxForm();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Сообщение отправлено');
                return $this->redirect(['index']);
            }
            
            return $this->render('view', [
                'model' => $model,
            ]);
        }
    }
