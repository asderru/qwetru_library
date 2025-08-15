<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Shop\RazdelReader;
    use core\tools\Constant;
    use yii\base\Widget;
    
    class RazdelWidget extends Widget
    {
        public function run(): string
        {
            $textType = $this->view->params['textType'] ?? null;
            $activeId = $this->view->params['parentId'] ?? null;
            $isRazdel = $textType === Constant::RAZDEL_TYPE;
            
            $models = RazdelReader::getArray(
                Constant::RAZDEL_TYPE,
                ['id', 'name', 'link', 'lft', 'rgt', 'depth',],
            );
            
            return $this->render(
                '@app/widgets/aside/views/_razdels',
                [
                    'models'   => $models,
                    'activeId' => $activeId,
                    'isRazdel' => $isRazdel,
                ],
            );
        }
    }
