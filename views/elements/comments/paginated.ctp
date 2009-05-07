<?php
// BUGFIX: when rendered from "add" would
// cause amazing effects for the end user
$this->passedArgs['action'] = 'view';
$this->passedArgs['ajax'] = 1;
$paginator->options
	(
		array_merge
		(
			array('url' => $this->passedArgs),
			array
			(
				'update'		=> 'comments-inner-wrap',
				'indicator'		=> 'working'
			)
		)
	);

if ($comments_count > 0)
{
	?>
	<div class="comment-paginate-counter"><?php echo $paginator->counter(__('Page %page% of %pages%', true)); ?></div>
	<div class="comment-paginate-numbers"><?php echo $paginator->numbers(array('separator' => ' &middot; ')); ?></div>
	<br />
	<?php

	foreach ($comments as $comment)
	{
		echo $this->element
			(
				'comments/comment',
				array
				(
					'comment' => $comment['Comment'],
					'slug' => $this->passedArgs[0]
				)
			);
	}
}
else
{
	echo __('No comments!', true);
}

echo $javascript->codeBlock
	(
		sprintf
		(
			'Element.update("comments-counter-%s", "%s");',
			$article['Article']['id'],
			sprintf(__n('%s comment', '%s comments', $comments_count, true), $comments_count)
		)
	);
?>
