Dear <?php echo $email_user['first_name'].' '.$email_user['last_name']; ?>,

A new comment has been posted for the article "<?php echo $email_article['Article']['title']; ?>".

Details
-------
Poster:		<?php echo $email_comment['Comment']['name'].' <'.$email_comment['Comment']['email'].'>'; ?>

Web site:	<?php echo $email_comment['Comment']['website']; ?>

Comment:
- BEGIN -
<?php echo $email_comment['Comment']['comment']; ?>

- END -

View the article here:
<?php echo $html->url(array('controller' => 'articles', 'action' => 'view', $email_article['Article']['slug']), true); ?>