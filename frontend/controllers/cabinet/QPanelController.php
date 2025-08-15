<?php
    
    namespace frontend\controllers\cabinet;
    
    use core\edit\entities\Addon\Panel;
    use core\edit\forms\Addon\QPanelForm;
    use core\edit\forms\SortForm;
    use core\edit\repositories\Addon\QPanelRepository;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\search\Addon\BannerSearch;
    use core\edit\search\Addon\QPanelSearch;
    use core\edit\useCases\Addon\QPanelManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use DomainException;
    use Exception;
    use himiklab\sortablegrid\SortableGridAction;
    use JetBrains\PhpStorm\ArrayShape;
    use JetBrains\PhpStorm\Pure;
    use Throwable;
    use Yii;
    use yii\db\StaleObjectException;
    use yii\filters\VerbFilter;
    use yii\web\Response;
    
    /**
     * PanelController implements the CRUD actions for Panel model.
     */
    class QPanelController extends MainController
    {
        public const int        TEXT_TYPE    = Panel::TEXT_TYPE;
        public const string     MODEL_PREFIX = Panel::MODEL_PREFIX;
        public const string     MODEL_LABEL  = Panel::MODEL_LABEL;
        private QPanelRepository      $repository;
        private QPanelManageService   $service;
        private InformationRepository $sites;
        
        public function __construct(
            $id,
            $module,
            QPanelRepository $repository,
            QPanelManageService $service,
            InformationRepository $sites,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->repository = $repository;
            $this->service    = $service;
            $this->sites      = $sites;
        }
        
        /**
         * {@inheritdoc}
         */
        #[ArrayShape(['verbs' => 'array'])]
        public function behaviors(): array
        {
            return [
                'verbs' => [
                    'class'   => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ];
        }
        
        #[Pure]
        #[ArrayShape([
            'sort' => 'array',
        ])]
        public function actions(): array
        {
            return [
                'sort' => [
                    'class'     => SortableGridAction::className(),
                    'modelName' => Panel::className(),
                ],
            ];
        }
        
        /**
         * Lists all Panel models.
         * @return Response|string
         * @action PanelController_index
         */
        public function actionIndex(): Response|string
        {
            $actionId = '#addon_QPanelController_index';
            
            $searchModel  = new QPanelSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render(
                '@app/views/addon/panel/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Displays a single Panel model.
         * @param integer $id
         * @return Response|string
         * @action PanelController_view
         */
        public function actionView(int $id): Response|string
        {
            $actionId = '#addon_QPanelController_view';
            
            $model = $this->repository::get($id);
            
            return $this->render(
                '@app/views/addon/panel/view',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Creates a new Panel model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @action PanelController_create
         */
        public function actionCreate(): string|Response
        {
            $actionId       = '#addon_PanelController_create';
            $form           = new QPanelForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    if ($model) {
                        Yii::$app->session->
                        setFlash(
                            'success',
                            'Панель успешно создана!',
                        );
                        return $this->redirect(
                            [
                                'insert',
                                'id' => $model->id,
                            ],
                        );
                    }
                }
                catch (DomainException $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            
            return $this->render(
                '@app/views/addon/panel/create',
                [
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Updates an existing Panel model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action PanelController_update
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = '#addon_PanelController_update';
            $banner   = $this->repository::get($id);
            
            $form = new QPanelForm($banner);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->edit($id, $form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Изменения внесены в базу!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'view',
                            'id' => $banner->id,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            
            return $this->render(
                '@app/views/addon/panel/update',
                [
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
            
        }
        
        /**
         * Updates an existing Panel model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action PanelController_update
         */
        public function actionInsert(int $id): string|Response
        {
            $actionId = '#addon_PanelController_update';
            $model    = $this->repository::get($id);
            
            $searchModel  = new BannerSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            
            return $this->render(
                '@app/views/addon/panel/insert',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $model,
                    'actionId'     => $actionId,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        public function actionBanner(): ?Response
        {
            if (Yii::$app->request->isAjax) {
                $keylist = Yii::$app->request->get('keylist');
                $modelId = Yii::$app->request->get('modelId');
                
                $this->service->insert($keylist, $modelId);
                Yii::$app->getSession()
                         ->setFlash(
                             'success',
                             'Изменения внесены в базу!',
                         )
                ;
                return $this->redirect(
                    [
                        'view',
                        'id' => $modelId,
                    ],
                );
            }
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
        
        
        /**
         * Deletes an existing Panel model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action PanelController_delete
         */
        public function actionDelete(
            int $id,
        ): Response
        {
            $model = $this->repository::get($id);
            
            try {
                $this->service->remove($model->id);
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Баннер успешно удален!',
                );
            }
            catch (DomainException|StaleObjectException|Throwable $e) {
                PrintHelper::exception('Контроллер. Проблема с удалением баннера', $e);
            }
            
            return $this->redirect(
                (ClearHelper::getAction() !== 'view')
                    ?
                    Yii::$app->request->referrer
                    :
                    'index',
            );
        }
        
        /**
         * @param integer $id
         * @param int     $bannerId
         * @return mixed
         * @action ProductController_movePhotoUp
         */
        public function actionMoveBannerUp(int $id, int $bannerId): Response
        {
            $this->service->moveBannerUp($id, $bannerId);
            return $this->redirect(
                [
                    'view',
                    'id' => $id,
                ],
            );
        }
        
        /*####### Sort #######################################################*/
        
        /**
         * @throws Exception
         */
        public function actionResort(
            int|null $id,
        ):
        Response|string
        {
            $searchModel  = new QPanelSearch();
            $dataProvider = $searchModel->search(
                Yii::$app->request->queryParams,
            );
            
            $form = new SortForm();
            
            $site = $this->sites::get($id);
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $this->service->setSort($form);
                    Yii::$app->getSession()
                             ->setFlash(
                                 'success',
                                 'Сортировка проведена успешно!',
                             )
                    ;
                    return $this->redirect(
                        [
                            'resort',
                            'id' => $site->id,
                        ],
                    );
                }
                catch (DomainException $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            return $this->render(
                '@app/views/addon/panel/resort',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'site'         => $site,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws Exception
         */
        public function actionClearSort(int $id): void
        {
            $this->service::reorderSort($id);
            $this->redirect(
                [
                    'resort',
                    'id' => $id,
                ],
            );
        }
        
    }
