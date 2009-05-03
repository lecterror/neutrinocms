<?php
echo $html->div('comments-form-wrap');
	echo $ajax->form(
		null,
		'post',
		array
		(
			'url' =>
				array
				(
					'controller'	=> 'comments',
					'action'		=> 'add',
					$this->passedArgs[0],
					'ajax'			=> '1'
				),
			'update'		=> 'comments-inner-wrap',
			'indicator'		=> 'working',
			'id'			=> 'add-comment-form-'.$article['Article']['id']
		)
	);
	?>
		<div class="commentbox">
			<?php echo $form->input('Comment.name', array('label' => 'Your name')); ?>
			<?php echo $form->input('Comment.website', array('label' => 'Web site (leave empty if you don\'t have one)')); ?>
			<?php echo $form->input('Comment.email', array('label' => 'Your email (will not be published)')); ?>
			<?php echo $form->input('Comment.comment', array('class' => 'comment_area')); ?>

			<?php echo $this->element('captcha'); ?>
			<?php echo $form->input('Comment.captcha', array('label' => 'Spam prevention code', 'autocomplete' => 'off')); ?>
			<?php echo $form->submit('Send', array('class' => 'button')); ?>
		</div>
	<?php echo $form->end(); ?>
</div>