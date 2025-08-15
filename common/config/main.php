<?php
    
    use common\bootstrap\SetUp;
    use yii\queue\LogBehavior;
    use yii\queue\redis\Queue;
    use yii\redis\Cache;
    use yii\redis\Connection;
    
    $params = array_merge(
        require(__DIR__ . '/params-local.php'),
    );
    return [
        'vendorPath' => '/var/www/vendor',
        'bootstrap'  => [
            'queue',
            SetUp::class,
        ],
        'language'   => 'ru-RU',
        'aliases'    => [
            //'@core' => '@vendor/shopnseo/core',
            '@bower' => '@vendor/bower-asset',
            '@npm'   => '@vendor/npm-asset',
        ],
        'components' => [
            'redis'         => [
                'class'      => Connection::class,
                //'unixSocket' => '/var/run/redis/redis.sock',
                'unixSocket' => null,
                'hostname'   => 'localhost',
                'port'       => 6379,
                'database'   => $params['redis'],
            ],
            'queue' => [
                'class'   => Queue::class,
                'as log'  => LogBehavior::class,
                'channel' => 'queue',
                'ttr'     => 120,
                'redis'   => 'redis', // Ссылка на компонент redis
            ],
            'cache'         => [
                'class'           => Cache::class,
                //'useMemcached' => true,
                'defaultDuration' => (YII_ENV_PROD)
                    ?
                    3600
                    :
                    5,
                'keyPrefix'       => $params['siteId'],
            ],
            'fileCache'     => [
                'class'           => 'yii\caching\FileCache',
                'cachePath'       => '@common/runtime/cache',
                'defaultDuration' => (YII_ENV_PROD)
                    ?
                    0
                    :
                    5,
                'keyPrefix'       => $params['siteId'],
            ],
            'dataCache'     => [
                'class' => 'yii\caching\DbDependency',
            ],
            'elasticsearch' => [
                'class' => 'yii\elasticsearch\Connection',
                'nodes' => [
                    [
                        'http_address' => '127.0.0.1:9200',
                    ],
                ],
            ],
            'authManager'   => [
                'class'           => 'yii\rbac\DbManager',
                'itemTable'       => '{{%auth_items}}',
                'itemChildTable'  => '{{%auth_item_children}}',
                'assignmentTable' => '{{%auth_assignments}}',
                'ruleTable'       => '{{%auth_rules}}',
            ],
            'formatter'     => [
                'locale'         => 'ru-RU',
                'timeZone'       => 'Europe/Moscow',
                'dateFormat'     => 'php:d-m-Y',
                'datetimeFormat' => 'php:d-m-Y в H:i:s',
                'timeFormat'     => 'php:H:i:s',
                'currencyCode'   => 'RUB',
            ],
        ],
    ];
