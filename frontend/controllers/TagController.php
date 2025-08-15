<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Content\Tag;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\CacheHelper;
    use core\read\arrays\Content\TagReader;
    use core\read\providers\Content\TagProvider;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\tools\Constant;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class TagController extends MainController
    {
        protected const int        TEXT_TYPE         = Tag::TEXT_TYPE;
        protected const string     MODEL_PREFIX      = Tag::MODEL_PREFIX;
        protected const string     MODEL_LABEL       = Tag::MODEL_LABEL;
        protected const string     CACHE_TAG         = Tag::CACHE_TAG;
        protected const array      DEFAULT_FIELDS    = Tag::DEFAULT_FIELDS;
        protected const bool       MODEL_CACHE_INDEX = Tag::CACHE_INDEX;
        protected const bool       MODEL_CACHE_VIEW  = Tag::CACHE_VIEW;
        
        private TagProvider $provider;
        private TagReader   $reader;
        
        public function __construct(
            $id,
            $module,
            TagProvider $provider,
            TagReader $reader,
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
        public function actionView(string $slug): string|Response
        {
            $textTypes = [
                Constant::RAZDEL_TYPE, Constant::PRODUCT_TYPE, Constant::CHAPTER_TYPE, Constant::POST_TYPE,
                Constant::PAGE_TYPE,
                Constant::NEWS_TYPE,
            ];
            $package   = $this->reader->getFullPackedTagBySlug($slug, $textTypes);
            if (!$package) {
                // Устанавливаем флеш-сообщение о неудаче
                Yii::$app->getSession()->setFlash('danger', 'Метка #' . $slug . ' не найдена.');
                
                // Перенаправляем на страницу index
                return $this->redirect(['index']);
            }
            
            $model   = $package['model'];
            $parents = $package['parents'];
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render(
                '@app/views/tag/view',
                [
                    'model'     => $model,
                    'prevModel' => $package['prevModel'],
                    'nextModel' => $package['nextModel'],
                    'razdels'   => $parents['razdels'],
                    'products'  => $parents['products'],
                    'chapters'  => $parents['chapters'],
                    'pages'     => $parents['razdels'],
                    'posts'     => $parents['pages'],
                    'news'      => $parents['news'],
                    'textType'  => self::TEXT_TYPE,
                ],
            );
        }
        
    }
