<?php
    
    namespace frontend\widgets\Forum;
    
    use core\read\readModels\Blog\PostReadRepository;
    use Throwable;
    use yii\base\Widget;
    
    class LastPostsWidget extends Widget
    {
        public int $limit;
        
        private PostReadRepository $repository;
        
        public function __construct(PostReadRepository $repository, $config = [])
        {
            parent::__construct($config);
            $this->repository = $repository;
        }
        
        /**
         * @throws Throwable
         */
        public function run(): string
        {
            return $this->render('last-posts', [
                'posts' => $this->repository::findLast($this->limit),
            ]);
        }
    }
