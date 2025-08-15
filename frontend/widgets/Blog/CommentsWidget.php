<?php
    
    namespace frontend\widgets\Blog;
    
    use core\edit\entities\Content\Comment;
    use core\edit\entities\Content\Content;
    use core\edit\forms\Content\CommentForm;
    use yii\base\InvalidConfigException;
    use yii\base\Widget;
    
    class CommentsWidget extends Widget
    {
        /**
         * @var Content
         */
        public Content $content;
        
        /**
         * @throws InvalidConfigException
         */
        public function init(): void
        {
            if (!$this->content) {
                throw new InvalidConfigException('Specify the post.');
            }
        }
        
        public function run(): string
        {
            $form = new CommentForm();
            
            $comments = $this->content->getComments()
                                      ->orderBy(['parent_id' => SORT_ASC, 'id' => SORT_ASC])
                                      ->all()
            ;
            
            $items = $this->treeRecursive($comments, null);
            return $this->render(
                '@app/widgets/Blog/views/comments/comments',
                [
                    'content'     => $this->content,
                    'items'       => $items,
                    'commentForm' => $form,
                ],
            );
        }
        
        /**
         * @param Comment[]    $comments
         * @param null|integer $parentId
         * @return CommentView[]
         */
        public function treeRecursive(&$comments, int|null $parentId): array
        {
            $items = [];
            foreach ($comments as $comment) {
                if ($comment->parent_id === $parentId) {
                    $items[] = new CommentView($comment, $this->treeRecursive($comments, $comment->id));
                }
            }
            return $items;
        }
    }
