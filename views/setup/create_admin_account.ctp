<h2><?php __('Create administrator account'); ?></h2>
<?php echo $form->create(false, array('url' => array('action' => $this->action, 'create_admin_account'))); ?>
	<?php echo $html->div('install-inputbox'); ?>
		<?php echo $form->input('User.username', array('label' => __('Username', true))); ?>
		<?php echo $form->input('User.passwd', array('type' => 'password', 'label' => __('Password', true), 'value' => '')); ?>
		<?php echo $form->input('User.passwd_confirm', array('type' => 'password', 'label' => __('Confirm password', true), 'value' => '')); ?>
		<?php echo $form->input('User.email', array('label' => __('E-mail', true))); ?>
		<?php echo $form->input('User.first_name', array('label' => __('First name', true))); ?>
		<?php echo $form->input('User.last_name', array('label' => __('Last name', true))); ?>
		<br />
		<?php echo $form->submit(__('Next', true), array('class' => 'button')); ?>
	</div>
<?php echo $form->end(); ?>