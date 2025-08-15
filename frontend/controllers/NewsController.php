<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Seo\News;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\CacheHelper;
    use core\read\arrays\Seo\NewsReader;
    use core\read\providers\Seo\NewsProvider;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class NewsController extends MainController
    {
        protected const int        TEXT_TYPE         = News::TEXT_TYPE;
        protected const string     MODEL_PREFIX      = News::MODEL_PREFIX;
        protected const string     MODEL_LABEL       = News::MODEL_LABEL;
        protected const string     CACHE_TAG         = News::CACHE_TAG;
        protected const array      DEFAULT_FIELDS    = News::DEFAULT_FIELDS;
        protected const bool       MODEL_CACHE_INDEX = News::CACHE_INDEX;
        protected const bool       MODEL_CACHE_VIEW  = News::CACHE_VIEW;
        
        private NewsProvider $provider;
        private NewsReader   $reader;
        
        public function __construct(
            $id,
            $module,
            NewsProvider $provider,
            NewsReader $reader,
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
                    'Новости не обнаружены!',
                );
                return $this->redirect(
                    ['site/index'],
                );
            }
            $order        = ['updated_at' => SORT_DESC];
            $dataProvider = $this->provider->findActiveData(null, null, $order);
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package, $dataProvider);
            
            // Возвращаем представление для страницы index
            return $this->render(
                '@app/views/news/index',
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
            $package = $this->reader->getFullPackedNewsById($id);
            if (!$package) {
                // Устанавливаем флеш-сообщение о неудаче
                Yii::$app->getSession()->setFlash('danger', 'Модель с ID#' . $id . ' не найдена.');
                
                // Перенаправляем на страницу index
                return $this->redirect(['index']);
            }
            
            $model     = $package['model'];
            $parent    = current($package['parents']);
            $nextModel = $package['nextModel'];
            $prevModel = $package['prevModel'];
            
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render(
                '@app/views/news/view',
                [
                    'model'     => $model,
                    'parent'    => $parent,
                    'prevModel' => $prevModel,
                    'nextModel' => $nextModel,
                    'tags'      => $package['tags'],
                    'keywords'  => $package['keywords'],
                    'textType'  => self::TEXT_TYPE,
                ],
            );
        }
        
    }
