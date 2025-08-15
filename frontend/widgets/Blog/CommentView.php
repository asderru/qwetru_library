<?php
    
    namespace frontend\widgets\Blog;
    
    
    use core\edit\entities\Content\Comment;
    
    class CommentView
    {
        public Comment $comment;
        /**
         * @var self[]
         */
        public array $children;
        
        public function __construct(Comment $comment, array $children)
        {
            $this->comment  = $comment;
            $this->children = $children;
        }
    }
