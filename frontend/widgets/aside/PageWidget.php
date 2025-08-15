<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Content\PageReader;
    use core\tools\Constant;
    use yii\base\Widget;
    
    class PageWidget extends Widget
    {
        public function run(): string
        {
            $textType = $this->view->params['textType'] ?? null;
            $activeId = $this->view->params['parentId'] ?? null;
            $isPage   = $textType === Constant::PAGE_TYPE;
            
            $models = PageReader::getArray(
                Constant::PAGE_TYPE,
                ['id', 'name', 'link', 'lft', 'rgt', 'depth',],
                null,
            );
            
            return $this->render(
                '@app/widgets/aside/views/_pages',
                [
                    'models'   => $models,
                    'activeId' => $activeId,
                    'isPage'   => $isPage,
                ],
            );
        }
    }
