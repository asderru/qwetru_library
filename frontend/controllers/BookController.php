<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Library\Book;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Library\BookReader;
    use core\read\models\Library\BookModel;
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
    
    class BookController extends MainController
    {
        protected const int        TEXT_TYPE      = Book::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Book::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Book::MODEL_LABEL;
        protected const string     CACHE_TAG      = Book::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Book::DEFAULT_FIELDS;
        
        protected BookModel  $model;
        protected BookReader $reader;
        
        public function __construct(
            $id,
            $module,
            BookModel $model,
            BookReader $reader,
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
                return $this->render('@app/views/book/index', [
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
                Yii::$app->session->setFlash('error', 'Произошла ошибка при загрузке библиотеки');
                return $this->redirect(['site/error']);
            }
        }
        
        
        /**
         * Displays a single Book model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedBookById($id);
            
            if (!$package) {
                throw new NotFoundHttpException(TypeHelper::getName(self::TEXT_TYPE, null, false, true) . ' на сервере отсутствует!');
            }
            $searchModel  = new ChapterSearch();
            $dataProvider = $searchModel->searchByBook(
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
            
            return $this->render('@app/views/book/view', [
                'model'        => $package['model'],
                'imageData'    => $package['imageData'],
                'children'     => $package['children'],
                'parents'      => $package['parents'],
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
                'tags'         => $package['tags'],
                'nextModel'    => $package['nextModel'],
                'prevModel'    => $package['prevModel'],
                'keywords'     => $package['keywords'],
                'textType'     => self::TEXT_TYPE,
            ]);
            
        }
        
    }
