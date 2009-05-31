<div class="comment-controls">
	<h3><?php __('Article comments'); ?> &mdash; <?php
		echo $ajax->link
			(
				__('Reload', true),
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
					'id'		=> sprintf('view-comments-%s', $article['Article']['id'])
				)
			);
		?></h3>
	<hr />
</div>