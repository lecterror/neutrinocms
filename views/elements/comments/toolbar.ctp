<div class="comment-controls">
	<div id="working" style="display:none;">
		<?php echo $html->image('throbber.gif'); ?>
	</div>
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
		?>
		&middot;
		<?php
		echo $ajax->link
			(
				__('Add', true),
				array
				(
					'controller' => 'comments',
					'action' => 'add',
					$this->passedArgs[0],
					'ajax' => '1'
				),
				array
				(
					'update'		=> 'comments-inner-wrap',
					'indicator'		=> 'working',
					'before'		=> sprintf
						(
							'Element.update("comments-inner-wrap", "%s")',
							__("Please wait...", true)
						),
					'id'			=> sprintf('add-comment-%s', $articleId)
				)
			); ?></h3>
	<hr />
</div>
<?php echo $ajax->div('comments-inner-wrap'); ?>
	<?php echo $this->element('comments/paginated'); ?>
<?php echo $ajax->divEnd('comments-inner-wrap'); ?>