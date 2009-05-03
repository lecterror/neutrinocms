<?php $this->pageTitle = 'User login'; ?>
<?php echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'id' => 'login_form')); ?>
	<?php echo $html->div('loginbox'); ?>
		<?php echo $form->input('username'); ?>
		<?php echo $form->input('password'); ?>
		<?php
		echo $form->input('User.remember_me', array(
				'type'	=> 'checkbox',
				'label' => array('style' => 'display:inline; margin-bottom:15px;', 'text' => ' Remember me'),
				'style' => 'display:inline; margin-top:15px;'));
		?>
		<br />
		<?php echo $form->submit('Login', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>
<?php echo $javascript->codeBlock('Form.focusFirstElement("login_form");'); ?>

