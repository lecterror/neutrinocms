<?php echo sprintf(__('Dear %s %s,', true), $email_user['first_name'], $email_user['last_name']); ?>

<?php
echo sprintf
	(
		__('A new comment has been posted for the article "%s".', true),
		$email_article['Article']['title']
	);
?>

<?php __('Details'); ?>
-------
<?php __('Poster:'); ?>		<?php echo $email_comment['Comment']['name'].' <'.$email_comment['Comment']['email'].'>'; ?>

<?php __('Web site:'); ?>	<?php echo $email_comment['Comment']['website']; ?>

<?php __('Comment:'); ?>
<?php __('- BEGIN -'); ?>
<?php echo $email_comment['Comment']['comment']; ?>

<?php __('- END -'); ?>

<?php __('View the article here:'); ?>
<?php
echo $html->url
	(
		array
		(
			'controller' => 'articles',
			'action' => 'view',
			$email_article['Article']['slug']
		),
		true
	);
?>