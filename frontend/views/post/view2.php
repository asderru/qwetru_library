<?php
    
    namespace core\edit\entities\Content;
    
    use core\edit\entities\Content\queries\CommentQuery;
    use core\tools\Constant;
    use yii\db\ActiveQuery;
    use yii\db\ActiveRecord;
    
    /**
     * This is the model class for table "content_comments".
     *
     * @property int      $id            ID
     * @property int      $site_id       ID сайта
     * @property int      $user_id       ID сайта
     * @property int      $content_id    ID родительского текста
     * @property int      $profile_id    ID профиля
     * @property int|null $parent_id     ID родительского коммента
     * @property int      $created_at    Дата создания
     * @property int      $updated_at    Дата обновления
     * @property string   $text          Комментарий
     * @property int      $status        Статус
     */
    class Comment extends ActiveRecord
    {
        /**
         * {@inheritdoc}
         */
        public static function tableName(): string
        {
            return 'content_comments';
        }
        
        /**
         * {@inheritdoc}
         * @return CommentQuery the active query used by this AR class.
         */
        public static function find(): CommentQuery
        {
            return new CommentQuery(static::class);
        }
        
        public static function create(int $userId, int|null $parentId, string $text, int|null $profileId = null): self
        {
            $comment             = new static();
            $comment->site_id    = Constant::SITE_ID;
            $comment->user_id    = $userId;
            $comment->profile_id = $profileId;
            $comment->parent_id  = $parentId;
            $comment->text       = $text;
            $comment->created_at = time();
            $comment->updated_at = time();
            $comment->status     = Constant::STATUS_DRAFT;
            return $comment;
        }
        
        public function edit(int $parentId, int $text): void
        {
            $this->parent_id = $parentId;
            $this->text      = $text;
        }
        
        public function activate(): void
        {
            $this->status = Constant::STATUS_ACTIVE;
        }
        
        public function draft(): void
        {
            $this->status = Constant::STATUS_DRAFT;
        }
        
        public function isActive(): bool
        {
            return $this->status > Constant::STATUS_DRAFT;
        }
        
        public function isDraft(): bool
        {
            return $this->status < Constant::STATUS_ACTIVE;
        }
        
        public function isIdEqualTo(int $id): bool
        {
            return $this->id === $id;
        }
        
        public function isChildOf(int $id): bool
        {
            return $this->parent_id === $id;
        }
        
        public function getContent(): ActiveQuery
        {
            return $this->hasOne(Content::class, ['id' => 'post_id']);
        }
        
        
        /**
         * {@inheritdoc}
         */
        public function attributeLabels(): array
        {
            return [
                'id'         => 'ID',
                'site_id'    => 'ID сайта',
                'type_id'    => 'типа текста',
                'content_id' => 'ID родительского текста',
                'profile_id' => 'ID профиля',
                'parent_id'  => 'ID родительского коммента',
                'created_at' => 'Дата создания',
                'updated_at' => 'Дата обновления',
                'text'       => 'Комментарий',
                'status'     => 'Статус',
            ];
        }
    }
