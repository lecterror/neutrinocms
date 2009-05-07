<p><?php echo sprintf(__('Dear %s %s,', true), $email_user['User']['first_name'], $email_user['User']['last_name']); ?>
<br /><br />
<?php
echo sprintf
	(
		__('A new comment has been posted for the article "%s"', true),
		$email_article['Article']['title']
	);
?>
</p>
<p>
<?php __('Details'); ?>
<br />
-------
</p>
<table>
	<tr><td><?php __('Poster:'); ?></td><td><?php echo $email_comment['Comment']['name'].' &lt;'.$email_comment['Comment']['email'].'&gt;'; ?></td></tr>
	<tr><td><?php __('Web site:'); ?></td><td><?php echo $email_comment['Comment']['website']; ?></td></tr>
	<tr><td><?php __('Comment:'); ?></td><td></td></tr>
	<tr><td colspan="2"><?php echo nl2br($email_comment['Comment']['comment']); ?></td></tr>
</table>
<?php
$viewLink = $html->link
	(
		__('here', true),
		$html->url
		(
			array
			(
				'controller' => 'articles',
				'action' => 'view',
				$email_article['Article']['slug']
			),
			true
		)
	);
?>
<p><?php sprintf(__('View the article %s', true), $viewLink); ?></p>