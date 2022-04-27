<?php
/**
 * @var array $comment
 */
?>
<div class="comment-data<?php if($comment['deleted']): ?> comment-deleted<?php endif; ?>">
    <div class="mar-btm">
        <a href="#" class="btn-link text-semibold media-heading box-inline"><?php echo $comment['user']['name']; ?></a>
        <p class="text-muted text-sm"><?php echo date('H:i:s d-m-Y', strtotime($comment['create_at'])); ?></p>
    </div>
    <p class="comment-text"><?php echo $comment['text']; ?></p>

    <?php if(!$comment['deleted']): ?>
        <div class="pad-ver">
            <a class="btn btn-sm btn-default btn-hover-primary answer-button" href="#<?=$comment['id'] ?>">Ответить</a>
            <?php if ($comment['editable']): ?>
                <a class="btn btn-sm btn-default btn-hover-primary edit-button" href="#<?=$comment['id'] ?>"><i class="fa fa-pencil fa-fw"></i> Редактировать</a>
            <?php endif; ?>
            <?php if ($comment['removeable']): ?>
                <a class="btn btn-sm btn-danger btn-hover-primary delete-button" href="#<?=$comment['id'] ?>">Удалить</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
