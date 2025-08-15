<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Blog\Post;
    use core\edit\search\Blog\PostSearch;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Blog\CategoryReader;
    use core\read\arrays\Blog\PostReader;
    use core\read\models\Blog\PostModel;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\tools\Constant;
    use Exception;
    use frontend\controllers\admin\MainController;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class PostController extends MainController
    {
        protected const int        TEXT_TYPE      = Post::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Post::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Post::MODEL_LABEL;
        protected const string     CACHE_TAG      = Post::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Post::DEFAULT_FIELDS;
        
        protected PostModel  $model;
        protected PostReader $reader;
        
        public function __construct(
            $id,
            $module,
            PostModel $model,
            PostReader $reader,
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
                $category     = null;
                $searchModel  = null;
                $dataProvider = null;
                // Получаем данные
                $package = $this->reader::getFullPackedRoot();
                if (!$package) {
                    Yii::$app->session->setFlash('error', TypeHelper::getName(self::TEXT_TYPE, null, true, true) . ' не обнаружены!');
                    return $this->redirect(Yii::$app->request->referrer ?: Url::home());
                }
                if ($slug) {
                    $category     = CategoryReader::getModelBySlug($slug);
                    $searchModel  = new PostSearch();
                    $dataProvider = $searchModel->searchByCategory(
                        Yii::$app->request->queryParams,
                        $category['id'],
                        'updated_at',
                        Constant::STATUS_ACTIVE,
                    );
                    $pageSize     = Yii::$app->request->get('pageSize', 20);
                    
                    $dataProvider->pagination->pageSize = $pageSize;
                }
                
                // Валидация структуры пакета
                if (!isset($package['model'])) {
                    Yii::$app->session->setFlash('error', 'Неверная структура данных');
                    return $this->redirect(Url::home());
                }
                
                // Инициализация метаданных и сервисов
                $this->initializeWebPageServices($package);
                
                // Возвращаем представление
                return $this->render('@app/views/post/index', [
                    'root'         => $package['model'],
                    'category'     => $category,
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
         * Displays a single Post model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedPostById($id);
            
            if (!$package || empty($package['model'])) {
                Yii::$app->session->setFlash('error', 'Неверная ссылка на пост №' . $id . '. Пост не найден');
                return $this->redirect(Yii::$app->request->referrer ?: Url::home());
            }
            // Валидация структуры пакета
            $requiredKeys = [
                'model', 'parents', 'imageData', 'person', 'tags', 'nextModel', 'prevModel', 'keywords',
            ];
            foreach ($requiredKeys as $key) {
                if (!array_key_exists($key, $package)) {
                    Yii::$app->session->setFlash('error', 'Неверная структура данных: отсутствует ключ' . $key);
                    return $this->redirect(Yii::$app->request->referrer ?: Url::home());
                }
            }
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($package);
            
            return $this->render('@app/views/post/view', [
                'model'     => $package['model'],
                'category'  => current($package['parents']),
                'person'    => $package['person'],
                'tags'      => $package['tags'],
                'nextModel' => $package['nextModel'],
                'prevModel' => $package['prevModel'],
                'keywords'  => $package['keywords'],
                'textType'  => self::TEXT_TYPE,
            ]);
            
        }
        
    }
