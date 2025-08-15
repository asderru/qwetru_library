<?php
    
    namespace frontend\controllers\cabinet;
    
    use core\edit\entities\Addon\QBanner;
    use core\edit\forms\Addon\QBannerForm;
    use core\edit\forms\SortForm;
    use core\edit\repositories\Addon\QBannerRepository;
    use core\edit\repositories\Admin\InformationRepository;
    use core\edit\search\Addon\QBannerSearch;
    use core\edit\useCases\Addon\QBannerManageService;
    use core\helpers\ClearHelper;
    use core\helpers\PrintHelper;
    use core\tools\Constant;
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
     * QBannerController implements the CRUD actions for QBanner model.
     */
    class QBannerController extends MainController
    {
        public const int        TEXT_TYPE    = QBanner::TEXT_TYPE;
        public const string     MODEL_PREFIX = QBanner::MODEL_PREFIX;
        public const string     MODEL_LABEL  = QBanner::MODEL_LABEL;
        private QBannerRepository     $repository;
        private QBannerManageService  $service;
        private InformationRepository $sites;
        
        public function __construct(
            $id,
            $module,
            QBannerRepository $repository,
            QBannerManageService $service,
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
                    'modelName' => QBanner::className(),
                ],
            ];
        }
        
        /**
         * Lists all QBanner models.
         * @return Response|string
         * @action QBannerController_index
         */
        public function actionIndex(): Response|string
        {
            $actionId     = '#addon_QBannerController_index';
            $searchModel  = new QBannerSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            
            return $this->render(
                '@app/views/addon/banner/index',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'actionId'     => $actionId,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Displays a single QBanner model.
         * @param integer $id
         * @return Response|string
         * @action QBannerController_view
         */
        public function actionView(int $id): Response|string
        {
            $actionId = '#addon_QBannerController_view';
            
            $model = $this->repository::get($id);
            
            return $this->render(
                '@app/views/addon/banner/view',
                [
                    'model'    => $model,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Creates a new QBanner model.
         * If creation is successful, the browser will be redirected to the
         * 'view' page.
         * @return string|Response
         * @action QBannerController_create
         */
        public function actionCreate(): string|Response
        {
            $actionId = '#addon_QBannerController_create';
            
            $form           = new QBannerForm();
            $form->scenario = $form::SCENARIO_CREATE_POST;
            
            if (
                $form->load(Yii::$app->request->post())
                && $form->validate()
            ) {
                try {
                    $model = $this->service->create($form);
                    Yii::$app->session->
                    setFlash(
                        'success',
                        'Баннер успешно создан!',
                    );
                    return $this->redirect(
                        [
                            'view',
                            'id' => $model->id,
                        ],
                    );
                }
                catch (DomainException|StaleObjectException|Throwable $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            
            return $this->render(
                '@app/views/addon/banner/create',
                [
                    'model'    => $form,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * Updates an existing QBanner model.
         * If update is successful, the browser will be redirected to the
         * 'view' page.
         * @param integer $id
         * @return string|Response
         * @throws Exception
         * @action QBannerController_update
         */
        public function actionUpdate(int $id): string|Response
        {
            $actionId = '#addon_QBannerController_update';
            
            $banner = $this->repository::get($id);
            
            $form = new QBannerForm($banner);
            
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
                            'id'       => $banner->id,
                            'actionId' => $actionId,
                        ],
                    );
                }
                catch (DomainException|Exception|Throwable $e) {
                    PrintHelper::exception('Контроллер', $e);
                }
            }
            
            return $this->render(
                '@app/views/addon/banner/update',
                [
                    'model'    => $form,
                    'banner'   => $banner,
                    'actionId' => $actionId,
                    'textType' => self::TEXT_TYPE,
                ],
            );
            
        }
        
        /**
         * Deletes an existing QBanner model.
         * If deletion is successful, the browser will be redirected to the
         * 'index' page.
         * @param integer $id
         * @return Response
         * @action QBannerController_delete
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
        
        /*####### Sort #######################################################*/
        
        /**
         * @throws Exception
         */
        public function actionResort(
            int $typeId, int|null $id,
        ):
        Response|string
        {
            $searchModel  = new QBannerSearch();
            $dataProvider = $searchModel->searchSort(
                Yii::$app->request->queryParams,
                $typeId,
                $id ?? Constant::SITE_ID,
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
                '@app/views/addon/banner/resort',
                [
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'model'        => $form,
                    'site'         => $site,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        public function actionClearSort(int $id, int $typeId): void
        {
            $this->service::reorderSort($id, $typeId);
            $this->redirect(
                [
                    'resort',
                    'id'     => $id,
                    'typeId' => $typeId,
                ],
            );
        }
        
    }
