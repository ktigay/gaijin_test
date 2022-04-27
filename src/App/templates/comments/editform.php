<?php
/**
 * @var \App\Models\Comment $comment
 */
?>
<form action="/comments/edit" class="edit-form">
    <input type="hidden" name="comment_id" />
    <div class="panel">
        <div class="panel-body">
            <textarea class="form-control" rows="2" name="text"></textarea>
            <div class="mar-top clearfix pad-ver">
                <button class="btn btn-sm btn-primary" type="submit" ><i class="fa fa-pencil fa-fw"></i> Сохранить</button>
                <button class="btn btn-sm btn-default cancel-button" type="button">Отменить</button>
            </div>
        </div>
    </div>
</form>