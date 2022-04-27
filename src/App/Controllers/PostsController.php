<?php

namespace App\Controllers;

use App\Models\Comments;
use App\Models\Post;
use App\Models\Posts;
use App\Models\Users;

class PostsController extends AppController
{
    protected const START_LEVELS = 2;

    public function actionIndex(): array
    {
        $status = 200;
        try {
            $posts = Posts::storage()
                ->findBy("", [], "create_at desc");

            $response = $this->renderTpl($this->isAjax() ? 'posts/list' : 'posts/index', ['posts' => $posts]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }
        return $this->response($response, $status);
    }

    public function actionNodes(): array
    {
        $status = 200;
        try {
            $id = $_REQUEST['id'] ?? throw new \Exception('Неверные параметры');

            $storage = Comments::storage();

            $comment = $storage->getById($id);

            $comments = Post::buildCommentTree($storage->findRecursive($comment->post_id, $comment->id));

            $response = $this->renderTpl('posts/nodes', ['comments' => $comments]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }

    public function actionGet(): array
    {
        $status = 200;
        try {
            $id = $_REQUEST['id'] ?? throw new \Exception('Неверные параметры');

            /**
             *@var Post
             */
            $post = Posts::storage()
                ->bind('comments', [
                    'select' => 't.*, if(t.level = ? and (select t2.id from comments t2 where t2.parent_id = t.id limit 1) is not null, true, false) isExpandable',
                    'conditions' => 'level <= ?',
                    'params' => [self::START_LEVELS, self::START_LEVELS],
                    'order' => 'create_at asc',
                    'bind' => 'user'
                ])->getById($id);

            if(!$post) {
                throw new \Exception('Пост не найден');
            }

            $response = $this->renderTpl('posts/post', ['post' => $post]);
        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }

    public function actionSave(): array
    {

        $status = 200;
        try {
            $userName = empty($_POST['user']) ? 'Гость' : $_POST['user'];
            $title = empty($_POST['title']) ? 'Не стал проверять заголовок на заполнение. Время время.': $_POST['title'];
            $text = empty($_POST['text']) ? 'Не стал проверять текст на заполнение. Времени мало :(' : $_POST['text'];

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
                'user_id' => $user->id,
                'title' => $title,
                'text' => $text,
            ];

            $post = Posts::storage()->createEmpty();
            $post->setData($data);
            $post->save();

            $response = $post->id;

        } catch (\Exception $ex) {
            $status = 500;
            $response = $ex->getMessage();
        }

        return $this->response($response, $status);
    }
}