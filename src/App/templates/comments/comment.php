<?php
/**
 * @var array $comment
 */
?>
<div class="media-block" id="comment<?=$comment['id'] ?>">
    <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя" src="https://bootstraptema.ru/snippets/icons/2016/mia/1.png"></a>
    <div class="media-body">
        <?php
            echo $this->renderTpl('comments/comment_data', [
                'comment' => $comment
            ]);
        ?>

        <div class="answer-container"></div>
        <hr />

        <div class="comments-block">

            <?php if(!empty($comment['isExpandable'])): ?>

                <a href="/posts/nodes?id=<?=$comment['id'] ?>" class="show-comments">Показать комментарии</a>
            <?php endif; ?>

            <?php if(!empty($comment['nodes'])): ?>
                <?php
                foreach($comment['nodes'] as $node) {

                    echo $this->renderTpl('comments/comment', [
                        'comment' => $node
                    ]);
                }
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>