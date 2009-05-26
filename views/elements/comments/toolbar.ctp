<div class="comment-controls">
	<h3><?php __('Article comments'); ?> &mdash; <?php
		echo $ajax->link
			(
				__('View', true),
				array
				(
					'controller' => 'comments',
					'action' => 'view',
					$this->passedArgs[0],
					'ajax' => '1'
				),
				array
				(
					'update'	=> 'comments-inner-wrap',
					'indicator'	=> 'working',
					'before'	=> sprintf
						(
							'Element.update("comments-inner-wrap", "%s")',
							__("Please wait...", true)
						),
					'id'		=> sprintf('view-comments-%s', $articleId)
				)
			);
		?></h3>
	<hr />
</div>
<?php echo $ajax->div('comments-inner-wrap'); ?>
	<?php
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
					//'indicator'		=> 'working',
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
