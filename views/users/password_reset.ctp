<?php $this->pageTitle = __('Password reset', true); ?>
<h1><?php __('Password reset'); ?></h1>
<?php
if (isset($showForm) && $showForm == true)
{
	echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'password_reset'), 'id' => 'UserPasswordResetForm'));
		echo $html->div('loginbox');
			echo $form->input('User.email', array('label' => __('E-Mail', true), 'autocomplete' => 'off'));
			echo $this->element('captcha');
			echo $form->input('User.captcha', array('label' => __('Spam prevention code', true), 'value' => '', 'autocomplete' => 'off'));
			?>
			<br />
			<?php echo $form->submit(__('Reset password', true), array('class' => 'button')); ?>
		</div>
	<?php
	echo $form->end();
	echo $javascript->codeBlock('Form.focusFirstElement("UserPasswordResetForm");');
}
else
{
	?>
	<div class="loginbox">
		<?php __('Your password reset code has been sent to the e-mail address you have provided.'); ?>
		<?php __('Please follow the instructions sent to your address to complete the password reset.'); ?>
	</div>
	<?php
}
?>