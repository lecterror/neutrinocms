<?php echo $ajax->div('comments-inner-wrap'); ?>
	<?php
	$commentsCount = count($article['Comment']);

	if ($commentsCount > 0)
	{
		?><br /><?php
		foreach ($article['Comment'] as $comment)
		{
			echo $this->element
				(
					'comments/comment',
					array
					(
						'comment' => $comment,
						'slug' => $this->passedArgs[0]
					)
				);
		}
	}
	else
	{
		__('No comments!');
	}

	echo $ajax->div('comments-form-wrap');
	echo $ajax->link
			(
				__('Add a comment', true),
				array
				(
					'controller' => 'comments',
					'action' => 'add',
					$this->passedArgs[0],
					'ajax' => '1'
				),
				array
				(
					'update'		=> 'comments-form-wrap',
					'before'		=> sprintf
						(
							'Element.update("comments-form-wrap", "%s")',
							__("Please wait...", true)
						),
					'id'			=> sprintf('add-comment-%s', $article['Article']['id'])
				)
			);
	echo $ajax->divEnd('comments-form-wrap');
	?>
<?php echo $ajax->divEnd('comments-inner-wrap'); ?>
