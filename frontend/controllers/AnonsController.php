<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Seo\Anons;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\CacheHelper;
    use core\read\arrays\Seo\AnonsReader;
    use core\read\providers\Seo\AnonsProvider;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class AnonsController extends MainController
    {
        protected const int        TEXT_TYPE         = Anons::TEXT_TYPE;
        protected const string     MODEL_PREFIX      = Anons::MODEL_PREFIX;
        protected const string     MODEL_LABEL       = Anons::MODEL_LABEL;
        protected const string     CACHE_TAG         = Anons::CACHE_TAG;
        protected const array      DEFAULT_FIELDS    = Anons::DEFAULT_FIELDS;
        protected const bool       MODEL_CACHE_INDEX = Anons::CACHE_INDEX;
        protected const bool       MODEL_CACHE_VIEW  = Anons::CACHE_VIEW;
        
        private AnonsProvider $provider;
        private AnonsReader   $reader;
        
        public function __construct(
            $id,
            $module,
            AnonsProvider $provider,
            AnonsReader $reader,
            VisitManageService $visitService,
            BreadcrumbsService $breadcrumbsService,
            MetaService $metaService,
            SchemaService $schemaService,
            $config = [],
        )
        {
            parent::__construct(
                $id, $module,
                $visitService,
                $breadcrumbsService,
                $metaService,
                $schemaService, $config,
            );
            $this->provider           = $provider;
            $this->reader             = $reader;
            $this->visitService = $visitService;
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
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
        
        public function behaviors(): array
        {
            $behaviors = [];
            
            if (self::MODEL_CACHE_INDEX === true) {
                $behaviors[] = CacheHelper::modelIndex(self::TEXT_TYPE);
            }
            
            if (self::MODEL_CACHE_VIEW === true) {
                $behaviors[] = CacheHelper::modelView(self::TEXT_TYPE);
            }
            
            return $behaviors;
        }
        
        /**
         * @throws Exception|Throwable
         */
        public function actionIndex(): Response|string
        {
            $package = $this->reader::getFullPackedRoot();
            if (!$package) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Анонсы не обнаружены!',
                );
                return $this->redirect(
                    ['site/index'],
                );
            }
            
            $dataProvider = $this->provider->findActiveData();
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package, $dataProvider);
            
            // Возвращаем представление для страницы index
            return $this->render(
                '@app/views/anons/index',
                [
                    'root'         => $package['model'],
                    'dataProvider' => $dataProvider,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws Exception|Throwable
         */
        public function actionView(int $id): string|Response
        {
            $package = $this->reader->getFullPackedAnonsById($id);
            if (!$package) {
                // Устанавливаем флеш-сообщение о неудаче
                Yii::$app->getSession()->setFlash('danger', 'Модель с ID#' . $id . ' не найдена.');
                
                // Перенаправляем на страницу index
                return $this->redirect(['index']);
            }
            
            $model     = $package['model'];
            $nextModel = $package['nextModel'] ?? [] ? $package['nextModel'][0] ?? null : null;
            $prevModel = $package['prevModel'] ?? [] ? $package['prevModel'][0] ?? null : null;
            
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render(
                '@app/views/anons/view',
                [
                    'model'     => $model,
                    'prevModel' => $prevModel,
                    'nextModel' => $nextModel,
                    'textType'  => self::TEXT_TYPE,
                ],
            );
        }
        
    }
