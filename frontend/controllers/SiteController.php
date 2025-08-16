<?php
    
    namespace frontend\controllers;
    
    use core\components\ItemSelector;
    use core\edit\entities\Admin\Information;
    use core\edit\useCases\User\VisitManageService;
    use core\helpers\PrintHelper;
    use core\read\arrays\Admin\ContentReader;
    use core\read\arrays\Admin\InformationReader;
    use core\read\arrays\Library\BookReader;
    use core\read\arrays\Seo\AnonsReader;
    use core\read\services\BreadcrumbsService;
    use core\read\services\MetaService;
    use core\read\services\SchemaService;
    use core\tools\Constant;
    use Exception;
    use frontend\assets\Bundles;
    use frontend\controllers\admin\MainController;
    use JetBrains\PhpStorm\ArrayShape;
    use Throwable;
    use Yii;
    use yii\web\ErrorAction;
    use yii\web\Response;
    
    class SiteController extends MainController
    {
        protected const int        TEXT_TYPE      = Information::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Information::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Information::MODEL_LABEL;
        protected const string     CACHE_TAG      = Information::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Information::DEFAULT_FIELDS;
        
        public                    $layout = '@app/views/layouts/main';
        private InformationReader $sites;
        private ContentReader $reader;
        private AnonsReader       $anonses;
        
        public function __construct(
            $id,
            $module,
            InformationReader $sites,
            ContentReader $reader,
            AnonsReader $anonses,
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
            $this->sites              = $sites;
            $this->reader = $reader;
            $this->anonses = $anonses;
            $this->visitService = $visitService;
            $this->breadcrumbsService = $breadcrumbsService;
            $this->metaService        = $metaService;
            $this->schemaService      = $schemaService;
        }
        
        #[ArrayShape([
            'error' => 'string[]',
        ])]
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
            return
                [
                    //  CacheHelper::index(),
                ];
        }
        
        /**
         * @throws \yii\db\Exception
         */
        public function actionIndex(): string|Response
        {
            $site = $this->sites->getFullPackedSite();
            
            $books     = BookReader::getArray(
                Constant::BOOK_TYPE,
            );
            $bookOfDay = ItemSelector::getItemForDay(
                $books,
            );
            $mainbooks = array_filter($books, function ($book) {
                return isset($book['status']) && $book['status'] > 3;
            });
            // Разбиваем массив массивов на отдельные переменные
            $viewData = [
                'site'      => current($site),
                'mainbooks' => $mainbooks,
                'bookOfDay' => $bookOfDay,
            ];
            $viewData['textType'] = self::TEXT_TYPE;
            // Инициализация метаданных и сервисов
            $this->initializeWebPageServices($site);
            
            return $this->render(
                '@app/views/site/index',
                $viewData,
            );
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        
        public function actionError(): string|Response
        {
            try {
                $randomNumber = random_int(1, 15);
            }
            catch (Exception $e) {
                // Логируем исключение, устанавливаем значение по умолчанию
                PrintHelper::exception('randomNumber', $e);
                $randomNumber = 1; // значение по умолчанию, если генерация случайного числа не удалась
            }
            
            $imgBackground = "/img/error/error_col-12_" . $randomNumber . ".webp";
            Bundles::getPreloadedImagesFromUrl($imgBackground, self::TEXT_TYPE);
            
            $exception = Yii::$app->errorHandler->exception;
            
            if ($exception !== null) {
                // Обработка ошибки и отображение соответствующего представления
                $message = $exception->getMessage();
                
                return $this->render(
                    'error',
                    [
                        'exception'     => $exception,
                        'message'       => $message,
                        'imgBackground' => $imgBackground,
                        'textType'      => self::TEXT_TYPE,
                    ],
                );
            }
            
            return $this->redirect(
                [
                    'index',
                ],
            );
        }
        
        public function getTextTypes(): array
        {
            return [
                Constant::BOOK_TYPE,
                Constant::CHAPTER_TYPE,
                Constant::CATEGORY_TYPE,
                Constant::POST_TYPE,
                Constant::PAGE_TYPE,
                Constant::GROUP_TYPE,
                Constant::THREAD_TYPE,
                Constant::SITE_TYPE,
                Constant::NEWS_TYPE,
                Constant::AUTHOR_TYPE,
                Constant::ANONS_TYPE,
                Constant::GALLERY_TYPE,
                Constant::REVIEW_TYPE,
                Constant::FAQ_TYPE,
                Constant::FOOTNOTE_TYPE,
            ];
        }
        
        public function getTypeLabels($textType): string
        {
            return match ($textType) {
                Constant::BOOK_TYPE     => 'books',
                Constant::CHAPTER_TYPE  => 'chapters',
                Constant::CATEGORY_TYPE => 'categories',
                Constant::POST_TYPE     => 'posts',
                Constant::PAGE_TYPE     => 'pages',
                Constant::GROUP_TYPE    => 'groups',
                Constant::THREAD_TYPE   => 'threads',
                Constant::SITE_TYPE     => 'sites',
                Constant::NEWS_TYPE     => 'news',
                Constant::AUTHOR_TYPE   => 'authors',
                Constant::ANONS_TYPE    => 'anonses',
                Constant::GALLERY_TYPE  => 'galleries',
                Constant::REVIEW_TYPE   => 'reviews',
                Constant::FAQ_TYPE      => 'faqs',
                Constant::FOOTNOTE_TYPE => 'footnotes',
                default                 => 'models',
            };
        }
        
    }
