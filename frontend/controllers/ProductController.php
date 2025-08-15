<?php
    
    namespace frontend\controllers;

    use core\edit\entities\Shop\Product\Product;
    use core\edit\search\Shop\ProductSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Shop\ProductReader;
    use core\read\arrays\Shop\RazdelReader;
    use core\read\models\Shop\ProductModel;
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
    
    class ProductController extends MainController
    {
        protected const int        TEXT_TYPE      = Product::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Product::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Product::MODEL_LABEL;
        protected const string     CACHE_TAG      = Product::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Product::DEFAULT_FIELDS;
        
        protected ProductModel  $model;
        protected ProductReader $reader;
        
        public function __construct(
            $id,
            $module,
            ProductModel $model,
            ProductReader $reader,
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
        public function actionIndex(?string $slug = null): Response|string
        {
            try {
                $razdel       = null;
                $searchModel  = null;
                $dataProvider = null;
                // Получаем данные
                $package = $this->reader::getFullPackedRoot();
                if (!$package) {
                    throw new NotFoundHttpException(
                        TypeHelper::getName(self::TEXT_TYPE, null, true, true) . ' не обнаружены!',
                    );
                }
                if ($slug) {
                    $razdel       = RazdelReader::getModelBySlug($slug);
                    $searchModel  = new ProductSearch();
                    $dataProvider = $searchModel->searchByRazdel(
                        Yii::$app->request->queryParams,
                        $razdel['id'],
                    );
                    $pageSize     = Yii::$app->request->get('pageSize', 20);
                    
                    $dataProvider->pagination->pageSize = $pageSize;
                }
                
                // Валидация структуры пакета
                if (!isset($package['model'])) {
                    throw new InvalidArgumentException('Неверная структура данных');
                }
                
                // Инициализация метаданных и сервисов
                $this->initializeWebPageServices($package);
                
                // Возвращаем представление
                return $this->render('@app/views/product/index', [
                    'root'         => $package['model'],
                    'razdel'       => $razdel,
                    'searchModel'  => $searchModel,
                    'dataProvider' => $dataProvider,
                    'textType'     => self::TEXT_TYPE,
                ]);
                
            }
            catch (NotFoundHttpException $e) {
                Yii::$app->session->setFlash('warning', $e->getMessage());
                return $this->redirect(['site/index']);
            }
            catch (Exception $e) {
                Yii::error($e->getMessage(), 'actionIndex');
                Yii::$app->session->setFlash('error', 'Произошла ошибка при загрузке страницы');
                return $this->redirect(['site/error']);
            }
        }
        
        /**
         * Displays a single Product model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedProductById($id);
            
            if (!$package) {
                throw new NotFoundHttpException(TypeHelper::getName(self::TEXT_TYPE, null, false, true) . ' на сервере отсутствует!');
            }
            
            // Валидация структуры пакета
            $requiredKeys = [
                'model', 'parents', 'imageData', 'tags', 'nextModel', 'prevModel', 'keywords',
            ];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $package)) {
                    throw new InvalidArgumentException("Неверная структура данных: отсутствует ключ '$key'");
                }
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render('@app/views/product/view', [
                'model'     => $package['model'],
                'razdel'    => current($package['parents']),
                'brand'     => $package['brand'],
                'tags'      => $package['tags'],
                'nextModel' => $package['nextModel'],
                'prevModel' => $package['prevModel'],
                'keywords'  => $package['keywords'],
                'textType'  => self::TEXT_TYPE,
            ]);
            
        }
        
    }
