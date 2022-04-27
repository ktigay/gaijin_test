<?php
/**
 * @var \App\Models\Post $post
 */
?>
<form action="/posts/save" class="post-form">
    <div class="panel">
        <div class="panel-body">
            <input class="form-control" type="text" name="user" placeholder="Гость" style="width: 400px;" />
            <br />
            <input class="form-control" type="text" name="title" placeholder="Заголовок" />
            <br />
            <textarea class="form-control" rows="2" name="text" placeholder="Добавьте Ваш комментарий"></textarea>
            <div class="mar-top clearfix">
                <button class="btn btn-sm btn-primary pull-right" type="submit"><i class="fa fa-pencil fa-fw"></i> Добавить</button>
            </div>
        </div>
    </div>
</form>