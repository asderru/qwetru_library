<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Shop\Razdel;
    use core\edit\search\Shop\ProductSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Shop\RazdelReader;
    use core\read\models\Shop\RazdelModel;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use InvalidArgumentException;
    use Throwable;
    use Yii;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class RazdelController extends MainController
    {
        protected const int        TEXT_TYPE         = Razdel::TEXT_TYPE;
        protected const string     MODEL_PREFIX      = Razdel::MODEL_PREFIX;
        protected const string     MODEL_LABEL       = Razdel::MODEL_LABEL;
        protected const string     CACHE_TAG         = Razdel::CACHE_TAG;
        protected const array      DEFAULT_FIELDS    = Razdel::DEFAULT_FIELDS;
        protected const bool       MODEL_CACHE_INDEX = Razdel::CACHE_INDEX;
        protected const bool       MODEL_CACHE_VIEW  = Razdel::CACHE_VIEW;
        
        protected RazdelModel  $model;
        protected RazdelReader $reader;
        
        public function __construct(
            $id,
            $module,
            RazdelModel $model,
            RazdelReader $reader,
            VisitManageService $visitService,
            BreadcrumbsService $breadcrumbsService,
            MetaService $metaService,
            SchemaService $schemaService,
            $config = [],
        )
        {
            parent::__construct(
                $id,
                $module,
                $visitService,
                $breadcrumbsService,
                $metaService,
                $schemaService,
                $config,
            );
            $this->model              = $model;
            $this->reader             = $reader;
            $this->visitService = $visitService;
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
        }
        
        /**
         * @throws Throwable
         */
        public function actionIndex(): Response|string
        {
            // Получаем данные
            $package = $this->reader::getFullPackedRoot();
            
            if (!$package) {
                throw new NotFoundHttpException(
                    TypeHelper::getName(self::TEXT_TYPE, null, true, true) . ' не обнаружены!',
                );
            }
            
            // Валидация структуры пакета
            if (!isset($package['model'], $package['children'])) {
                throw new InvalidArgumentException('Неверная структура данных');
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            // Возвращаем представление
            return $this->render('@app/views/razdel/index', [
                'root'     => $package['model'],
                'models'   => $package['children'],
                'textType' => self::TEXT_TYPE,
            ]);
        }
        
        
        /**
         * Displays a single Razdel model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedRazdelById($id);
            if (!$package) {
                throw new NotFoundHttpException(TypeHelper::getName(self::TEXT_TYPE, null, false, true) . ' на сервере отсутствует!');
            }
            $searchModel  = new ProductSearch();
            $dataProvider = $searchModel->searchByRazdel(
                Yii::$app->request->queryParams,
                $id,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 20);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            // Валидация структуры пакета
            $requiredKeys = [
                'model', 'children', 'parents', 'imageData', 'tags', 'nextModel', 'prevModel', 'keywords',
            ];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $package)) {
                    throw new InvalidArgumentException("Неверная структура данных: отсутствует ключ '$key'");
                }
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package, $dataProvider);
            
            return $this->render('@app/views/razdel/view', [
                'model'     => $package['model'],
                'imageData' => $package['imageData'],
                'children'  => $package['children'],
                'parents'   => $package['parents'],
                'products'  => $dataProvider->getModels(),
                'nextModel' => $package['nextModel'],
                'prevModel' => $package['prevModel'],
                'tags'      => $package['tags'],
                'keywords'  => $package['keywords'],
                'textType'  => self::TEXT_TYPE,
            ]);
            
        }
        
    }
