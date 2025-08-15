<?php
    
    use frontend\widgets\Forum\CommentView;
    
    /* @var $item CommentView */
    
    $profile = $item->comment->person;
?>

<div id="comment_<?= $item->comment->id ?>"
     class="comment-area__topic comment-item row"
     data-id="<?= $item->comment->id ?>">

    <div class='col-sm-2 text-center comment-topic__starter'>
        <?php
            try {
                echo
                $profile->getImageUrl(6);
            }
            catch (Throwable $e) {
            
            }
        ?>

        <div class='topic-starter__name'>
            <?= $profile->name ?>
        </div>
    </div>
    <div class='col-sm-10'>
        <div class='comment-topic__meta d-flex justify-content-between'>
            <small>ответов: <?= $item->comment->counts ?></a></small>
            <small><?= Yii::$app->formatter->asDatetime($item->comment->created_at) ?></small>
        </div>

        <div class='comment-topic__text'>
            <?php
                if ($item->comment->isActive()): ?>
                    <?= Yii::$app->formatter->asNtext($item->comment->text) ?>
                <?php
                else: ?>
                    <i>Комментарий удален.</i>
                <?php
                endif; ?>

            <div>
                <?php
                    if (!Yii::$app->user->isGuest) {
                        // Пользователь зарегистрирован
                        // ... ваш код для зарегистрированных пользователей ...
                        ?>
                        <div class='comment-topic__reply'>
                            <span class='btn btn-sm btn-outline-secondary comment-reply'>Ответить</span>
                        </div>
                        <?php
                    }
                ?>
            </div>
        </div>
    </div>
    <div class="margin">

        <div class="reply-block"></div>

        <div class="comments additional-comment__body">
            <?php
                foreach ($item->children as $children): ?>
                    <?= $this->render('@app/widgets/Forum/views/comments/_comment', ['item' => $children]) ?>
                <?php
                endforeach; ?>
        </div>

    </div>

</div>
