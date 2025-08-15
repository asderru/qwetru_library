<?php
    
    namespace frontend\controllers\cabinet;
    
    use core\edit\entities\User\Person;
    use core\edit\entities\User\User;
    use core\edit\forms\UploadPhotoForm;
    use core\edit\forms\User\EmailForm;
    use core\edit\forms\User\PasswordForm;
    use core\edit\forms\User\PersonForm;
    use core\edit\repositories\User\PersonRepository;
    use core\edit\useCases\User\PersonManageService;
    use core\helpers\FormatHelper;
    use core\helpers\PrintHelper;
    use core\read\read\models\User\UserModel;
    use DomainException;
    use Exception;
    use Throwable;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    use yii\web\UploadedFile;
    
    class ProfileController extends MainController
    {
        public const int        TEXT_TYPE    = Person::TEXT_TYPE;
        public const string     MODEL_PREFIX = Person::MODEL_PREFIX;
        public const string     MODEL_LABEL  = Person::MODEL_LABEL;
        private PersonRepository    $repository;
        private PersonManageService $service;
        private UserReadRepository  $users;
        
        public function __construct(
            $id,
            $module,
            PersonRepository $repository,
            PersonManageService $service,
            UserReadRepository $users,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->users      = $users;
        }
        
        
        /**
         * Displays a single Person model.
         * @return Response|string
         * @throws Throwable
         */
        public function actionView(): Response|string
        {
            $model = $this->users->findMainPerson();
            
            if (!$model) {
                // Устанавливаем флеш-сообщение о неудаче
                Yii::$app->getSession()->setFlash('danger', 'Модель не найдена.');
                
                // Перенаправляем на страницу index
                return $this->redirect(['index']);
            }
            $mainTitle = FormatHelper::asHtml($model->title);
            
            $uploadForm = new UploadPhotoForm();
            
            if (Yii::$app->request->isPost) {
                $uploadForm->imageFile = UploadedFile::getInstance(
                    $uploadForm, 'imageFile',
                );
                if ($uploadForm->upload($model, self::TEXT_TYPE)) {
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ],
                    );
                }
            }
            
            return $this->render(
                '@app/views/cabinet/profile/view',
                [
                    'model'      => $model,
                    'uploadForm' => $uploadForm,
                    'mainTitle'  => $mainTitle,
                    'textType'   => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Creates a new Person model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Throwable
         */
        public function actionCreate(): string|Response
        {
            $user = $this->users->findUser();
            
            $form = new PersonForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $product = $this->service->create($form);
                    return $this->redirect(
                        [
                            'view',
                            'id' => $product->id,
                        ],
                    );
                }
                catch (DomainException $e) {
                    Yii::$app->errorHandler->logException($e);
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            return $this->render(
                '@app/views/cabinet/profile/create',
                [
                    'model'    => $form,
                    'user'     => $user,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Updates an existing Person model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @throws Throwable
         */
        public function actionUpdate(): string|Response
        {
            $profile = $this->users->findMainPerson();
            
            $form = new PersonForm($profile);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
                && $profile
            ) {
                try {
                    $this->service->edit($profile->id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Профиль отредактирован!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $profile->id,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            
            return $this->render(
                '@app/views/cabinet/profile/update',
                [
                    'profile'  => $profile,
                    'model'    => $form,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /*####### Photo ######################################################*/
        
        /**
         * @throws Exception
         */
        public function actionDeletePhoto(
            int $id,
        ): string|Response
        {
            $model = $this->repository::get($id);
            
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Модель  #' . $id . ' не найдена!',
                );
                return $this->redirect([
                    'index',
                ]);
            }
            
            (new UploadPhotoForm)::deletePhoto($model);
            $model->deletePhotos($model->slug);
            
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /**
         * @return string|Response
         * @throws NotFoundHttpException
         */
        public function actionPassword(): string|Response
        {
            $user = $this->findModel(Yii::$app->user->id);
            $form = new PasswordForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
                && $user
            ) {
                
                try {
                    $this->service->password($user->id, $form);
                    Yii::$app->getSession()->setFlash('success', 'Ваш пароль успешно изменен!');
                    return $this->redirect(['/cabinet/profile/index', 'id' => $user->id]);
                }
                catch (DomainException|Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
            return $this->render('password', [
                'model'    => $form,
                'textType' => self::TEXT_TYPE,
            ]);
        }
        
        /**
         * @return string|Response
         * @throws NotFoundHttpException
         */
        public function actionEmail(): string|Response
        {
            $user = $this->findModel(Yii::$app->user->id);
            $form = new EmailForm();
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
                && $user
            ) {
                
                try {
                    $this->service->email($user->id, $form);
                    Yii::$app->getSession()->setFlash('success', 'Ваш email успешно изменен!');
                    return $this->redirect(['/cabinet/profile/index', 'id' => $user->id]);
                }
                catch (DomainException|Throwable $e) {
                    Yii::$app->errorHandler->logException($e);
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
            return $this->render('email', [
                'model'    => $form,
                'textType' => self::TEXT_TYPE,
            ]);
        }
        
        /**
         * Finds the User model based on its primary key value.
         * If the model is not found, a 404 HTTP exception will be thrown.
         * @param integer $id
         * @return User|null the loaded model
         * @throws NotFoundHttpException if the model cannot be found
         */
        private function findModel(int $id): User|null
        {
            if (($model = User::findOne($id)) !== null) {
                return $model;
            }
            
            throw new NotFoundHttpException('Искомый пользователь отсутствует.');
        }
    }
