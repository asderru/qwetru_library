<?php
    
    namespace frontend\widgets;
    
    use core\read\arrays\Blog\CategoryReader;
    use core\read\arrays\Content\PageReader;
    use core\read\arrays\Library\BookReader;
    use core\read\arrays\Shop\RazdelReader;
    use core\tools\Constant;
    use Exception;
    use Throwable;
    use yii\base\Widget;
    
    class NavigatorWidget extends Widget
    {
        private RazdelReader   $razdelReader;
        private BookReader     $bookReader;
        private CategoryReader $categoryReader;
        private PageReader     $pageReader;
        
        public function __construct(
            RazdelReader   $razdelReader,
            BookReader     $bookReader,
            CategoryReader $categoryReader,
            PageReader     $pageReader,
                           $config = [],  // Конфиг должен быть последним параметром
        )
        {
            $this->razdelReader   = $razdelReader;
            $this->bookReader     = $bookReader;
            $this->categoryReader = $categoryReader;
            $this->pageReader     = $pageReader;
            parent::__construct($config); // Вызываем родительский конструктор с конфигом
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        public function run(): string
        {
            $razdels    = $this->razdelReader::getMenuArray();
            $books      = $this->bookReader::getMenuArray();
            $categories = $this->categoryReader::getArray(Constant::CATEGORY_TYPE, ['id', 'name', 'link', 'depth'],
                null);
            $pages      = $this->pageReader::getMenuArray();
            
            return $this->render(
                '@app/widgets/views/_navigator.php',
                [
                    'razdels'    => $razdels,
                    'books'      => $books,
                    'categories' => $categories,
                    'pages'      => $pages,
                ],
            );
        }
        
    }
