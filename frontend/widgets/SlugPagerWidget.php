<?php
    
    namespace frontend\widgets;
    
    use core\tools\Constant;
    use frontend\extensions\helpers\UrlHelper;
    use yii\base\Model;
    use yii\bootstrap5\Html;
    use yii\bootstrap5\Widget;
    use yii\helpers\Url;
    
    class SlugPagerWidget extends Widget
    {
        public Model       $model;
        public null|string $folder = null;
        
        public function run(): string
        {
            $model  = $this->model;
            $folder = $this->folder;
            $class  = 'pagination-link';
            
            $prev = $model->getPrevModel(Constant::STATUS_ACTIVE);
            if ($prev) {
                $prevUrl = ($folder)
                    ?
                    Html::a(
                        '<i class="bi bi-arrow-left-circle"></i> ' . Html::encode
                        (
                            $prev->name,
                        ),
                        [
                            'view',
                            'slug' => $prev->slug,
                        ],
                        [
                            'class'      => $class,
                            'aria-label' => $prev->name,
                        ],
                    )
                    :
                    UrlHelper::getUrlView
                    (
                        $prev,
                        $class,
                    );
            }
            else {
                $prevUrl = Html::a(
                    '<i class="bi bi-arrow-up-circle"></i> Главная',
                    Url::toRoute('index'),
                    [
                        'class'      => $class,
                        'aria-label' => 'Главная',
                    ],
                );
            }
            
            $next = $model->getNextModel(Constant::STATUS_ACTIVE);
            
            if ($next) {
                $nextUrl = ($folder)
                    ?
                    Html::a(
                        Html::encode($next->name) . ' <i class="bi bi-arrow-right-circle"></i>',
                        [
                            'view',
                            'slug' => $next->slug,
                        ],
                        [
                            'class'      => $class,
                            'aria-label' => $next->name,
                        ],
                    )
                    :
                    UrlHelper::getUrlView
                    (
                        $next,
                        $class,
                    );
            }
            else {
                $nextUrl = Html::a(
                    'Главная <i class="bi bi-arrow-up-circle"></i>',
                    Url::toRoute('index'),
                    [
                        'class'      => $class,
                        'aria-label' => 'Главная',
                    ],
                );
            }
            
            return $this->render(
                '@app/widgets/views/_pager',
                [
                    'nextUrl' => $nextUrl,
                    'prevUrl' => $prevUrl,
                    'next'    => $next,
                    'prev'    => $prev,
                ],
            
            );
            
        }
        
    }
