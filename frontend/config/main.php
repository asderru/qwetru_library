<?php
    /** @noinspection ALL */
    
    use yii\bootstrap5\LinkPager;
    
    $params = array_merge(
        require(__DIR__ . '/../../common/config/params.php'),
        require(__DIR__ . '/../../common/config/params-local.php'),
        require(__DIR__ . '/../../common/config/prefix-local.php'),
        require(__DIR__ . '/../../common/config/photos-local.php'),
        require(__DIR__ . '/params.php'),
        require(__DIR__ . '/params-local.php'),
        require(__DIR__ . '/bundles.php'),
    );
    return [
        'basePath'            => dirname(__DIR__),
        'bootstrap'           => [
            'log',
            'frontend\bootstrap\SetUp',
        ],
        'aliases'             => [
            '@homepage'   => $params['frontendHostInfo'],
            '@frontRoot'  => $params['frontendPath'],
            '@backHost'   => $params['backendHostInfo'],
            '@backRoot'   => $params['backendPath'],
            '@staticHost' => $params['staticHostInfo'],
            '@staticRoot' => $params['staticPath'],
            '@uploadHost' => $params['uploadHostInfo'],
            '@uploadRoot' => $params['uploadPath'],
            '@filesHost'  => $params['filesHostInfo'],
            '@filesRoot'  => $params['filesPath'],
            '@logsRoot'   => $params['logsPath'],
        ],
        'layout'              => '@app/views/layouts/blank',
        'controllerNamespace' => 'frontend\controllers',
        'container'           => [
            'definitions' => [
                \yii\widgets\LinkPager::class => LinkPager::class,
            ],
        ],
        'components'          => [
            'request'            => [
                'csrfParam'           => '_csrf-frontend',
                'cookieValidationKey' => $params['cookieValidationKey'],
                'csrfCookie'          => [
                    'httpOnly' => true,
                    'secure'   => true,
                    'sameSite' => yii\web\Cookie::SAME_SITE_LAX,
                ],
            ],
            'response'           => [
                'class'         => 'yii\web\Response',
                'on beforeSend' => function ($event) {
                    $response = $event->sender;
                    foreach ($response->cookies as $cookie) {
                        if ($cookie->name === '_ym_uid' || $cookie->name === '_ym_d') {
                            $cookie->sameSite = yii\web\Cookie::SAME_SITE_NONE;
                            $cookie->secure   = true;
                        }
                        else {
                            $cookie->httpOnly = true;
                            $cookie->secure   = true;
                            $cookie->sameSite = yii\web\Cookie::SAME_SITE_LAX;
                        }
                    }
                },
            ],
            'assetManager' => [
                'bundles'         => $bundles,
                'appendTimestamp' => true,
                'assetMap'        => [
                ],
            ],
            'user'               => [
                'identityClass'   => 'common\auth\Identity',
                'enableAutoLogin' => true,
                'identityCookie'  => [
                    'name'     => '_identity',
                    'httpOnly' => true,
                    'domain'   => $params['cookieDomain'],
                ],
                'loginUrl'        => ['/auth/auth/login'],
            ],
            'session'            => [
                'name'         => '_session',
                'class'        => 'yii\redis\Session',
                'cookieParams' => [
                    'domain'   => $params['cookieDomain'],
                    'httpOnly' => true,
                ],
            ],
            'log'                => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets'    => [
                    [
                        'class'       => 'yii\log\FileTarget',
                        'levels'      => ['error', 'warning'],
                        'logFile'     => '@logsRoot/frontend.log',
                        'maxLogFiles' => 10,
                    ],
                ],
            ],
            'view'               => [
                'class'    => '\ogheo\htmlcompress\View',
                'compress' => YII_ENV_DEV ? false : true,
            ],
            'errorHandler'       => [
                'errorAction' => 'site/error',
            ],
            'frontendUrlManager' => require __DIR__ . '/../urls/urlManager.php',
            'urlManager'         => static function () {
                return Yii::$app->get('frontendUrlManager');
            },
        ],
        'params'              => $params,
    ];
