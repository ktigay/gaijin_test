<?php
/**
 * @var \App\Controllers\AppController $this
 * @var \App\Models\Posts[] $posts
 */
?>
<div class="panel">
    <div class="panel-body">
        <div class="media-block">
            <div class="media-body">
                <div class="posts" id="postList">
                    <?php
                    echo $this->renderTpl('posts/list', [
                        'posts' => $posts
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    echo $this->renderTpl('posts/form');
?>
<script type="text/javascript">
	class PostList extends App {

		_events = {
			submit: {
				'post-form': event => {
					this.savePost(event.target);
					return false;
				},
			}
		};

		constructor() {
			super();
			this._registerGlobalEvents();
		}

		savePost(form) {
			const formData = new FormData(form),
				params = {};
			for (let [name, value] of formData.entries()) {
				params[name] = value;
			}

			Request.post(form.getAttribute('action'), params)
				.then(async () => {
					let response = await Request.get('/posts/index'),
					    result = await response.text();

					document.getElementById('postList').innerHTML = result;
				});
		}
	}
	new PostList();
</script>
