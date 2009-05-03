<div class="comment-controls">
	<div id="working" style="display:none;"><?php echo $html->image('throbber.gif'); ?></div>
	<h3>Article comments &mdash; <?php
		echo $ajax->link('View', array('controller' => 'comments', 'action' => 'view', $this->passedArgs[0], 'ajax' => '1'),
				array
				(
					'update'		=> 'comments-inner-wrap',
					'indicator'		=> 'working',
					'before'		=> 'Element.update("comments-inner-wrap", "Please wait...")',
					'id'			=> 'view-comments-'.$articleId
				)
			); ?> &middot; <?php
		echo $ajax->link('Add', array('controller' => 'comments', 'action' => 'add', $this->passedArgs[0], 'ajax' => '1'),
				array
				(
					'update'		=> 'comments-inner-wrap',
					'indicator'		=> 'working',
					'before'		=> 'Element.update("comments-inner-wrap", "Please wait...")',
					'id'			=> 'add-comment-'.$articleId
				)
			); ?></h3>
	<hr />
</div>
<?php echo $ajax->div('comments-inner-wrap'); ?>
	<?php echo $this->element('comments/paginated'); ?>
<?php echo $ajax->divEnd('comments-inner-wrap'); ?>