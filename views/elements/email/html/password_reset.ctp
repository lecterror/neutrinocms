<p><?php echo sprintf(__('Dear %s %s,', true), $user['User']['first_name'], $user['User']['last_name']); ?>
<br /><br />
<?php __('To reset your password and enter a new one, please follow the link below:'); ?>
</p>
<p>
<br />
<?php
$resetLink = $html->url
	(
		array
		(
			'controller' => 'users',
			'action' => 'password_reset_input',
			'code' => $user['User']['hash']
		),
		true
	);

echo $html->link($resetLink, $resetLink);
?>
<br />
</p>