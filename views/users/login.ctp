<?php $this->pageTitle = __('User login', true); ?>
<h2><?php __('User login'); ?></h2>
<?php
echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'id' => 'login_form'));
	echo $html->div('loginbox');
		echo $form->input('username', array('label' => __('Username', true)));
		echo $form->input('password', array('label' => __('Password', true)));
		echo $form->input
			(
				'User.remember_me',
				array
				(
					'type'	=> 'checkbox',
					'style' => 'display:inline; margin-top:15px;',
					'label' => array
					(
						'style' => 'display:inline; margin-bottom:15px; margin-left:5px;',
						'text' => __('Remember me', true)
					)
				)
			);
		?>
		<br />
		<?php echo $form->submit(__('Login', true), array('class' => 'button')); ?>
	</div>
<?php
echo $form->end();
echo $javascript->codeBlock('Form.focusFirstElement("login_form");');


$registerLink = $html->link
	(
		__('Register', true),
		array
		(
			'controller' => 'users',
			'action' => 'register'
		)
	);
$forgotPasswordLink = $html->link
	(
		__('Forgot your password?', true),
		array
		(
			'controller' => 'users',
			'action' => 'password_reset'
		)
	);

echo $html->div
	(
		'login-links',
		sprintf('%s | %s', $registerLink, $forgotPasswordLink)
	);
?>