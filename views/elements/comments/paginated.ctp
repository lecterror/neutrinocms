<?php
// BUGFIX: when rendered from "add" would
// cause amazing effects for the end user
$this->passedArgs['action'] = 'view';
$this->passedArgs['ajax'] = 1;

if ($commentsCount > 0)
{
	?>
	<br />
	<?php
	foreach ($comments as $comment)
	{
		if ($auth->check('comments', 'view', $comment['Comment']['user_id']))
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
}
else
{
	__('No comments!');
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
