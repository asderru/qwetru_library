<?php
    
    namespace common\bootstrap;
    
    use core\tools\CacheUpdateChecker;
    use core\tools\dispatchers\AsyncEventDispatcher;
    use core\tools\dispatchers\DeferredEventDispatcher;
    use core\tools\dispatchers\EventDispatcher;
    use Yii;
    use yii\base\BootstrapInterface;
    use yii\base\ErrorHandler;
    use yii\caching\Cache;
    use yii\di\Container;
    use yii\mail\MailerInterface;
    use yii\queue\Queue;
    use yii\rbac\ManagerInterface;
    
    class SetUp implements BootstrapInterface
    {
        public function bootstrap($app): void
        {
            $container = Yii::$container;
            
            $container->setSingleton(
                ErrorHandler::class, function () use ($app) {
                return $app->errorHandler;
            },
            );
            
            $container->setSingleton(MailerInterface::class, function () use ($app) {
                return $app->mailer;
            });
            
            $container->setSingleton(
                Queue::class, function () use ($app) {
                return $app->get('queue');
            },
            );
            
            $container->setSingleton(
                Cache::class, function () use ($app) {
                return $app->cache;
            },
            );
            
            $container->setSingleton(
                ManagerInterface::class, function () use ($app) {
                return $app->authManager;
            },
            );
            
            // Регистрация CacheUpdateChecker в контейнере зависимостей
            $container->setSingleton(CacheUpdateChecker::class, function () {
                return CacheUpdateChecker::getInstance();
            });
            
            $container->setSingleton(
                EventDispatcher::class, DeferredEventDispatcher::class,
            );
            
            $container->setSingleton(
                DeferredEventDispatcher::class, function (
                Container $container,
            ) {
                return new DeferredEventDispatcher(
                    new AsyncEventDispatcher(
                        $container->get(Queue::class),
                    ),
                );
            },
            );
        }
        
        
    }
