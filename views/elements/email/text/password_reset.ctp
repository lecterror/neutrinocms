<?php echo sprintf(__('Dear %s %s,', true), $user['User']['first_name'], $user['User']['last_name']); ?>

<?php __('To reset your password and enter a new one, please follow the link below:'); ?>

<?php
echo $html->url
	(
		array
		(
			'controller' => 'users',
			'action' => 'password_reset_input',
			'code' => $user['User']['hash']
		),
		true
	);
?>