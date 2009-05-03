<h2>Step 2: Create administrator account</h2>
<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'install_step2'))); ?>
	<?php echo $html->div('install-inputbox'); ?>
		<?php echo $form->input('User.username', array('label' => 'Username')); ?>
		<?php echo $form->input('User.password', array('label' => 'Password')); ?>
		<?php echo $form->input('User.email', array('label' => 'E-mail')); ?>
		<?php echo $form->input('User.first_name', array('label' => 'First name')); ?>
		<?php echo $form->input('User.last_name', array('label' => 'Last name')); ?>
		<br />
		<?php echo $form->submit('Next', array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>