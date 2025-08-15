<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Library\BookReader;
    use yii\base\Widget;
    
    class BookWidget extends Widget
    {
        public function run(): string
        {
            $models = BookReader::getLastArray(
                8,
                ['id', 'name', 'slug', 'link', 'description'],
            );
            return $this->render(
                '@app/widgets/aside/views/_books',
                [
                    'models' => $models,
                ],
            );
        }
    }
