<?php
if ($comment['article_author']) echo $html->div('authors-comment'); ?>
	<div class="article-comment" id="<?php echo sprintf('comment-%s', $comment['id']); ?>">
	<?php echo $html->div('comment-header'); ?>
		<?php
		if ($auth->check('comments', 'delete', $comment['user_id']))
		{
			echo '<span class="comment-admin">[ ';
			echo $ajax->link
				(
					__('Delete', true),
					array
					(
						'controller' => 'comments',
						'action' => 'delete',
						$slug,
						$comment['id'],
						'ajax' => '1'
					),
					array
					(
						'update'		=> 'comments-inner-wrap'
					),
					__('Are you sure you want to delete this comment?', true)
				);
			echo ' ]</span>';
		}

		echo '<span class="author-name">';
		if ($auth->isAdmin() && empty($comment['user_id']))
		{
			echo $html->link($comment['name'], 'mailto:'.$comment['email']);
		}
		else
		{
			if (Validation::url($comment['website']))
			{
				echo $html->link($comment['name'], $comment['website']);
			}
			else
			{
				echo $comment['name'];
			}
		}

		echo '</span> :: '.date(Configure::read('Neutrino.DatetimeDisplayFormat'), strtotime($comment['created'])).' ';
	echo '</div>';

	echo '<div class="content">';
	echo nl2br(Sanitize::html($comment['comment']));
	echo '</div>';

	echo $html->div('comment-footer', '', ''); ?>

	</div>
<?php if ($comment['article_author']) echo '</div>'; ?>
