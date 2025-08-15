<?php
    
    use frontend\urls\AnonsRule;
    use frontend\urls\BookRule;
    use frontend\urls\BrandRule;
    use frontend\urls\CategoryRule;
    use frontend\urls\ChapterRule;
    use frontend\urls\FaqRule;
    use frontend\urls\NewsRule;
    use frontend\urls\PageRule;
    use frontend\urls\PersonRule;
    use frontend\urls\PostRule;
    use frontend\urls\ProductRule;
    use frontend\urls\RazdelRule;
    use frontend\urls\SeoRule;
    use frontend\urls\ThreadRule;
    use yii\web\UrlManager;
    
    /** @var array $params */
    /** @var string $pagePrefix */
    /** @var string $bookPrefix */
    /** @var string $chapterPrefix */
    
    return [
        'class'           => UrlManager::class,
        'hostInfo'        => $params['frontendHostInfo'],
        'baseUrl'         => $params['frontendHostInfo'],
        'enablePrettyUrl' => true,
        'showScriptName'  => false,
        'cache'           => false,
        'rules'           => [
            '' => 'site/index',
            
            ['pattern' => 'browserconfig', 'route' => 'site/browserconfig', 'suffix' => '.xml'],
            ['pattern' => 'site', 'route' => 'site/webmanifest', 'suffix' => '.webmanifest'],
            ['pattern' => 'robots', 'route' => 'site/robots', 'suffix' => '.txt'],
            
            
            [
                'class' => RazdelRule::class,
            ],
            
            $params['chapterPrefix'] . '/<slug:[\w\-]+>' => 'chapter/index',
            [
                'class' => ChapterRule::class,
            ],
            
            $params['bookPrefix'] => 'book/index',
            [
                'class' => BookRule::class,
            ],
            
            $params['productPrefix'] => 'product/index',
            [
                'class' => ProductRule::class,
            ],
            
            $params['pagePrefix'] => '/page/index',
            [
                'class' => PageRule::class,
            ],
            
            $params['brandPrefix'] => '/brand/index',
            [
                'class' => BrandRule::class,
            ],
            
            $params['categoryPrefix'] => 'category/index',
            [
                'class' => CategoryRule::class,
            ],
            
            $params['postPrefix'] => 'post/index',
            [
                'class' => PostRule::class,
            ],
            
            $params['faqPrefix']   => 'faq/index',
            [
                'class' => FaqRule::class,
            ],
            $params['anonsPrefix'] => 'faq/index',
            [
                'class' => AnonsRule::class,
            ],
            $params['personPrefix'] => 'person/index',
            [
                'class' => PersonRule::class,
            ],
            [
                'class' => ThreadRule::class,
            ],
            [
                'class' => NewsRule::class,
            ],
            [
                'class' => SeoRule::class,
            ],
            
            'contact' => 'contact/index',
            'rss.xml' => 'rss/index',
            [
                'pattern' => 'sitemap', 'route' => 'sitemap/index', 'suffix' => '.xml',
            ],
            [
                'pattern' => 'sitemap-<target:[a-z-]+>-<start:\d+>', 'route' => 'sitemap/<target>', 'suffix' => '.xml',
            ],
            
            [
                'pattern' => 'sitemap-<target:[a-z-]+>', 'route' => 'sitemap/<target>', 'suffix' => '.xml',
            ],
            
            'tags/<slug:[\w\-]+>' => 'tag/view',
            
            '<_c:[\w\-]+>'                       => '<_c>/index',
            '<_c:[\w\-]+>/<id:\d+>'              => '<_c>/view',
            '<_c:[\w\-]+>/<_a:[\w-]+>'           => '<_c>/<_a>',
            '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
        ],
    ];
