<?php
/**
 * @var \App\Models\Post $post
 */
?>
<div class="post list-item">
    <div><?php echo date('H:i:s d-m-Y', strtotime($post->create_at)); ?></div> |
    <div>Автор: <?php echo $post->user->name ?></div> |
    <div><a href="/posts/get?id=<?=$post->id ?>"><?php echo $post->title; ?></a></div>
</div>