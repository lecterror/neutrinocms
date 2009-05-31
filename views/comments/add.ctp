<div id="comments-form-wrap">
	<?php
	echo $form->create
		(
			false,
			array
			(
				'url' => array
				(
					'controller'	=> 'comments',
					'action'		=> 'add',
					$this->passedArgs[0]
				),
				'id'			=> 'add-comment-form-'.$article['Article']['id']
			)
		);
	?>
	<div class="commentbox">
		<?php
		echo $form->input('Comment.name', array('label' => __('Your name', true)));
		echo $form->input('Comment.website', array('label' => __('Web site (leave empty if you don\'t have one)', true)));
		echo $form->input('Comment.email', array('label' => __('Your email (will not be published)', true)));
		echo $form->input('Comment.comment', array('class' => 'comment_area', 'label' => __('Comment', true)));

		echo $this->element('captcha');
		echo $form->input('Comment.captcha', array('label' => __('Spam prevention code', true), 'value' => '', 'autocomplete' => 'off'));
		echo $form->submit(__('Send', true), array('class' => 'button'));
		?>
	</div>
	<?php echo $form->end(); ?>
	<div id="working" style="display:none;">
		<?php echo $html->image('throbber.gif'); ?>
	</div>
</div>