<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Seo\FaqReader;
    use Exception;
    use Throwable;
    use yii\base\Widget;
    
    class FaqWidget extends Widget
    {
        /**
         * @throws Exception
         * @throws Throwable
         */
        public function run(): string
        {
            $models = FaqReader::findLast(8);
            
            return $this->render(
                '@app/widgets/aside/views/_faqs',
                [
                    'models'  => $models,
                    'modelId' => $this->modelId,
                ],
            );
        }
        
    }
