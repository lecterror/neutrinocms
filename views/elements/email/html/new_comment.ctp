<p>Dear <?php echo $email_user['first_name'].' '.$email_user['last_name']; ?>,
<br /><br />
A new comment has been posted for the article "<?php echo $email_article['Article']['title']; ?>".
</p>
<p>
Details<br />
-------
</p>
<table>
	<tr><td>Poster:</td><td><?php echo $email_comment['Comment']['name'].' &lt;'.$email_comment['Comment']['email'].'&gt;'; ?></td></tr>
	<tr><td>Web site:</td><td><?php echo $email_comment['Comment']['website']; ?></td></tr>
	<tr><td>Comment:</td><td></td></tr>
	<tr><td colspan="2"><?php echo nl2br($email_comment['Comment']['comment']); ?></td></tr>
</table>
<p>
View the article <?php echo $html->link('here', $html->url(array('controller' => 'articles', 'action' => 'view', $email_article['Article']['slug']), true)); ?>.
</p>