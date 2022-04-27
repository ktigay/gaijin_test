<?php
/**
 * @var array $comments
 */
foreach ($comments as $comment) {

    echo $this->renderTpl('comments/comment', [
        'comment' => $comment
    ]);
}