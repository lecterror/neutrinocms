<?php $this->pageTitle = __('Password reset', true); ?>
<h1><?php __('Password reset'); ?></h1>
<?php
if (isset($showForm) && $showForm == true)
{
	echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'password_reset_input'), 'id' => 'UserPasswordResetInputForm'));
		echo $html->div('loginbox');
			echo $form->hidden('User.code', array('value' => $code));
			echo $form->input('User.email', array('label' => __('E-Mail', true), 'autocomplete' => 'off'));
			echo $form->input('User.passwd', array('type' => 'password', 'label' => __('New password', true), 'value' => ''));
			echo $form->input('User.passwd_confirm', array('type' => 'password', 'label' => __('Confirm password', true), 'value' => ''));
			?>
			<br />
			<?php echo $form->submit(__('Save new password', true), array('class' => 'button')); ?>
		</div>
	<?php
	echo $form->end();
	echo $javascript->codeBlock('Form.focusFirstElement("UserPasswordResetInputForm");');
}
else
{
	?>
	<div class="loginbox">
		<?php __('Your new password has been saved successfully.'); ?>
		<?php
		$link = $html->link('login', array('controller' => 'users', 'action' => 'login'));
		echo sprintf(__('Proceed to %s.', true), $link);
		?>
	</div>
	<?php
}
?>