<?php
    
    namespace frontend\widgets;
    
    use core\read\arrays\Blog\CategoryReader;
    use core\read\arrays\Content\PageReader;
    use core\read\arrays\Library\BookReader;
    use core\read\arrays\Shop\RazdelReader;
    use Exception;
    use Throwable;
    use yii\base\Widget;
    
    class Navigator2Widget extends Widget
    {
        public ?array $books      = null;
        public ?array $categories = null;
        public ?array $pages      = null;
        public ?array $razdels    = null;
        
        private RazdelReader   $razdelsRepository;
        private BookReader     $booksRepository;
        private CategoryReader $categoriesRepository;
        private PageReader     $pagesRepository;
        
        public function __construct(
            RazdelReader   $razdelsRepository,
            BookReader     $booksRepository,
            CategoryReader $categoriesRepository,
            PageReader     $pagesRepository,
                           $config = [],  // Конфиг должен быть последним параметром
        )
        {
            $this->razdelsRepository    = $razdelsRepository;
            $this->booksRepository      = $booksRepository;
            $this->categoriesRepository = $categoriesRepository;
            $this->pagesRepository      = $pagesRepository;
            parent::__construct($config); // Вызываем родительский конструктор с конфигом
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        public function run(): string
        {
            return $this->render(
                '@app/widgets/views/_navigator.php',
            );
        }
        
        /**
         * @throws Exception
         * @throws Throwable
         */
        private function prepareNavigationData(): array
        {
            $books      = $this->books ?? $this->booksRepository->findModels();
            $categories = $this->categories ?? $this->categoriesRepository->findModels();
            $pages      = $this->pages ?? $this->pagesRepository->findModels();
            $razdels    = $this->razdels ?? $this->razdelsRepository->findModels();
            
            $navigationData = [
                'books'       => $books,
                'categories'  => $categories,
                'pages'       => $pages,
                'mainRazdels' => [],
                'razdels1'    => [],
                'razdels2'    => [],
            ];
            
            if (empty($razdels)) {
                return $navigationData;
            }
            
            return array_merge(
                $navigationData,
                $this->processRazdels($razdels),
            );
        }
        
        private function processRazdels(array $razdels): array
        {
            // Получаем основные разделы с depth = 1
            $mainRazdels = array_filter($razdels, fn($razdel) => $razdel->depth === 1);
            
            if (empty($mainRazdels)) {
                return [
                    'mainRazdels' => [],
                    'razdels1'    => [],
                    'razdels2'    => [],
                ];
            }
            
            // Индексируем основные разделы для удобства
            $mainRazdels = array_values($mainRazdels);
            
            // Получаем дочерние разделы с depth = 2
            $childRazdels = array_filter($razdels, fn($razdel) => $razdel->depth === 2);
            
            $razdels1 = [];
            $razdels2 = [];
            
            foreach ($childRazdels as $razdel) {
                if (
                    isset($mainRazdels[0])
                    && $this->isChildRazdel($razdel, $mainRazdels[0])
                ) {
                    $razdels1[] = $razdel;
                }
                elseif (
                    isset($mainRazdels[1])
                    && $this->isChildRazdel($razdel, $mainRazdels[1])
                ) {
                    $razdels2[] = $razdel;
                }
            }
            
            return [
                'mainRazdels' => $mainRazdels,
                'razdels1'    => $razdels1,
                'razdels2'    => $razdels2,
            ];
        }
        
        private function isChildRazdel($childRazdel, $parentRazdel): bool
        {
            return $childRazdel->lft > $parentRazdel->lft
                   && $childRazdel->rgt < $parentRazdel->rgt;
        }
    }
