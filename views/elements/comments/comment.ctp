<?php if ($comment['article_author']) echo $html->div('authors-comment'); ?>
	<div class="article-comment">
	<?php echo $html->div('comment-header'); ?>
		<?php
		if ($auth->valid())
		{
			echo '<span class="comment-admin">[ ';
			echo $ajax->link(
					'Delete',
					'/comments/delete/'.$slug.'/'.$comment['id'].'/ajax:1',
					array
					(
						'update'		=> 'comments-inner-wrap',
						'indicator'		=> 'working',
						'id'			=> 'comment-'.$article['Article']['id'].$comment['id']
					),
					'Are you sure you want to delete this comment?'
				);
			echo ' ]</span>';
		}

		echo '<span class="author-name">';
		if ($auth->valid())
			echo $html->link($comment['name'], 'mailto:'.$comment['email']);
		else
		{
			if (Validation::url($comment['website']))
				echo $html->link($comment['name'], $comment['website']);
			else
				echo $comment['name'];
		}

		echo '</span> :: '.date(Configure::read('Neutrino.DatetimeDisplayFormat'), strtotime($comment['created'])).' ';
	echo '</div>';

	echo '<div class="content">';
	// fix ticked #15: preserve newlines in comments
	echo nl2br(Sanitize::html($comment['comment']));
	echo '</div>';

	echo $html->div('comment-footer', '', ''); ?>

	</div>
<?php if ($comment['article_author']) echo '</div>'; ?>
