<?php
    
    namespace frontend\widgets;
    
    use core\helpers\IconHelper;
    use yii\bootstrap5\Html;
    use yii\bootstrap5\Widget;
    use yii\helpers\Url;
    
    class PagerWidget extends Widget
    {
        public array      $model;
        public null|array $prevModel;
        public null|array $nextModel;
        
        public function run(): string
        {
            $class = 'pagination-link';
            $model = $this->model;
            $prev = $this->prevModel;
            $next = $this->nextModel;
            
            if ($prev) {
                $prevUrl = Html::a(
                    IconHelper::biCaretLeft($model['name']) . '  ' . Html::encode
                    (
                        $prev['name'],
                    ),
                    [
                        'view',
                        'id' => $prev['id'],
                    ],
                    [
                        'class'      => $class,
                        'aria-label' => $prev['name'],
                    ],
                );
            }
            else {
                $prevUrl = Html::a(
                    IconHelper::biCaretUp($model['name']) . '  Главная',
                    Url::toRoute('index'),
                    [
                        'class'      => $class,
                        'aria-label' => 'Главная',
                    ],
                );
            }
            
            if ($next) {
                $nextUrl
                    = Html::a(
                    Html::encode($next['name']) . ' ' . IconHelper::biCaretRight($model['name']),
                    [
                        'view',
                        'id' => $next['id'],
                    ],
                    [
                        'class'      => $class,
                        'aria-label' => $next['name'],
                    ],
                );
            }
            else {
                $nextUrl = Html::a(
                    'Главная ' . IconHelper::biCaretUp($model['name']),
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
