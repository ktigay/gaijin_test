<?php
/**
 * @var \App\Models\Post $post
 */
?>
<form action="/comments/save" class="comment-form">
    <input type="hidden" name="post_id" value="<?=$post->id ?>" />
    <input type="hidden" name="parent_id" />
    <div class="panel">
        <div class="panel-body">
            <input class="form-control" type="text" name="user" placeholder="Гость" style="width: 400px;" />
            <br />
            <textarea class="form-control" rows="2" name="text" placeholder="Добавьте Ваш комментарий"></textarea>
            <div class="mar-top clearfix">
                <button class="btn btn-sm btn-primary pull-right" type="submit"><i class="fa fa-pencil fa-fw"></i> Добавить</button>
            </div>
        </div>
    </div>
</form>