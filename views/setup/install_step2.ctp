<h2><?php __('Step 2: Create administrator account'); ?></h2>
<?php echo $form->create(null, array('url' => array('controller' => 'setup', 'action' => 'install_step2'))); ?>
	<?php echo $html->div('install-inputbox'); ?>
		<?php echo $form->input('User.username', array('label' => __('Username', true))); ?>
		<?php echo $form->input('User.password', array('label' => __('Password', true))); ?>
		<?php echo $form->input('User.email', array('label' => __('E-mail', true))); ?>
		<?php echo $form->input('User.first_name', array('label' => __('First name', true))); ?>
		<?php echo $form->input('User.last_name', array('label' => __('Last name', true))); ?>
		<br />
		<?php echo $form->submit(__('Next', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>