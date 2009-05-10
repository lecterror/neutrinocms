<?php $this->pageTitle = __('User registration', true); ?>
<h1><?php __('User registration'); ?></h1>
<?php
if (isset($showForm) && $showForm == true)
{
	echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'register'), 'id' => 'UserRegisterForm'));
		echo $html->div('loginbox');
			echo $form->input('User.username', array('label' => __('Username', true)));
			echo $form->input('User.passwd', array('type' => 'password', 'label' => __('Password', true), 'value' => ''));
			echo $form->input('User.passwd_confirm', array('type' => 'password', 'label' => __('Confirm password', true), 'value' => ''));
			echo $form->input('User.email', array('label' => __('E-Mail', true)));
			echo $form->input('User.first_name', array('label' => __('First name', true)));
			echo $form->input('User.last_name', array('label' => __('Last name', true)));
			?>
			<br />
			<?php echo $form->submit(__('Register', true), array('class' => 'button')); ?>
		</div>
	<?php
	echo $form->end();
	echo $javascript->codeBlock('Form.focusFirstElement("UserRegisterForm");');
}
else
{
	?>
	<div class="loginbox">
	<?php __('Your registration info and account activation code have been sent to the e-mail address you have provided.'); ?>
	<?php __('Please follow the instructions sent to your address to complete the registration.'); ?>
	</div>
	<?php
}
?>