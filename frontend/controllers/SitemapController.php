<?php
    
    namespace frontend\controllers;
    
    use core\edit\entities\Blog\Category;
    use core\edit\entities\Blog\Post;
    use core\edit\entities\Content\Page;
    use core\edit\entities\Library\Book;
    use core\edit\entities\Library\Chapter;
    use core\edit\entities\Seo\Faq;
    use core\edit\entities\Seo\Footnote;
    use core\edit\entities\Shop\Product\Product;
    use core\edit\entities\Shop\Razdel;
    use core\read\arrays\Blog\CategoryReader;
    use core\read\arrays\Blog\PostReader;
    use core\read\arrays\Content\PageReader;
    use core\read\arrays\Library\BookReader;
    use core\read\arrays\Library\ChapterReader;
    use core\read\arrays\Shop\ProductReader;
    use core\read\arrays\Shop\RazdelReader;
    use core\services\sitemap\IndexItem;
    use core\services\sitemap\MapItem;
    use core\services\sitemap\Sitemap;
    use core\tools\Constant;
    use Yii;
    use yii\caching\Dependency;
    use yii\caching\TagDependency;
    use yii\helpers\Url;
    use yii\web\Controller;
    use yii\web\RangeNotSatisfiableHttpException;
    use yii\web\Response;
    
    class SitemapController extends Controller
    {
        protected const int     ITEMS_PER_PAGE = 100;
        
        private Sitemap        $sitemap;
        private RazdelReader   $razdels;
        private ProductReader  $products;
        private PageReader     $pages;
        private CategoryReader $categories;
        private PostReader     $posts;
        private BookReader     $books;
        private ChapterReader  $chapters;
        
        public function __construct(
            $id,
            $module,
            Sitemap $sitemap,
            RazdelReader  $razdels,
            ProductReader $products,
            PageReader    $pages,
            CategoryReader $categories,
            PostReader    $posts,
            BookReader    $books,
            ChapterReader $chapters,
            $config = [],
        )
        {
            parent::__construct($id, $module, $config);
            $this->sitemap    = $sitemap;
            $this->razdels    = $razdels;
            $this->products   = $products;
            $this->pages      = $pages;
            $this->categories = $categories;
            $this->posts      = $posts;
            $this->books      = $books;
            $this->chapters   = $chapters;
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-index', function () {
                return $this->sitemap->generateIndex(
                    [
                        new IndexItem(
                            Url::to(
                                [
                                    'razdels',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'razdel-products-index',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'books',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'book-chapters-index',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'categories',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'categories-posts-index',
                                ], true,
                            ),
                        ),
                        new IndexItem(
                            Url::to(
                                [
                                    'pages',
                                ], true,
                            ),
                        ),
                    ],
                );
            },
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBooks(): Response
        {
            return $this->renderSitemap(
                'sitemap-books', function () {
                $books = $this->books->getArray(Constant::BOOK_TYPE, ['id', 'name', 'link', 'rating', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $book) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $book['link'],
                                ], true,
                            ) . '/',
                            $book['updated_at'],
                            ($book['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($book['rating'] < 10) ? .1 : $book['rating'] / 100,
                        );
                    }, $books),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Book::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBookChaptersIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-book-chapters-index', function () {
                $chapters      = $this->chapters->getArray(Constant::CHAPTER_TYPE, ['id', 'name', 'link', 'updated_at']);
                $totalChapters = count($chapters);
                
                return $this->sitemap->generateIndex(
                    array_map(static function ($start) {
                        return new IndexItem(
                            Url::toRoute(
                                [
                                    'book-chapters',
                                    'start' => $start * self::ITEMS_PER_PAGE,
                                ], true,
                            ),
                        );
                    }, range(0, (int)($totalChapters / self::ITEMS_PER_PAGE))),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Book::CACHE_TAG . 'all',
                            Chapter::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionBookChapters(int $start = 0): Response
        {
            return $this->renderSitemap(
                'sitemap-book-chapters' . $start,
                function () use ($start) {
                    $chapters = $this->chapters->getArray(Constant::CHAPTER_TYPE, ['id', 'name', 'link', 'updated_at']);
                    
                    // Slice the array to get only the items we need for this page
                    $pagedChapters = array_slice($chapters, $start, self::ITEMS_PER_PAGE, true);
                    
                    return $this->sitemap->generateMap(
                        array_map(static function (array $chapter) {
                            return new MapItem(
                                Url::toRoute(
                                    [
                                        $chapter['link'],
                                    ], true,
                                ) . '/',
                                $chapter['updated_at'],
                                MapItem::DAILY,
                            );
                        }, $pagedChapters),
                    );
                },
                new TagDependency(
                    [
                        'tags' => [
                            Book::CACHE_TAG . 'all',
                            Chapter::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdels(): Response
        {
            return $this->renderSitemap(
                'sitemap-razdels', function () {
                $razdels = $this->razdels->getArray(Constant::RAZDEL_TYPE, ['id', 'name', 'link', 'rating', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $razdel) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $razdel['link'],
                                ], true,
                            ) . '/',
                            $razdel['updated_at'],
                            ($razdel['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($razdel['rating'] < 10) ? .1 : $razdel['rating'] / 100,
                        );
                    }, $razdels),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Razdel::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdelProductsIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-razdel-products-index', function () {
                $products      = $this->products->getArray(Constant::PRODUCT_TYPE, ['id', 'name', 'link', 'updated_at']);
                $totalProducts = count($products);
                
                return $this->sitemap->generateIndex(
                    array_map(static function ($start) {
                        return new IndexItem(
                            Url::toRoute(
                                [
                                    'razdel-products',
                                    'start' => $start * self::ITEMS_PER_PAGE,
                                ], true,
                            ),
                        );
                    }, range(0, (int)($totalProducts / self::ITEMS_PER_PAGE))),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Razdel::CACHE_TAG . 'all',
                            Product::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionRazdelProducts(int $start = 0): Response
        {
            return $this->renderSitemap(
                'sitemap-razdel-products' . $start,
                function () use ($start) {
                    $products = $this->products->getArray(Constant::PRODUCT_TYPE, ['id', 'name', 'link', 'updated_at']);
                    
                    // Slice the array to get only the items we need for this page
                    $pagedProducts = array_slice($products, $start, self::ITEMS_PER_PAGE, true);
                    
                    return $this->sitemap->generateMap(
                        array_map(static function (array $product) {
                            return new MapItem(
                                Url::toRoute(
                                    [
                                        $product['link'],
                                    ], true,
                                ) . '/',
                                $product['updated_at'],
                                MapItem::DAILY,
                            );
                        }, $pagedProducts),
                    );
                },
                new TagDependency(
                    [
                        'tags' => [
                            Razdel::CACHE_TAG . 'all',
                            Product::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionCategories(): Response
        {
            return $this->renderSitemap(
                'sitemap-categories', function () {
                $categories = $this->categories->getArray(Constant::RAZDEL_TYPE, ['id', 'name', 'link', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $category) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $category['link'],
                                ], true,
                            ) . '/',
                            $category['updated_at'],
                            ($category['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($category['rating'] < 10) ? .1 : $category['rating'] / 100,
                        );
                    }, $categories),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Category::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionCategoryPostsIndex(): Response
        {
            return $this->renderSitemap(
                'sitemap-category-posts-index', function () {
                $posts      = $this->posts->getArray(Constant::PRODUCT_TYPE, ['id', 'name', 'link', 'updated_at']);
                $totalPosts = count($posts);
                
                return $this->sitemap->generateIndex(
                    array_map(static function ($start) {
                        return new IndexItem(
                            Url::toRoute(
                                [
                                    'category-posts',
                                    'start' => $start * self::ITEMS_PER_PAGE,
                                ], true,
                            ),
                        );
                    }, range(0, (int)($totalPosts / self::ITEMS_PER_PAGE))),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Category::CACHE_TAG . 'all',
                            Post::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionCategoryPosts(int $start = 0): Response
        {
            return $this->renderSitemap(
                'sitemap-category-posts' . $start,
                function () use ($start) {
                    $posts = $this->posts->getArray(Constant::PRODUCT_TYPE, ['id', 'name', 'link', 'updated_at']);
                    
                    // Slice the array to get only the items we need for this page
                    $pagedPosts = array_slice($posts, $start, self::ITEMS_PER_PAGE, true);
                    
                    return $this->sitemap->generateMap(
                        array_map(static function (array $post) {
                            return new MapItem(
                                Url::toRoute(
                                    [
                                        $post['link'],
                                    ], true,
                                ) . '/',
                                $post['updated_at'],
                                MapItem::DAILY,
                            );
                        }, $pagedPosts),
                    );
                },
                new TagDependency(
                    [
                        'tags' => [
                            Category::CACHE_TAG . 'all',
                            Post::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionPages(): Response
        {
            return $this->renderSitemap(
                'sitemap-pages', function () {
                $pages = $this->pages->getArray(Constant::PAGE_TYPE, ['id', 'name', 'link', 'rating', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $page) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $page['link'],
                                ], true,
                            ) . '/',
                            $page['updated_at'],
                            ($page['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($page['rating'] < 10) ? .1 : $page['rating'] / 100,
                        );
                    }, $pages),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Page::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionFaqs(): Response
        {
            return $this->renderSitemap(
                'sitemap-categories', function () {
                $categories = $this->categories->getArray(Constant::FAQ_TYPE, ['id', 'name', 'link', 'rating', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $faq) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $faq['link'],
                                ], true,
                            ) . '/',
                            $faq['updated_at'],
                            ($faq['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($faq['rating'] < 10) ? .1 : $faq['rating'] / 100,
                        );
                    }, $categories),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Faq::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        public function actionFootnotes(): Response
        {
            return $this->renderSitemap(
                'sitemap-posts', function () {
                $posts = $this->posts->getArray(Constant::FOOTNOTE_TYPE, ['id', 'name', 'link', 'rating', 'updated_at']);
                
                return $this->sitemap->generateMap(
                    array_map(static function (array $footnote) {
                        return new MapItem(
                            Url::toRoute(
                                [
                                    $footnote['link'],
                                ], true,
                            ) . '/',
                            $footnote['updated_at'],
                            ($footnote['rating'] < 50) ? MapItem::MONTHLY : MapItem::WEEKLY,
                            ($footnote['rating'] < 10) ? .1 : $footnote['rating'] / 100,
                        );
                    }, $posts),
                );
            },
                new TagDependency(
                    [
                        'tags' => [
                            Footnote::CACHE_TAG . 'all',
                        ],
                    ],
                ),
            );
        }
        
        
        /**
         * @throws RangeNotSatisfiableHttpException
         */
        private function renderSitemap(
            string      $key,
            callable    $callback,
            ?Dependency $dependency = null,
        ): Response
        {
            return Yii::$app->response->sendContentAsFile(
                Yii::$app->cache->getOrSet(
                    $key, $callback, null, $dependency,
                ), Url::canonical(), [
                'mimeType' => 'application/xml',
                'inline'   => true,
            ],
            );
        }
        
    }
