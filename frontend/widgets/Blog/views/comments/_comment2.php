<?php
    
    use frontend\widgets\Blog\CommentView;
    
    /* @var $item CommentView */
    
    //PrintHelper::print($item->comment->id );
?>

<div class="comment-item comment-area__topic comment-item  row" data-id="<?= $item->comment->id ?>">

    <div class='col-sm-2 text-center comment-topic__starter'>
        <img src='https://meduza.io/impro/8w8ePyReiu_WutOA4DwwoARgxwRKwieYAj63oCoPpZU/resizing_type:fit/width:650/height:0/enlarge:1/quality:80/aHR0cHM6Ly9tZWR1/emEuaW8vaW1hZ2Uv/YXR0YWNobWVudHMv/aW1hZ2VzLzAwOS81/MjAvMjc4L29yaWdp/bmFsL3VDc3JmeXhQ/cFVZcnM0TjYwZVZo/blEuanBn.webp'
             alt='' class='comment-topic__avatar rounded-circle img-fluid'>
        <div class='topic-starter__name'>
            Martin Martines
        </div>
    </div>
    <div class='col-sm-10'>
        <div class='comment-topic__meta d-flex justify-content-between'>
            <small><?= Yii::$app->formatter->asDatetime($item->comment->created_at) ?></a></small>
            <small>September 18, 2016</small>
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
                <div class="pull-left">

                </div>
                <div class="pull-right">
                    <span class="comment-reply">Ответить</span>
                </div>
            </div>
        </div>
    </div>
    <div class="margin">
        <div class="reply-block"></div>
        <div class="comments">
            <?php
                foreach ($item->children as $children): ?>
                    <?= $this->render('@app/widgets/Blog/views/comments/_comment', ['item' => $children]) ?>
                <?php
                endforeach; ?>
        </div>
    </div>
</div>
