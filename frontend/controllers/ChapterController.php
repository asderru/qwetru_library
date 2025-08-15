<?php
    
    namespace frontend\controllers;
    
    use core\edit\arrays\Library\BookReader;
    use core\edit\entities\Library\Chapter;
    use core\edit\search\Library\ChapterSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Library\ChapterReader;
    use core\read\models\Library\ChapterModel;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use InvalidArgumentException;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class ChapterController extends MainController
    {
        protected const int        TEXT_TYPE      = Chapter::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Chapter::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Chapter::MODEL_LABEL;
        protected const string     CACHE_TAG      = Chapter::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Chapter::DEFAULT_FIELDS;
        
        protected ChapterModel  $model;
        protected ChapterReader $reader;
        
        public function __construct(
            $id,
            $module,
            ChapterModel $model,
            ChapterReader $reader,
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
                $book         = null;
                $searchModel  = null;
                $dataProvider = null;
                // Получаем данные
                $package = $this->reader::getFullPackedRoot();
                if (!$package) {
                    Yii::$app->session->setFlash('error', TypeHelper::getName(self::TEXT_TYPE, null, true, true) . ' не обнаружены!');
                    return $this->redirect(Yii::$app->request->referrer ?: Url::home());
                }
                if ($slug) {
                    $book         = BookReader::getModelBySlug($slug);
                    $searchModel  = new ChapterSearch();
                    $dataProvider = $searchModel->searchByBook(
                        Yii::$app->request->queryParams,
                        $book['id'],
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
                return $this->render('@app/views/chapter/index', [
                    'root'         => $package['model'],
                    'book'         => $book,
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
         * Displays a single Chapter model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedChapterById($id);
            
            if (!$package || empty($package['model'])) {
                Yii::$app->session->setFlash('error', 'Неверная ссылка на главу №' . $id . '. Пост не найден');
                return $this->redirect(Yii::$app->request->referrer ?: Url::home());
            }
            
            // Валидация структуры пакета
            $requiredKeys = [
                'model', 'parents', 'author', 'imageData', 'tags', 'nextModel', 'prevModel', 'keywords',
            ];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $package)) {
                    Yii::$app->session->setFlash('error', 'Неверная структура данных: отсутствует ключ' . $key);
                    return $this->redirect(Yii::$app->request->referrer ?: Url::home());
                }
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render('@app/views/chapter/view', [
                'model'     => $package['model'],
                'book'      => current($package['parents']),
                'author'    => $package['author'],
                'tags'      => $package['tags'],
                'nextModel' => $package['nextModel'],
                'prevModel' => $package['prevModel'],
                'keywords'  => $package['keywords'],
                'textType'  => self::TEXT_TYPE,
            ]);
            
        }
        
    }
