<?php
    
    namespace frontend\widgets\Blog;
    
    use core\edit\entities\Blog\Category;
    use core\read\readModels\Blog\CategoryReadRepository;
    use Throwable;
    use yii\base\Widget;
    use yii\helpers\Html;
    
    class CategoriesWidget extends Widget
    {
        /** @var Category|null */
        public ?Category               $active;
        private CategoryReadRepository $categories;
        
        public function __construct(CategoryReadRepository $categories, $config = [])
        {
            parent::__construct($config);
            $this->categories = $categories;
        }
        
        /**
         * @throws Throwable
         */
        public function run(): string
        {
            return Html::tag(
                'div', implode(
                PHP_EOL, array_map(function (Category $category) {
                $active = $this->active && ($this->active->id === $category->id);
                return Html::a(
                    Html::encode($category->name),
                    ['/blog/post/category', 'slug' => $category->slug],
                    ['class' => $active ? 'list-group-item active' : 'list-group-item'],
                );
            }, $this->categories::findModels()),
            ), [
                'class' => 'list-group',
            ],
            );
        }
    }
