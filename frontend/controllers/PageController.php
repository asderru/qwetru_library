<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Content\Page;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\TypeHelper;
    use core\read\arrays\Content\PageReader;
    use core\read\models\Content\PageModel;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use Exception;
    use frontend\controllers\admin\MainController;
    use InvalidArgumentException;
    use Throwable;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class PageController extends MainController
    {
        protected const int        TEXT_TYPE      = Page::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Page::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Page::MODEL_LABEL;
        protected const string     CACHE_TAG      = Page::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Page::DEFAULT_FIELDS;
        
        protected PageModel  $model;
        protected PageReader $reader;
        
        public function __construct(
            $id,
            $module,
            PageModel $model,
            PageReader $reader,
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
            return $this->render('@app/views/page/index', [
                'root'     => $package['model'],
                'models'   => $package['children'],
                'textType' => self::TEXT_TYPE,
            ]);
        }
        
        
        /**
         * Displays a single Page model.
         * @param integer $id
         * @return Response|string
         * @throws Exception
         * @throws Throwable
         */
        public function actionView(int $id): Response|string
        {
            // Получаем данные
            $package = $this->reader->getFullPackedPageById($id);
            
            if (!$package) {
                throw new NotFoundHttpException(TypeHelper::getName(self::TEXT_TYPE, null, false, true) . ' на сервере отсутствует!');
            }
            
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
            $this->initializeWebPageServices($package);
            
            return $this->render('@app/views/page/view', [
                'model'     => $package['model'],
                'imageData' => $package['imageData'],
                'children'  => $package['children'],
                'parents'   => $package['parents'],
                'tags'      => $package['tags'],
                'nextModel' => $package['nextModel'],
                'prevModel' => $package['prevModel'],
                'keywords'  => $package['keywords'],
                'textType'  => self::TEXT_TYPE,
            ]);
            
        }
        
    }
