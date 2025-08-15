<?php
    
    use yii\bootstrap\BootstrapAsset;
    use yii\bootstrap5\BootstrapPluginAsset;
    use yii\web\JqueryAsset;
    
    $bundles = [
        JqueryAsset::class                    => [
            'sourcePath' => null,   // do not publish the bundle
            'js' => [
                [
                    'vendors/jquery.min.js',
                ],
            ],
        ],
        BootstrapPluginAsset::class           => [
            'sourcePath' => null,
            'js'         => [
                'vendors/bootstrap-5.3.7/js/bootstrap.bundle.min.js',
            ],
        ],
        BootstrapAsset::class                 => [
            'sourcePath' => null,
            'baseUrl'    => '/',
            'css'        => [
                [
                    'css/main.min.css',
                ],
            ],
        ],
        \yii\bootstrap5\BootstrapAsset::class => [
            'sourcePath' => null,
            'baseUrl'    => '/',
            'css'        => [
                [
                    'css/main.min.css',
                ],
            ],
        ],
    ];
    
    return $bundles;
