<?php
/**
 * @var \App\Controllers\AppController $this
 * @var \App\Models\Posts[] $posts
 */

if(empty($posts)) {
    echo "<h3>Пока что ни одного поста ;(</h3>";
} else {
    foreach ($posts as $post) {

        echo $this->renderTpl('posts/listitem', [
            'post' => $post
        ]);
    }
}