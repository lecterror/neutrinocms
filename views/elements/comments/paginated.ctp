<?php
// BUGFIX: when rendered from "add" would
// cause amazing effects for the end user
$this->passedArgs['action'] = 'view';
$this->passedArgs['ajax'] = 1;
$paginator->options(
	array_merge(
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
	<div class="comment-paginate-counter"><?php echo $paginator->counter('Page %page% of %pages%'); ?></div>
	<div class="comment-paginate-numbers"><?php echo $paginator->numbers(array('separator' => ' &middot; ')); ?></div>
	<br />
	<?php

	foreach ($comments as $comment)
	{
		echo $this->element('comments/comment',
				array(
					'comment' => $comment['Comment'],
					'slug' => $this->passedArgs[0]
				)
			);
	}
}
else
	echo 'No comments!';

echo $javascript->codeBlock(
	'Element.update("comments-counter-'.$article['Article']['id'].'", "'.$comments_count.' comment'.($comments_count != 1 ? 's' : '').'");'
	);
?>
