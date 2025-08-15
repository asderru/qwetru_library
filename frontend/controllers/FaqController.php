<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Seo\Faq;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\CacheHelper;
    use core\read\arrays\Seo\FaqReader;
    use core\read\providers\Seo\FaqProvider;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class FaqController extends MainController
    {
        protected const int        TEXT_TYPE         = Faq::TEXT_TYPE;
        protected const string     MODEL_PREFIX      = Faq::MODEL_PREFIX;
        protected const string     MODEL_LABEL       = Faq::MODEL_LABEL;
        protected const string     CACHE_TAG         = Faq::CACHE_TAG;
        protected const array      DEFAULT_FIELDS    = Faq::DEFAULT_FIELDS;
        protected const bool       MODEL_CACHE_INDEX = Faq::CACHE_INDEX;
        protected const bool       MODEL_CACHE_VIEW  = Faq::CACHE_VIEW;
        
        private FaqProvider $provider;
        private FaqReader   $reader;
        
        public function __construct(
            $id,
            $module,
            FaqProvider $provider,
            FaqReader $reader,
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
            $this->visitService       = $visitService;
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
                    'Комментарии не обнаружены!',
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
                '@app/views/faq/index',
                [
                    'model'        => $package['model'],
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
    $package = $this->reader->getFullPackedFaqById($id);
    if (!$package) {
        Yii::$app->getSession()->setFlash('danger', 'Модель с ID#' . $id . ' не найдена.');
        return $this->redirect(['index']);
    }
    
    $model = $package['model'];
    $parents = $package['parents'] ?? [];
    
    // Поиск родителя с глубиной 1
    $firstParent = current(array_filter($parents, fn($item) => $item['depth'] === 1)) ?: $model;
    
    $grandParent = null;
    if (isset($firstParent['text_type'], $firstParent['parent_id'])) {
        $selected = ['id', 'name', 'slug', 'link', 'title', 'description'];
        $grandParent = $this->reader->getGrandParentArray(
            $firstParent['text_type'],
            $firstParent['parent_id'],
            $selected
        );
    }
    
    // Инициализация метаданных и сервисов
    $this->initializeWebPageServices($package);
    
    return $this->render('@app/views/faq/view', [
        'model' => $model,
        'parentModel' => $package['parentModel'] ?? null,
        'grandParent' => $grandParent ?? [],
        'children' => $package['children'] ?? [],
        'nextModel' => $package['nextModel'] ?? null,
        'prevModel' => $package['prevModel'] ?? null,
        'tags' => $package['tags'] ?? [],
        'textType' => self::TEXT_TYPE,
    ]);
}
        
    }
