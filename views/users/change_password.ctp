<?php $this->pageTitle = __('Change password', true); ?>
<h1><?php __('Change password'); ?></h1>
<?php
echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'change_password', $user['id']), 'id' => 'UserChangePasswordForm'));
	echo $html->div('loginbox');
		echo $form->input('User.password', array('type' => 'password', 'label' => __('Current password', true), 'value' => '', 'autocomplete' => 'off'));
		echo $form->input('User.passwd', array('type' => 'password', 'label' => __('New password', true), 'value' => '', 'autocomplete' => 'off'));
		echo $form->input('User.passwd_confirm', array('type' => 'password', 'label' => __('Confirm new password', true), 'value' => '', 'autocomplete' => 'off'));
		?>
		<br />
		<?php echo $form->submit(__('Change password', true), array('class' => 'button')); ?>
	</div>
<?php
echo $form->end();
echo $javascript->codeBlock('Form.focusFirstElement("UserChangePasswordForm");');
?>