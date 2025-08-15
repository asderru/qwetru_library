<?php
    
    namespace frontend\widgets\aside;
    
    use core\read\arrays\Seo\AnonsReader;
    use yii\base\Widget;
    
    class AnonsWidget extends Widget
    {
        public function run(): ?string
        {
            $models = AnonsReader::getLastAnons();
            $model  = current($models);
            if ($model) {
                return null;
            }
            return $this->render(
                '@app/widgets/aside/views/_anons',
                [
                    'model' => $model,
                ],
            );
        }
    }
