<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Content\TagReader;
    use core\tools\Constant;
    use yii\base\Widget;
    
    class TagWidget extends Widget
    {
        public function run(): string
        {
            $activeId = $this->view->params['parentId'] ?? null;
            
            $models = TagReader::getArray(
                Constant::TAG_TYPE,
            );
            return $this->render(
                '@app/widgets/aside/views/_tags',
                [
                    'models'   => $models,
                    'activeId' => $activeId,
                ],
            );
        }
    }
