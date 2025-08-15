<?php
    
    namespace frontend\controllers\auth;
    
    use core\edit\useCases\Auth\PasswordResetService;
    use core\read\auth\PasswordResetRequestForm;
    use core\read\auth\ResetPasswordForm;
    use DomainException;
    use Yii;
    use yii\base\Exception;
    use yii\web\BadRequestHttpException;
    use yii\web\Response;
    
    class ResetController extends MainController
    {
        public $layout = 'auth';
        
        private PasswordResetService $service;
        
        public function __construct($id, $module, PasswordResetService $service, $config = [])
        {
            parent::__construct($id, $module, $config);
            $this->service = $service;
        }
        
        /**
         * @return Response|string
         */
        public function actionRequest(): Response|string
        {
            $form = new PasswordResetRequestForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $this->service->request($form);
                    Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                    return $this->goHome();
                }
                catch (DomainException|Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
            
            return $this->render('request', [
                'model' => $form,
            ]);
        }
        
        /**
         * @param $token
         * @return string|Response
         * @throws BadRequestHttpException
         */
        public function actionConfirm($token): Response|string
        {
            try {
                $this->service->validateToken($token);
            }
            catch (DomainException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }
            
            $form = new ResetPasswordForm();
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                try {
                    $this->service->reset($token, $form);
                    Yii::$app->session->setFlash('success', 'New password saved.');
                }
                catch (DomainException|Exception $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
                return $this->goHome();
            }
            
            return $this->render('confirm', [
                'model' => $form,
            ]);
        }
    }
