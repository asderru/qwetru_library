<?php
    
    namespace frontend\widgets\aside;
    
    use core\edit\entities\Blog\Category;
    use core\read\arrays\Blog\CategoryReader;
    use core\tools\Constant;
    use Exception;
    use Throwable;
    use yii\base\Widget;
    
    /* @var $categories Category[] */
    
    /* @var $category Category */
    class CategoryWidget extends Widget
    {
        /**
         * @throws Exception
         * @throws Throwable
         */
        public function run(): string
        {
            $models = CategoryReader::getArray(
                Constant::CATEGORY_TYPE,
                ['id', 'name', 'link', 'lft', 'rgt', 'depth',],
            );
            return $this->render(
                '@app/widgets/aside/views/_categories',
                [
                    'models'  => $models,
                    'modelId' => $this->modelId,
                ],
            );
        }
        
    }
