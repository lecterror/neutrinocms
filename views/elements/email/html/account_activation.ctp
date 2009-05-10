<p><?php echo sprintf(__('Dear %s %s,', true), $user['User']['first_name'], $user['User']['last_name']); ?>
<br /><br />
<?php __('To confirm your account registration please follow the activation link:'); ?>
</p>
<p>
<br />
<?php
$activationLink = $html->url
	(
		array
		(
			'controller' => 'users',
			'action' => 'activate',
			'code' => $user['User']['hash']
		),
		true
	);

echo $html->link($activationLink, $activationLink);
?>
<br />
</p>