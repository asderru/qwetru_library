<?php
    
    use core\edit\entities\Content\Content;
    use core\edit\forms\Content\CommentForm;
    use frontend\widgets\Forum\CommentView;
    use yii\bootstrap5\ActiveForm;
    use yii\helpers\Html;
    
    /* @var $this yii\web\View */
    /* @var $content Content */
    /* @var $items CommentView[] */
    /* @var $count integer */
    /* @var $commentForm CommentForm */
?>

<div id="comments" class="comment-area__title">
    <h2>Комментарии</h2>
    <?php
        foreach ($items as $item): ?>
            <?= $this->render('@app/widgets/Forum/views/comments/_comment', ['item' => $item]) ?>
        <?php
        endforeach; ?>
</div>

<?php
    if (!Yii::$app->user->isGuest) {
        // Пользователь зарегистрирован
        // ... ваш код для зарегистрированных пользователей ...
        ?>


        <div id="reply-block" class="leave-reply">
            <?php
                $form = ActiveForm::begin([
                    'action' => ['comment', 'id' => $content->id],
                ]); ?>
            
            <?= Html::activeHiddenInput(
                $commentForm, 'parentId',
                [
                    'value' => null,
                ],
            ) ?>
            
            <?= $form->field($commentForm, 'text')->textarea(['rows' => 5])->label('Ваш комментарий') ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-sm btn-secondary']) ?>
            </div>
            
            <?php
                ActiveForm::end(); ?>
            <hr>
            <div class='card-footer '>
                <small><em> *Оставлять комментарии могут только зарегистрированные пользователи</em></small>
            </div>
        </div>
        
        <?php
    }
    else {
        // Пользователь не зарегистрирован
        ?>
        <div class="card">
            <div class="card-header text-center">
                Оставлять комментарии могут только зарегистрированные пользователи
            </div>
        </div>
        <?php
    }
?>


<?php
    $this->registerJs(
        "
    jQuery(document).on('click', '#comments .comment-reply', function () {
        var link = jQuery(this);
        var form = jQuery('#reply-block');
        var comment = link.closest('.comment-item');
        jQuery('#commentform-parentid').val(comment.data('id'));
        form.detach().appendTo(comment.find('.reply-block:first'));
        return false;
    });
",
    ); ?>
