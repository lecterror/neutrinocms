<?php echo sprintf(__('Dear %s %s,', true), $user['User']['first_name'], $user['User']['last_name']); ?>

<?php __('To confirm your account registration please follow the activation link:'); ?>

<?php
echo $html->url
	(
		array
		(
			'controller' => 'users',
			'action' => 'activate',
			'code' => $user['User']['hash']
		),
		true
	);
?>