<?php
/**
 * @var \App\Models\Post $post
 */
?>
<div class="panel">
    <div class="panel-body">
        <div class="media-block">
            <a class="media-left" href="#"><img class="img-circle img-sm" alt="Профиль пользователя" src="https://bootstraptema.ru/snippets/icons/2016/mia/1.png"></a>
            <div class="media-body">
                <div class="mar-btm">
                    <a href="#" class="btn-link text-semibold media-heading box-inline"><?php echo $post->user->name; ?></a>
                    <p class="text-muted text-sm"><?php echo date('d-m-Y', strtotime($post->create_at)); ?></p>
                </div>
                <p><?php echo $post->text; ?></p>
                <br />
            </div>
        </div>
    </div>
</div>

<div class="panel">
    <div class="panel-body" id="commentsContainer">
        <?php
        if(!$post->comments) {
            echo "<div class='comment-empty'><h3>Пока что ни одного комментария ;(</h3></div>";
        } else {
            $tree = $post->buildCommentsTree();
            foreach ($tree as $comment) {

                echo $this->renderTpl('comments/comment', [
                    'comment' => $comment
                ]);
            }
        }
        ?>
    </div>
</div>


<?php
    echo $this->renderTpl('comments/form', ['post' => $post]);
?>

<template id="answerForm">
    <?php
    echo $this->renderTpl('comments/form', ['post' => $post]);
    ?>
</template>

<template id="editForm">
    <?php
    echo $this->renderTpl('comments/editform', ['post' => $post]);
    ?>
</template>
<script type="text/javascript">

	class Post extends App {

		_floatingForm;

		_events = {
			click: {
				'show-comments': event => {
					this.showComments(event.target);
					return false;
				},
				'answer-button': event => {
					this.showCommentForm(event.target);
					return false;
				},
				'edit-button': event => {
					this.editCommentForm(event.target);
					return false;
				},
                'delete-button': event => {
					this.deleteComment(event.target);
					return false;
                },
                'cancel-button': event => {
					this._removeFloatWindow();
					return false;
                }
			},
			submit: {
				'comment-form': event => {
					this.saveComment(event.target);
					return false;
				},
				'edit-form': event => {
					this.editComment(event.target);
					return false;
				},
			}
		};

		constructor() {
			super();
			this._registerGlobalEvents();
		}

		showComments(link) {
			let parent,
				url;

			parent = link.parentNode;
			url = link.getAttribute('href');

			Request.get(url).then(async (response) => {
				parent.innerHTML = await response.text();
			});
		}

        showCommentForm(button) {
			let template,
                form;

			this._removeFloatWindow();
			template = document.getElementById('answerForm');

			form = this._floatingForm = template.content.cloneNode(true).querySelector('form');

			form.querySelector('[name="parent_id"]').value = button.getAttribute('href').substring(1);
			button
                .closest('.media-body')
                .querySelector('.answer-container')
                .append(form);
        }

		saveComment(form) {
			const formData = new FormData(form),
                params = {};
			for (let [name, value] of formData.entries()) {
				params[name] = value;
			}

			Request.post(form.getAttribute('action'), params)
            .then(async (response) => {
				if(response.status === 200) {
					let result = await response.text(),
						container = document.querySelector(`#comment${params.parent_id}`);

					this._removeFloatWindow();

					if(params.parent_id) {
						container.querySelector(`#comment${params.parent_id} > .media-body > .comments-block`).insertAdjacentHTML('beforeend', result);
					} else  {
						let container = document.getElementById('commentsContainer');
						container.querySelector('.comment-empty')?.remove();
						container.insertAdjacentHTML('beforeend', result);
						form.reset();
                    }
				}
            });
        }

		editCommentForm(button) {
			let template,
                form,
                parent = button
					.closest('.comment-data');

			this._removeFloatWindow();

			template = document.getElementById('editForm');
			form = this._floatingForm = template.content.cloneNode(true).querySelector('form');

			form.querySelector('[name="comment_id"]').value = button.getAttribute('href').substring(1);
			form.querySelector('[name="text"]').value = parent.querySelector('.comment-text').textContent;

            parent.append(form);
        }

		editComment(form) {
			const formData = new FormData(form),
				params = {};
			for (let [name, value] of formData.entries()) {
				params[name] = value;
			}

			Request.post(form.getAttribute('action'), params)
				.then(async (response) => {
					if(response.status === 200) {
						let result = await response.text();

						this._removeFloatWindow();

						let commentData = document.querySelector(`#comment${params.comment_id} > .media-body > .comment-data`)
						commentData.insertAdjacentHTML('afterend', result);
						commentData.remove();
					}
				});
		}

		deleteComment(button) {
			let id = button.getAttribute('href').substring(1);

			Request.get(`/comments/delete?comment_id=${id}`)
				.then(async (response) => {
					if(response.status === 200) {
						let result = await response.text();

						this._removeFloatWindow();

						let commentData = document.querySelector(`#comment${id} > .media-body > .comment-data`)
						commentData.insertAdjacentHTML('afterend', result);
						commentData.remove();
					}
				});
        }

		_removeFloatWindow() {
			if(this._floatingForm) {
				this._floatingForm.remove();
				this._floatingForm = null;
			}
        }
	}
	new Post();
</script>