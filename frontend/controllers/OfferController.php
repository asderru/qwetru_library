<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Shop\Product\Offer;
    use core\edit\entities\Shop\Product\Product;
    use core\helpers\CacheHelper;
    use core\helpers\FormatHelper;
    use core\read\readModels\Shop\ProductReadRepository;
    use core\read\readModels\Shop\RazdelReadRepository;
    use core\read\traits\BreadCrumbsTrait;
    use core\read\traits\MetaTrait;
    use core\read\traits\SchemaTrait;
    use core\tools\Constant;
    use Exception;
    use frontend\assets\Bundles;
    use JetBrains\PhpStorm\NoReturn;
    use Throwable;
    use Yii;
    use yii\helpers\Url;
    use yii\web\NotFoundHttpException;
    use yii\web\Response;
    
    class OfferController extends MainController
    {
        use BreadCrumbsTrait;
        use MetaTrait;
        use SchemaTrait;
        
        protected const int        TEXT_TYPE      = Offer::TEXT_TYPE;
        protected const string     MODEL_PREFIX   = Product::MODEL_PREFIX;
        protected const string     MODEL_LABEL    = Offer::MODEL_LABEL;
        protected const string     CACHE_TAG      = Offer::CACHE_TAG;
        protected const array      DEFAULT_FIELDS = Product::DEFAULT_FIELDS;
        public                        $layout = 'product';
        private RazdelReadRepository  $razdels;
        private ProductReadRepository $repository;
        
        public function __construct(
            $id,
            $module,
            RazdelReadRepository $razdels,
            ProductReadRepository $repository,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->razdels    = $razdels;
            $this->repository = $repository;
        }
        
        
        public function behaviors(): array
        {
            return
                [
                    CacheHelper::modelView(self::TEXT_TYPE),
                ];
        }
        
        /**
         * @throws Exception|Throwable
         */
        #[NoReturn]
        public function actionIndex(): Response|string
        {
            if (!str_ends_with(Yii::$app->request->getUrl(), '/')) {
                return $this->redirect(Url::to(['offer/index',]));
            }
            
            $model = $this->repository::findRoot();
            if (!$model) {
                Yii::$app->session->
                setFlash(
                    'warning',
                    'Лендинги не обнаружены!',
                );
                return $this->redirect(
                    ['site/index'],
                );
            }
            $mainTitle    = FormatHelper::asHtml($model->title);
            $razdels      = $this->razdels::findMain();
            $dataProvider = $this->repository::findActiveData();
            $pageSize     = Yii::$app->request->get('pageSize', 10);
            
            $dataProvider->pagination->pageSize = $pageSize;
            
            $picture = $model->getPicture([3, 6, 12]);
            Bundles::setPreloadedTags(self::TEXT_TYPE, $picture);
            
            $schemaData = self::generateArticlesSchema($model, array_slice($dataProvider->getModels(), 0, 10));
            self::setRegistration($model, $keywords);
            self::registerBreadcrumbs($model);
            
            return $this->render(
                'index',
                [
                    'root'         => $model,
                    'razdels'      => $razdels,
                    'dataProvider' => $dataProvider,
                    'schemaData'   => $schemaData,
                    'mainTitle'    => $mainTitle,
                    'textType'     => self::TEXT_TYPE,
                ],
            );
        }
        
        /**
         * @throws NotFoundHttpException
         * @throws Exception|Throwable
         */
        public function actionView(int $id): null|string|Response
        {
            if (!str_ends_with(Yii::$app->request->getUrl(), '/')) {
                return $this->redirect(Url::to(['offer/view', 'id' => $id]));
            }
            
            if (!$product = $this->repository::find($id)) {
                throw new NotFoundHttpException(
                    'Запрос не прошел. Это ошибка ' . $product->id,
                );
            }
            $mainTitle = FormatHelper::asHtml($product->title);
            $relateds  = $product->getRelatedProducts()
                                 ->andWhere(
                                     [
                                         '>=',
                                         'status',
                                         Constant::STATUS_ACTIVE,
                                     ],
                                 )
                                 ->all()
            ;
            
            $picture = $product->getPicture([3, 6, 12]);
            Bundles::setPreloadedTags(self::TEXT_TYPE, $picture);
            
            $imgBackground = Bundles::getMainPictureUrl($picture);
            
            $schemaData = self::generateArticleSchema($product);
            self::setRegistration($model, $keywords);
            self::registerBreadcrumbs($product);
            
            return $this->render(
                'view',
                [
                    'product'       => $product,
                    'imgBackground' => $imgBackground,
                    'relateds'      => $relateds,
                    'schemaData'    => $schemaData,
                    'mainTitle'     => $mainTitle,
                    'textType'      => self::TEXT_TYPE,
                ],
            );
        }
        
    }
