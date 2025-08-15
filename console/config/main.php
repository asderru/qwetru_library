<?php
    $params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php'),
    );
    
    return [
        'id'                  => 'app-console',
        'basePath'            => dirname(__DIR__),
        'bootstrap'           => [
            'log',
            'common\bootstrap\SetUp',
        ],
        'controllerNamespace' => 'console\controllers',
        'controllerMap'       => [
            'fixture' => [
                'class'     => 'yii\console\controllers\FixtureController',
                'namespace' => 'common\fixtures',
            ],
            'migrate' => [
                'class'          => 'fishvision\migrate\controllers\MigrateController',
                'autoDiscover'   => true,
                'migrationPaths' => [
                    '@vendor/yiisoft/yii2/rbac/migrations',
                ],
            ],
            'cache'   => [
                'class' => 'console\controllers\CacheController',
            ],
        
        ],
        'components'          => [
            'log'               => [
                'targets' => [
                    [
                        'class'  => 'yii\log\FileTarget',
                        'levels' => [
                            'error', 'warning',
                        ],
                    ],
                ],
            ],
            'consoleUrlManager' => require __DIR__ . '/urlManager.php',
            'urlManager'        => static function () {
                return Yii::$app->get('consoleUrlManager');
            },
        
        ],
        'aliases'             => [
            '@webroot' => dirname(__DIR__, 2) . '/frontend/web',
            '@web'     => '/',
        ],
        'params'              => $params,
    ];
