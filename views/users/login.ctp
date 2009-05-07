<?php
$this->pageTitle = __('User login', true);

echo $form->create('User', array('url' => array('controller' => 'users', 'action' => 'login'), 'id' => 'login_form'));
	echo $html->div('loginbox');
		echo $form->input('username', array('label' => 'Username'));
		echo $form->input('password', array('label' => 'Password'));
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
?>