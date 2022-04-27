<?php

namespace App\Controllers;

use App\Models\Comments;
use App\Models\Posts;
use App\Models\Users;

class CommentsController extends AppController
{
    public function actionSave(): array
    {
        $status = 200;
        try {
            $post_id = $_POST['post_id'] ?? null;
            $parent_id = $_POST['parent_id'] ?? null;
            $userName = empty($_POST['user']) ? 'Гость' : $_POST['user'];
            $text = empty($_POST['text']) ? 'Заполните пожалуйста комментарий!' : $_POST['text'];
            $level = 0;

            if(!$post_id) {
                throw new \Exception('wrong params');
            }

            /**
             *@var \App\Models\Post
             */
            $post = Posts::storage()
                ->getById($post_id);
            if(!$post) {
                throw new \Exception('post does not exists');
            }

            if($parent_id) {
                $comment = Comments::storage()
                    ->getById($parent_id);
                if (!$comment) {
                    throw new \Exception('comment does not exists or deleted');
                }
                $level = $comment->level + 1;
            }

            $users = Users::storage()->findBy('t.name = ?', [$userName]);
            if(empty($users)) {
                $userId = Users::storage()->save([
                    'name' => $userName
                ]);
                $user = Users::storage()->getById($userId);
            } else {
                $user = array_shift($users);
            }

            $data = [
                'post_id' => $post->id,
                'user_id' => $user->id,
                'parent_id' => $parent_id,
                'level' => $level,
                'text' => $text,
            ];

            $comment = Comments::storage()
                ->createEmpty();

            $comment->setData($data);
            $comment->save();

            $response = $this->renderTpl('comments/comment', ['comment' => $comment->toArray()]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }

    public function actionEdit(): array
    {
        $status = 200;
        try {
            $comment_id = $_POST['comment_id'] ?? null;
            $text = empty($_POST['text']) ? 'Заполните пожалуйста комментарий!' : $_POST['text'];

            if(!$comment_id) {
                throw new \Exception('wrong params');
            }

            /**
             * @var \App\Models\Comment $comment
             */
            $comment = Comments::storage()
                ->getById($comment_id);
            if (!$comment) {
                throw new \Exception('comment does not exists or deleted');
            }

            if($comment->isEditable()) {
                $comment->text = $text;
                $comment->save();
            }

            $response = $this->renderTpl('comments/comment_data', ['comment' => $comment->toArray()]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }

    public function actionDelete(): array
    {
        $status = 200;
        try {
            $comment_id = $_GET['comment_id'] ?? null;

            /**
             * @var \App\Models\Comment $comment
             */
            $comment = Comments::storage()
                ->getById($comment_id);
            if (!$comment) {
                throw new \Exception('comment does not exists or deleted');
            }

            if($comment->isRemoveable()) {
                $comment->text = 'Комментарий удален';
                $comment->deleted = 1;
                $comment->save();
            }

            $response = $this->renderTpl('comments/comment_data', ['comment' => $comment->toArray()]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }
}