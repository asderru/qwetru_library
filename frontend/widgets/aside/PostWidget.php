<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Blog\PostReader;
    use yii\base\Widget;
    
    class PostWidget extends Widget
    {
        public function run(): string
        {
            $activeId = $this->view->params['parentId'] ?? null;
            
            $models = PostReader::getLastArray(
                8,
                ['id', 'category_id', 'name', 'title', 'slug', 'link', 'description', 'updated_at'],
                $activeId,
            );
            return $this->render(
                '@app/widgets/aside/views/_posts',
                [
                    'models' => $models,
                ],
            );
        }
    }
