<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Blog\Category;
    use core\edit\search\Blog\PostSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Blog\CategoryReader;
    use core\read\models\Blog\CategoryModel;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\tools\Constant;
    use Exception;
    use frontend\controllers\admin\MainController;
    use InvalidArgumentException;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class CategoryController extends MainController
    {
        protected const int        TEXT_TYPE      = Category::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Category::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Category::MODEL_LABEL;
        protected const string     CACHE_TAG      = Category::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Category::DEFAULT_FIELDS;
        
        protected CategoryModel  $model;
        protected CategoryReader $reader;
        
        public function __construct(
            $id,
            $module,
            CategoryModel $model,
            CategoryReader $reader,
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
            try {
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
                return $this->render('@app/views/category/index', [
                    'root'     => $package['model'],
                    'models'   => $package['children'],
                    'textType' => self::TEXT_TYPE,
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
         * Displays a single Category model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedCategoryById($id);
            
            if (!$package) {
                Yii::$app->session->setFlash('error', TypeHelper::getName(self::TEXT_TYPE, null, true, true) . ' на сервере отсутствует!');
                return $this->redirect(Yii::$app->request->referrer ?: Url::home());
            }
            $searchModel  = new PostSearch();
            $dataProvider = $searchModel->searchByCategory(
                Yii::$app->request->queryParams,
                $id,
                'updated_at',
                Constant::STATUS_ACTIVE,
            );
            $pageSize     = Yii::$app->request->get('pageSize', 20);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            // Валидация структуры пакета
            $requiredKeys = [
                'model', 'children', 'parents', 'imageData',
                'tags', 'nextModel', 'prevModel', 'keywords',
            ];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $package)) {
                    Yii::$app->session->setFlash('error', 'Неверная структура данных: отсутствует ключ' . $key);
                    return $this->redirect(Yii::$app->request->referrer ?: Url::home());
                }
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package, $dataProvider);
            
            return $this->render('@app/views/category/view', [
                'model'        => $package['model'],
                'imageData'    => $package['imageData'],
                'children'     => $package['children'],
                'parents'      => $package['parents'],
                'tags'         => $package['tags'],
                'nextModel'    => $package['nextModel'],
                'prevModel'    => $package['prevModel'],
                'keywords'     => $package['keywords'],
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'textType'     => self::TEXT_TYPE,
            ]);
            
        }
        
    }
